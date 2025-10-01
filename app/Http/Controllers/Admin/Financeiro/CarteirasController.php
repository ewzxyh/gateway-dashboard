<?php

namespace App\Http\Controllers\Admin\Financeiro;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Solicitacoes;

class CarteirasController extends Controller
{
    public function index()
    {
        $total_em_carteiras = DB::table('users')
            ->sum('saldo') ?: 0;

        // Consultar o total de solicitações pagas
        $totalPaidOut = DB::table('solicitacoes')
            ->where('status', 'PAID_OUT')
            ->sum('amount') ?: 0;

        // Consultar o total de cash outs completados
        $totalCompleted = DB::table('solicitacoes_cash_out')
            ->where('status', 'COMPLETED')
            ->sum('amount') ?: 0;

        // Calcular o total bruto no gateway
        $totalBrutoGateway = $totalPaidOut - $totalCompleted;

        $usuarios = User::get();

        // Consulta para obter os 3 usuários com mais saldo (faturamento)
        $topUsuarios = DB::table('users')
            ->limit(3)
            ->get();
		
      	$top3Users = Solicitacoes::select(
        'user_id',
        DB::raw('SUM(amount) as total_amount'),
        DB::raw('COUNT(*) as total_paid_out')
        )
        ->where('status', 'PAID_OUT')
        ->where('user_id','!=','admin')
        ->groupBy('user_id')
        ->orderByDesc('total_amount') // << agora ordenando pela soma de amount
        ->limit(3)
        ->get()
        ->map(function ($item) {
            $item->user = User::where('user_id', $item->user_id)->first();
            return $item;
        });




        // Passar as variáveis para a view
        return view('admin.financeiro.carteiras', compact(
            'total_em_carteiras',
            'totalPaidOut',
            'totalCompleted',
            'totalBrutoGateway',
            'usuarios',
            'top3Users',
        ));
    }
}