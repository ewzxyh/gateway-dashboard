<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Adquirente;
use App\Models\CheckoutBuild;
use App\Models\CheckoutDepoimento;
use App\Models\CheckoutOrders;
use App\Models\SolicitacoesCashOut;
use App\Models\Solicitacoes;
use App\Models\UsersKey;
use App\Models\User;
use App\Traits\ApiTrait;
use App\Helpers\Helper;
use App\Traits\{PagarMeTrait, EfiTrait, MercadoPagoTrait, CashtimeTrait, XgateTrait, WitetecTrait, PixupTrait, WooviTrait};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CheckoutControlller extends Controller
{
    public function index(Request $request)
    {
        $buscar = $request->input('buscar');

        $query = CheckoutBuild::where("user_id", auth()->id());

        if (!is_null($buscar)) {
            $query->where('produto_name', 'LIKE', "%$buscar%");
        }

        $checkouts = $query->get();

        return view("profile.checkout.index", compact("checkouts"));
    }

    public function indexEdit($id, Request $request)
    {
        $checkout = CheckoutBuild::where('id_unico', $id)->firstOrFail();
        return view("profile.checkout.edit", compact('checkout'));
    }

    public function v1($id, Request $request)
    {
        $checkout = CheckoutBuild::where("id_unico", $id)->first();
        $user = User::where('id', $checkout->user_id)->first();
        $keys = UsersKey::where('user_id', $user->user_id)->first();
        $token = $keys->token;
        $secret = $keys->secret;

        return view('profile.checkout.v1', compact('checkout', 'secret', 'token'));
    }

    public function v2(Request $request)
    {
        $id = $request->input("id");
        $produto = CheckoutBuild::where("referencia", $id)->first();
        $keys = UsersKey::where('user_id', $produto->user_id)->first();
        $token = $keys->token;
        $secret = $keys->secret;

        return view('profile.checkout.v2', compact('produto', 'secret', 'token'));
    }

    public function create(Request $request)
    {

        $validated = $request->validate([
            "produto_name" => "required|string",
            "produto_valor" => "required|numeric|min:0.01",
            "produto_descricao" => "required|string",
            "produto_tipo" => "required|string",
            "produto_tipo_cob" => "required|string"
        ], [
            'produto_valor.required' => 'O preço do produto é obrigatório.',
            'produto_valor.numeric' => 'O preço do produto deve ser um número válido.',
            'produto_valor.min' => 'O preço do produto deve ser maior que R$ 0,00.',
            'produto_name.required' => 'O nome do produto é obrigatório.',
            'produto_descricao.required' => 'A descrição do produto é obrigatória.',
            'produto_tipo.required' => 'O tipo do produto é obrigatório.',
            'produto_tipo_cob.required' => 'O tipo de cobrança é obrigatório.'
        ]);

        $data = $request->except(['_token', '_method', '/checkout']);

        $data['user_id'] = auth()->id();
        $data['id_unico'] = Str::uuid();
        $data['produto_valor'] = str_replace([","], '.', $data['produto_valor']);
        CheckoutBuild::create($data);
        return redirect()->back()->with('success', 'Checkout cadastrado com sucesso com sucesso!');
    }

    public function edit($id, Request $request)
    {
        // Validação dos campos obrigatórios
        $request->validate([
            "produto_name" => "required|string",
            "produto_valor" => "required|numeric|min:0.01",
            "produto_descricao" => "required|string",
            "produto_tipo" => "required|string",
            "produto_tipo_cob" => "required|string"
        ], [
            'produto_valor.required' => 'O preço do produto é obrigatório.',
            'produto_valor.numeric' => 'O preço do produto deve ser um número válido.',
            'produto_valor.min' => 'O preço do produto deve ser maior que R$ 0,00.',
            'produto_name.required' => 'O nome do produto é obrigatório.',
            'produto_descricao.required' => 'A descrição do produto é obrigatória.',
            'produto_tipo.required' => 'O tipo do produto é obrigatório.',
            'produto_tipo_cob.required' => 'O tipo de cobrança é obrigatório.'
        ]);

        // Criamos o registro sem as imagens
        $checkoutBuild = CheckoutBuild::where('id', $id)->first();
        $checkoutDir = public_path("/checkouts/{$checkoutBuild->id}/");
        if (!file_exists($checkoutDir)) {
            mkdir($checkoutDir, 0755, true);
        }
        $data = collect($request->all())
            ->reject(function ($value, $key) {
                return preg_match('/^checkout_depoimentos_/', $key)
                    || in_array($key, ['_token', '_method', 'checkout_depoimentos_id', 'checkout_depoimentos_nome', 'checkout_depoimentos_depoimento', 'checkout_depoimentos_image']);
            })
            ->toArray();

        $data['methods'] = json_encode($request->methods);
        $data['produto_valor'] = str_replace([","], '.', $data['produto_valor']);
        // Atualiza campos principais
        $checkoutBuild->update($data);

        // Atualiza imagens únicas como produto/banner/logo/etc
        $images_checkout = ['produto_image', 'checkout_header_logo', 'checkout_header_image', 'checkout_banner'];
        $dataImg = [];

        foreach ($images_checkout as $field) {
            if ($request->hasFile($field)) {
                $filename = 'checkout_' . $field . '.' . $request->file($field)->getClientOriginalExtension();
                $request->file($field)->move($checkoutDir, $filename);
                $dataImg[$field] = "/checkouts/{$checkoutBuild->id}/{$filename}";
            }
        }

        // Atualiza imagens únicas, se houver
        if (!empty($dataImg)) {
            $checkoutBuild->update($dataImg);
        }


        $checkoutBuild->fill([
            'checkout_timer_active' => $request->has('checkout_timer_active'),
            'checkout_header_logo_active' => $request->has('checkout_header_logo_active'),
            'checkout_header_image_active' => $request->has('checkout_header_image_active'),
            'checkout_topbar_active' => $request->has('checkout_topbar_active'),
            // outros campos...
        ])->save();

        return redirect()->back()->with('success', 'Checkout alterado com sucesso!');
    }

    public function destroy($id)
    {
        // Buscar o checkout pelo ID
        $checkout = CheckoutBuild::find($id);

        if (!$checkout) {
            return redirect()->back()->with('error', 'Checkout não encontrado.');
        }

        // Verifica se o usuário autenticado pode excluir esse checkout
        /* if (auth()->user()->user_id !== $checkout->user_id) {
            return redirect()->back()->with('error', 'Você não tem permissão para excluir este checkout.');
        } */

        // Deleta as imagens associadas, se existirem
        if ($checkout->logo_produto) {
            Storage::disk('public')->delete($checkout->logo_produto);
        }
        if ($checkout->banner_produto) {
            Storage::disk('public')->delete($checkout->banner_produto);
        }

        // Exclui o checkout do banco de dados
        $checkout->delete();

        return redirect()->back()->with('success', 'Checkout excluído com sucesso!');
    }

    public function gerarPedido(Request $request)
    {
        $data = $request->except(['_token']);
        $venda = CheckoutOrders::create($data);
        
        // Buscar o usuário do checkout para usar sua adquirente preferida
        $checkout = CheckoutBuild::where('id', $request->checkout_id)->first();
        $user = User::where('id', $checkout->user_id)->first();
        $default = Helper::adquirenteDefault($user->user_id);

        if ($request->metodo == 'card') {
            $creditcard = json_decode($data['credit_card']);
            $installment = json_decode($data['installment']);
            $checkout = CheckoutBuild::where('id', $request->checkout_id)->first();
            $user = User::where('id', $checkout->user_id)->first();
            $payload = [
                "user" => $user,
                "data" => [
                    "items" => [
                        [
                            'name'      => $checkout->produto_name,
                            'value'     => (int) $data['valor_total'] * 100,
                            'amount'    => 1
                        ]
                    ],
                    "payment" => [
                        "credit_card" => [
                            "customer" => [
                                "name"          => $data['name'],
                                "cpf"           => str_replace(['(', ')', ' ', '.', '-', '/'], '', $data['cpf']),
                                "email"         => $data['email'],
                                "phone_number"  => str_replace(['(', ')', ' ', '.', '-', '/'], '', $data['telefone']),
                            ],
                            "installments" => $installment->installment,
                            "payment_token" => $data['payment_token']
                        ]
                    ]
                ]
            ];

            if (!is_null($user->webhook_url) && in_array('gerado', (array) $user->webhook_endpoint)) {
                Http::withHeaders(['Content-Type' => 'application/json', 'Accept' => 'application/json'])
                    ->post($user->webhook_url, [
                        'nome' => $venda->name,
                        'cpf' => preg_replace('/\D/', '', $venda->cpf),
                        'telefone' => preg_replace('/\D/', '', $venda->telefone),
                        'email' => $venda->email,
                        'status' => 'pendente'
                    ]);
            }
            $newrequest = new Request($payload);
            //            dd($newrequest->all());
            $response = EfiTrait::requestCardEfi($payload);
            //dd($response);
            $status = isset($response['status']) && $response['status'] == 200 ? 'success' : 'error';
            if ($status == "success") {
                $cahsout = Solicitacoes::where('idTransaction', $response['data']['idTransaction'])->first();
                $cahsout->update(['descricao_transacao' => 'PRODUTO']);

                $venda->idTransaction = $response['data']['idTransaction'];
                $venda->qrcode = "";
                $venda->save();
                $valor_text = "R$ " . number_format($venda->valor_total, '2', ',', '.');
                return response()->json(["status" => $status, "data" => $response['data'], "valor_text" => $valor_text]);
            } else {
                return response()->json(['status' => 'error', 'message' => $response['message'] ?? "Verifique e tente novamente."]);
            }
        } elseif ($request->metodo == 'billet') {

            $data = $request->all();
            $checkout = CheckoutBuild::where('id', $request->checkout_id)->first();
            $user = User::where('id', $checkout->user_id)->first();
            $payload = [
                "user" => $user,
                "items" => [
                    [
                        'name'      => $checkout->produto_name,
                        'value'     => (int) $data['valor_total'] * 100,
                        'amount'    => 1
                    ]
                ],
                "payment" => [
                    "banking_billet" => [
                        "customer"          => [
                            "name"          => $data['name'],
                            "cpf"           => str_replace(['(', ')', ' ', '.', '-', '/'], '', $data['cpf']),
                            "email"         => $data['email'],
                            "phone_number"  => str_replace(['(', ')', ' ', '.', '-', '/'], '', $data['telefone']),

                        ],
                        "expire_at" => "2023-12-15",
                        "configurations" => [
                            "fine" => 200,
                            "interest" => 33
                        ]
                    ]
                ]

            ];

            if (isset($data['cep'])) {
                $address = [
                    "street"        => $data['endereco'],
                    "number"        => $data['numero'] ?? 0,
                    "neighborhood"  => $data['bairro'],
                    "zipcode"       => str_replace(['.', '-', ' '], '', $data['cep']),
                    "city"          => $data['cidade'],
                    "complement"    => $data['complemento'] ?? "",
                    "state"         => $data['estado']
                ];

                $payload['payment']['banking_billet']['customer']["address"] = $address;
            }

            //dd($payload);

            if (!is_null($user->webhook_url) && in_array('gerado', (array) $user->webhook_endpoint)) {
                Http::withHeaders(['Content-Type' => 'application/json', 'Accept' => 'application/json'])
                    ->post($user->webhook_url, [
                        'nome' => $venda->name,
                        'cpf' => preg_replace('/\D/', '', $venda->cpf),
                        'telefone' => preg_replace('/\D/', '', $venda->telefone),
                        'email' => $venda->email,
                        'status' => 'pendente'
                    ]);
            }
            $newrequest = new Request($payload);
            //            dd($newrequest->all());
            $response = EfiTrait::requestBoletoEfi($newrequest);
            //dd($response);
            $status = isset($response['status']) && $response['status'] == 200 ? 'success' : 'error';
            if ($status == "success") {
                $cahsout = Solicitacoes::where('idTransaction', $response['data']['idTransaction'])->first();
                $cahsout->update(['descricao_transacao' => 'PRODUTO']);

                $venda->idTransaction = $response['data']['idTransaction'];
                $venda->qrcode = $response['data']['qrcode'];
                $venda->save();
                $valor_text = "R$ " . number_format($venda->valor_total, '2', ',', '.');
                return response()->json(["status" => $status, "data" => $response['data'], "valor_text" => $valor_text]);
            } else {
                return response()->json(['status' => 'error', 'message' => $response['message'] ?? "Verifique e tente novamente."]);
            }
        }

        if (!$venda) {
            return response()->json(['status' => 'error', 'message' => 'Houve um erro. Tente novamente!']);
        }

        $checkout = CheckoutBuild::where('id', $venda->checkout_id)->first();
        $user = User::where('id', $checkout->user_id)->first();
        $chaves = UsersKey::where('user_id', $user->user_id)->first();

        $dataRequest = [
            'token' => $chaves->token,
            'secret' => $chaves->secret,
            'amount' => $venda->valor_total,
            'debtor_name' => $venda->name,
            'email' => $venda->email,
            'debtor_document_number' => $venda->cpf,
            'phone' => $venda->telefone,
            'method_pay' => 'pix',
            'postback' => 'web',
            'user' => $user
        ];

        if (!is_null($user->webhook_url) && in_array('gerado', (array) $user->webhook_endpoint)) {
            Http::withHeaders(['Content-Type' => 'application/json', 'Accept' => 'application/json'])
                ->post($user->webhook_url, [
                    'nome' => $venda->name,
                    'cpf' => preg_replace('/\D/', '', $venda->cpf),
                    'telefone' => preg_replace('/\D/', '', $venda->telefone),
                    'email' => $venda->email,
                    'status' => 'pendente'
                ]);
        }

        $request = new Request($dataRequest);


        switch ($default) {
            case 'cashtime':
                $response = CashtimeTrait::requestDepositCashtime($request);
                break;
            case 'mercadopago':
                $response = MercadoPagoTrait::requestDepositMercadoPago($request);
                break;
            case 'efi':
                $response = EfiTrait::requestDepositEfi($request);
                break;
            case 'pagarme':
                $response = PagarMeTrait::requestDepositPagarme($request);
                break;
            case 'xgate':
                $response = XgateTrait::requestDepositXgate($request);
                break;
            case 'witetec':
                $response = WitetecTrait::requestDepositWitetec($request);
                break;
            case 'pixup':
                $response = PixupTrait::requestDepositPixup($request);
                break;
            case 'woovi':
                $response = WooviTrait::requestPaymentWoovi($request);
                break;
        }

        $status = isset($response['status']) && $response['status'] == 200 ? 'success' : 'error';
        if ($status == "success") {
            $cahsout = Solicitacoes::where('idTransaction', $response['data']['idTransaction'])->first();
            $cahsout->update(['descricao_transacao' => 'PRODUTO']);

            $venda->idTransaction = $response['data']['idTransaction'];
            $venda->qrcode = $response['data']['qrcode'];
            $venda->save();
            $valor_text = "R$ " . number_format($venda->valor_total, '2', ',', '.');
            return response()->json(["status" => $status, "data" => $response['data'], "valor_text" => $valor_text]);
        } else {
            return response()->json(['status' => 'error', 'message' => "Verifique e tente novamente."]);
        }
    }

    public function statusPedido(Request $request)
    {
        $data = $request->except(['/checkout/cliente/pedido/status']);
        $order = CheckoutOrders::where('idTransaction', $data['idTransaction'])->first();

        $status = $order->status;
        $message = "Aguardando pagamento...";
        if ($status == 'pago') {
            $message = "Pagamento realizado com sucesso!";
        }
        return response()->json(compact('status', 'message'));
        //dd($data, $order);
    }

    public function salvarDepoimento(Request $request)
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'depoimento' => 'required|string|max:1000',
            'image' => 'nullable|image|max:2048',
            'avatar' => 'nullable|string',
            'id' => 'nullable|string',
            'checkout_id' => 'required'
        ]);

        $depoimento = [
            'id' => $validated['id'],
            'nome' => $validated['nome'],
            'depoimento' => $validated['depoimento'],
            'avatar' => $validated['avatar'] ?? null,
            'checkout_id' => $validated['checkout_id'],
        ];

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = 'dep_' . $depoimento['id'] . '.' . $file->getClientOriginalExtension();
            $path = "checkouts/{$depoimento['id']}/";
            $file->move(public_path($path), $filename);
            $depoimento['avatar'] = '/' . $path . $filename;
        }
        //dd($depoimento);
        // Validação e sanitização dos dados antes de inserir
        $depoimento = array_map('strip_tags', $depoimento);
        $depoimento = array_map('trim', $depoimento);
        
        // Aqui você pode salvar em banco se quiser
        if (is_null($depoimento['id'])) {
            unset($depoimento['id']);
            $depoimento = DB::table('checkout_depoimentos')->insert($depoimento);
        } else {
            // Validação adicional para update
            $existingDepoimento = DB::table('checkout_depoimentos')->where('id', $depoimento['id'])->first();
            if ($existingDepoimento) {
                DB::table('checkout_depoimentos')->where('id', $depoimento['id'])->update($depoimento);
            }
        }


        return response()->json([
            'success' => true,
            'depoimento' => $depoimento
        ]);
    }


    public function removerDepoimento(Request $request)
    {
        $id = $request->input('id');

        if (!$id) {
            return response()->json(['success' => false, 'message' => 'ID não informado.'], 400);
        }

        $depoimento = CheckoutDepoimento::find($id);

        if (!$depoimento) {
            return response()->json(['success' => false, 'message' => 'Depoimento não encontrado.'], 404);
        }

        try {
            $depoimento->delete();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Erro ao remover depoimento.']);
        }
    }
}
