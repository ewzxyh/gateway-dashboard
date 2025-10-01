<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Solicitacoes;
use App\Models\SolicitacoesCashOut;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Str;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        Helper::calcularSaldoLiquidoUsuarios();

        $periodo = $request->input('periodo', 'hoje');

        // Filtros de datas
        $dataInicio = null;
        $dataFim = null;

        switch ($periodo) {
            case 'hoje':
                $dataInicio = Carbon::today()->startOfDay();
                $dataFim = Carbon::today()->endOfDay();
                break;

            case 'ontem':
                $dataInicio = Carbon::yesterday()->startOfDay();
                $dataFim = Carbon::yesterday()->endOfDay();
                break;

            case '7dias':
                $dataInicio = Carbon::today()->subDays(6)->startOfDay();
                $dataFim = Carbon::today()->endOfDay();
                break;

            case '30dias':
                $dataInicio = Carbon::today()->subDays(29)->startOfDay();
                $dataFim = Carbon::today()->endOfDay();
                break;

            case 'tudo':
                $dataInicio = Carbon::today()->subYears(100)->startOfDay();
                $dataFim = Carbon::today()->endOfDay();
                break;

            default:
                if (Str::contains($periodo, ':')) {
                    [$start, $end] = explode(':', $periodo);
                    $dataInicio = Carbon::parse($start)->startOfDay();
                    $dataFim = Carbon::parse($end)->endOfDay();
                } else {
                    $dataInicio = Carbon::today()->startOfDay();
                    $dataFim = Carbon::today()->endOfDay();
                }
                break;
        }


        $solicitacoes = Solicitacoes::where('status', 'PAID_OUT');
        $saques = SolicitacoesCashOut::where('status', 'COMPLETED');

        if ($dataInicio && $dataFim) {
            $solicitacoes->whereBetween('date', [$dataInicio, $dataFim]);
            $saques->whereBetween('date', [$dataInicio, $dataFim]);
        }

        // Agora aplique nas variáveis
        $lucroDepositos = (clone $solicitacoes)->sum('taxa_cash_in');
        $lucroSaques = (clone $saques)->sum('taxa_cash_out');
        $lucro_liquido = $lucroDepositos + $lucroSaques;
  
        $valor_aprovado = (clone $solicitacoes)->sum('amount') + (clone $saques)->sum('amount');
        $transacoes_aprovadas = (clone $solicitacoes)->count() + (clone $saques)->count();

        $cadastros_total = User::whereBetween('created_at', [$dataInicio, $dataFim])->count();
        $cadastros_analise = User::where('status', 5)->whereBetween('created_at', [$dataInicio, $dataFim])->count();

        $saques_pendentes = SolicitacoesCashOut::where('status', 'PENDING')->whereBetween('date', [$dataInicio, $dataFim]);
 		$carteiras = User::sum('saldo') + User::sum('saldo_bloqueado');
      
        return view("admin.dashboard", compact(
          	"carteiras",
            "solicitacoes",
            "saques",
            "lucro_liquido",
            "lucroDepositos",
            "lucroSaques",
            "valor_aprovado",
            "transacoes_aprovadas",
            "cadastros_total",
            "cadastros_analise",
            "saques_pendentes"
            // outros dados se quiser manter os totais fora do filtro
        ));
    }
}
