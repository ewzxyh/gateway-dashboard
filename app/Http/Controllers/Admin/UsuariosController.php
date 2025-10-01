<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UsersKey;
use App\Models\Adquirente;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class UsuariosController extends Controller
{
    public function index(Request $request)
    {
        $users = User::where('id', '>=', 1); // cria query base

        $status = $request->query('status');
        $buscar = $request->query('buscar');

        switch ($status) {
            case 'ativos':
                $users->where('banido', 0);
                break;
            case 'banidos':
                $users->where('banido', 1);
                break;
            case 'pendentes':
                $users->where('status', 5);
                break;
        }
        if (isset($buscar)) {
            $users->where('name', "LIKE", "%$buscar%");
        }

        $users = $users->get(); // executa a query e pega os resultados
        $gerentes = User::where('permission', 5)->get();
        $adquirentes = Adquirente::all();
        return view('admin.usuarios', compact('users', 'gerentes', 'adquirentes'));
    }

    public function detalhes($id, Request $request)
    {
        // Obter a data e hora atual usando Carbon
        $now = Carbon::now();

        // Início e fim do dia de hoje
        $todayStart = $now->copy()->startOfDay()->toDateTimeString();
        $todayEnd = $now->copy()->endOfDay()->toDateTimeString();

        // Início do mês
        $startOfMonth = $now->copy()->startOfMonth()->toDateTimeString();

        // Início da semana
        $startOfWeek = $now->copy()->startOfWeek()->toDateTimeString();

        // Consultas para obter os totais
        $totalCadastros = User::count();

        $cadastrosHoje = User::whereBetween('data_cadastro', [$todayStart, $todayEnd])
            ->count();

        $cadastrosMes = User::where('data_cadastro', '>=', $startOfMonth)
            ->count();

        $cadastrosSemana = User::where('data_cadastro', '>=', $startOfWeek)
            ->count();

        $usuario = User::find($id);
        return view('admin.usuariodetalhes', compact('usuario'));
    }

    public function usuarioStatus(Request $request)
    {
        $message = "";
        $usuarioId = $request->input('id');
        $usuario = User::where('id', $usuarioId)->first();

        if ($request->tipo === 'status') {
            $status = $usuario->status == 5 ? 1 : 5;
            $message = $usuario->status == 5 ? "Status alterado para pendente!" : "Status alterado para Aprovado";
            $usuario->update(['status' => $status]);
        }

        if ($request->tipo === 'banido') {
            $banido = $usuario->banido == 1 ? 0 : 1;
            $message = $usuario->banido == 1 ? "Usuário desbanido com sucesso!" : "Usuário banido com sucesso!";
            $usuario->update(['banido' => $banido]);
        }

        return redirect()->back()->with('success', $message);
    }

    public function destroy($id, Request $request)
    {
        $user = User::find($id);
        if (!$user) {
            return redirect()->back()->with('error', "Usuário não encontrado!");
        }

        $user->delete($id);
        return redirect()->route('admin.usuarios')->with('error', "Usuário removido com sucesso!");
    }

    public function edit($id, Request $request)
    {
        if (!isset($id)) {
            return redirect()->back()->with('error', "Selecione um usuário!");
        }

        $email = $request->input('email');
        $name = $request->input('name');
        $permission = $request->input('permission');
        $cpf_cnpj = $request->input('cpf_cnpj');
        $telefone = $request->input('telefone');
        $data_nascimento = $request->input('data_nascimento');


        $token = $request->input('token');
        $secret = $request->input('secret');


        $user = User::find($id);

        if (!isset($user)) {
            return redirect()->back()->with('error', "Usuário não encontrado!");
        }

        if ($user->cpf_cnpj != $cpf_cnpj) {
            $validation = $request->validate([
                'cpf_cnpj' => ['unique:users,cpf_cnpj'],
            ]);

            if (!$validation) {
                return redirect()->back()->with('error', "CPF já cadastrado na base!");
            }
        }

        if ($user->email != $email) {
            $validation = $request->validate([
                'email' => ['unique:users,email'],
            ]);

            if (!$validation) {
                return redirect()->back()->with('error', "Email já cadastrado na base!");
            }
        }

        $payl = [
            'email' => $email,
            'name' => $name,
            'permission' => $permission,
            'cpf_cnpj' => $cpf_cnpj,
            'data_nascimento' => $data_nascimento,
            'telefone' => $telefone
        ];

        $path = uniqid();
        if ($request->hasFile('foto_rg_frente')) {
            $fotoRgFrente = Helper::salvarArquivo($request, 'foto_rg_frente', $path);
            $payl['foto_rg_frente'] = $fotoRgFrente;
        }

        if ($request->hasFile('foto_rg_verso')) {
            $fotoRgVerso  = Helper::salvarArquivo($request, 'foto_rg_verso', $path);
            $payl['foto_rg_verso'] = $fotoRgVerso;
        }

        if ($request->hasFile('selfie_rg')) {
            $selfieRg     = Helper::salvarArquivo($request, 'selfie_rg', $path);
            $payl['selfie_rg'] = $selfieRg;
        }

        if (!is_null($request->password)) {
            // Validar nova senha com requisitos robustos
            $request->validate([
                'password' => [
                    'required',
                    'min:8',
                    'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&+#^~`|\\/:";\'<>,.=\-_\[\]{}()])[A-Za-z\d@$!%*?&+#^~`|\\/:";\'<>,.=\-_\[\]{}()]+$/',
                ],
            ], [
                'password.required' => 'A nova senha é obrigatória.',
                'password.min' => 'A nova senha deve ter pelo menos 8 caracteres.',
                'password.regex' => 'A nova senha deve conter pelo menos uma letra minúscula, uma letra maiúscula, um número e um caractere especial.',
            ]);
            
            $payl['password'] = Hash::make($request->input('password'));
        }

        if ($request->filled('gerente_percentage')) {
            $valorFormatado = $request->input('gerente_percentage'); // ex: "1.234,56"
            $valorParaBanco = number_format($valorFormatado, 2); // "1234.56"
            $payl['gerente_percentage'] = (float) $valorParaBanco;
        } else {
            $payl['gerente_percentage'] = 0.00;
        }

        if ($request->filled('gerente_id')) {
            $payl['gerente_id'] = $request->input('gerente_id');
        }

        $payl['gerente_aprovar'] = $request->has('gerente_aprovar');
        
        // Sistema de taxas personalizadas removido - usar apenas configurações globais
        
        // Processar adquirente específica
        $preferredAdquirente = $request->input('preferred_adquirente');
        if ($preferredAdquirente) {
            $payl['preferred_adquirente'] = $preferredAdquirente;
            $payl['adquirente_override'] = true;
        } else {
            $payl['preferred_adquirente'] = null;
            $payl['adquirente_override'] = false;
        }

        // Debug: Log final do payload antes da atualização
        \Log::info('Payload final antes da atualização:', $payl);
        
        User::where('id', $id)->update($payl);

        $userkey = UsersKey::where('user_id', $user->user_id)->first();
        if (!$userkey) {
            $user_id = $user->user_id;
            $userkey = UsersKey::create(compact('user_id', 'token', 'secret'));
        }

        $userkey->update(compact('token', 'secret'));

        return redirect()->back()->with('success', "Usuário alterado com sucesso!");
    }
}
