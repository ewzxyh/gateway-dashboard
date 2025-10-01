<?php

namespace App\Http\Controllers\Admin\Financeiro;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Solicitacoes;
use App\Models\ConfirmarDeposito;
use App\Models\SolicitacoesCashOut;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class TransacoesController extends Controller
{
    public function index(Request $request)
    {
        $limit = 10;

        // Página atual
        $page = $request->input('page', 1);
        $offset = ($page - 1) * $limit;

        $now = Carbon::now();

        $todayStart = $now->copy()->startOfDay();
        $todayEnd = $now->copy()->endOfDay();
        $startOfMonth = $now->copy()->startOfMonth();
        $startOfWeek = $now->copy()->startOfWeek();

        // Valores de depósitos
        $depositsPaidOutToday = Solicitacoes::where('status', 'PAID_OUT')
            ->whereBetween('date', [$todayStart, $todayEnd])
            ->sum('amount');

        $depositsPaidOutMonth = Solicitacoes::where('status', 'PAID_OUT')
            ->where('created_at', '>=', $startOfMonth)
            ->sum('amount');

        $depositsPaidOutTotal = Solicitacoes::where('status', 'PAID_OUT')->sum('amount');

        $pixGeneratedTotal = Solicitacoes::whereIn('status', ['RELEASE','PAID_OUT', 'WAITING_FOR_APPROVAL'])
            ->sum('amount');

        $totalRecords = DB::table('solicitacoes')
            ->where('status', "PAID_OUT")->count();
        $totalPages = ceil($totalRecords / $limit);

        // Consultar os registros com paginação
        $deposits = DB::table('solicitacoes')
            //->where('status', "PAID_OUT")
            ->orderByDesc('date')
            ->get();


        $transacoes_aprovadas = Solicitacoes::where('status', 'PAID_OUT')->count() + SolicitacoesCashOut::where('status', 'COMPLETED')->count();
        // Cálculo de lucro líquido usando Query Builder seguro
        $lucro_depositos_hoje = Solicitacoes::where('status', 'PAID_OUT')
            ->whereDate('date', Carbon::today())
            ->get()
            ->sum(function($item) { return $item->amount - $item->deposito_liquido; });
        
        $lucro_saques_hoje = SolicitacoesCashOut::where('status', 'COMPLETED')
            ->whereDate('date', Carbon::today())
            ->get()
            ->sum(function($item) { return $item->amount - $item->cash_out_liquido; });
        
        $lucro_liquido_hoje = $lucro_depositos_hoje + $lucro_saques_hoje;
        
        $lucro_depositos_mes = Solicitacoes::where('status', 'PAID_OUT')
            ->whereMonth('date', Carbon::now()->month)
            ->whereYear('date', Carbon::now()->year)
            ->get()
            ->sum(function($item) { return $item->amount - $item->deposito_liquido; });
        
        $lucro_saques_mes = SolicitacoesCashOut::where('status', 'COMPLETED')
            ->whereMonth('date', Carbon::now()->month)
            ->whereYear('date', Carbon::now()->year)
            ->get()
            ->sum(function($item) { return $item->amount - $item->cash_out_liquido; });
        
        $lucro_liquido_mes = $lucro_depositos_mes + $lucro_saques_mes;
        
        $lucro_depositos_total = Solicitacoes::where('status', 'PAID_OUT')
            ->get()
            ->sum(function($item) { return $item->amount - $item->deposito_liquido; });
        
        $lucro_saques_total = SolicitacoesCashOut::where('status', 'COMPLETED')
            ->get()
            ->sum(function($item) { return $item->amount - $item->cash_out_liquido; });
        
        $lucro_liquido_total = $lucro_depositos_total + $lucro_saques_total;

        $transacoes_aprovadas = Solicitacoes::where('status', 'PAID_OUT')->count();
        $valor_aprovado_hoje = Solicitacoes::where('status', 'PAID_OUT')->whereDate('date', Carbon::today())->sum('amount') + SolicitacoesCashOut::where('status', 'COMPLETED')->whereDate('date', Carbon::today())->sum('amount');
        $valor_aprovado_mes = Solicitacoes::where('status', 'PAID_OUT')->whereMonth('date', Carbon::now()->month)->whereYear('date', Carbon::now()->year)->sum('amount') + SolicitacoesCashOut::where('status', 'COMPLETED')->whereMonth('date', Carbon::now()->month)->whereYear('date', Carbon::now()->year)->sum('amount');
        $valor_aprovado_total = Solicitacoes::where('status', 'PAID_OUT')->sum('amount') + SolicitacoesCashOut::where('status', 'COMPLETED')->sum('amount');


        return view("admin.financeiro.transacoes", compact(
            "transacoes_aprovadas",
            "lucro_liquido_hoje",
            "lucro_liquido_mes",
            "lucro_liquido_total",
            "valor_aprovado_hoje",
            "valor_aprovado_mes",
            "valor_aprovado_total",
            "depositsPaidOutToday",
            "depositsPaidOutMonth",
            "depositsPaidOutTotal",
            "pixGeneratedTotal",
            'deposits',
            'totalPages',
            'page'
        ));
    }
}
