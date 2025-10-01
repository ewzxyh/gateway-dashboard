<?php

namespace App\Helpers;

use App\Models\App;

class TaxaFlexivelHelper
{
    /**
     * Calcula a taxa de depósito usando o sistema flexível
     * 
     * @param float $amount Valor do depósito
     * @param App $setting Configurações do sistema
     * @param User|null $user Usuário específico (opcional)
     * @return array ['taxa_cash_in' => float, 'deposito_liquido' => float, 'descricao' => string]
     */
    public static function calcularTaxaDeposito($amount, $setting, $user = null)
    {
        // Verificar se as taxas personalizadas estão ativas
        $taxasPersonalizadasAtivas = $user && isset($user->taxas_personalizadas_ativas) && $user->taxas_personalizadas_ativas;
        $usarConfiguracaoUsuario = $taxasPersonalizadasAtivas && $user->sistema_flexivel_ativo;
        
        if ($usarConfiguracaoUsuario) {
            // Usar configurações do usuário
            $valorMinimo = $user->valor_minimo_flexivel ?? $setting->taxa_flexivel_valor_minimo;
            $taxaFixaBaixo = $user->taxa_fixa_baixos ?? $setting->taxa_flexivel_fixa_baixo;
            $taxaPercentualAlto = $user->taxa_percentual_altos ?? $setting->taxa_flexivel_percentual_alto;
            $descricao = "FLEXIVEL_USUARIO";
        } elseif ($setting->taxa_flexivel_ativa) {
            // Usar configurações globais
            $valorMinimo = $setting->taxa_flexivel_valor_minimo;
            $taxaFixaBaixo = $setting->taxa_flexivel_fixa_baixo;
            $taxaPercentualAlto = $setting->taxa_flexivel_percentual_alto;
            $descricao = "FLEXIVEL_GLOBAL";
        } else {
            // Sistema flexível não ativo, usar sistema antigo
            return self::calcularTaxaAntiga($amount, $setting, $user);
        }

        if ($amount < $valorMinimo) {
            // Depósitos abaixo do valor mínimo: taxa fixa
            $taxa_cash_in = $taxaFixaBaixo;
            $deposito_liquido = $amount - $taxa_cash_in;
            $descricao .= "_FIXA";
        } else {
            // Depósitos acima do valor mínimo: taxa percentual
            $taxa_cash_in = ($amount * $taxaPercentualAlto) / 100;
            $deposito_liquido = $amount - $taxa_cash_in;
            $descricao .= "_PERCENTUAL";
        }

        return [
            'taxa_cash_in' => $taxa_cash_in,
            'deposito_liquido' => $deposito_liquido,
            'descricao' => $descricao
        ];
    }

    /**
     * Calcula a taxa usando o sistema antigo (baseline)
     * 
     * @param float $amount Valor do depósito
     * @param App $setting Configurações do sistema
     * @param User|null $user Usuário específico (opcional)
     * @return array ['taxa_cash_in' => float, 'deposito_liquido' => float, 'descricao' => string]
     */
    private static function calcularTaxaAntiga($amount, $setting, $user = null)
    {
        // Verificar se o usuário tem taxas personalizadas ativas
        if ($user && isset($user->taxas_personalizadas_ativas) && $user->taxas_personalizadas_ativas) {
            // Usar taxas personalizadas do usuário
            $taxaPercentual = $user->taxa_percentual_deposito ?? $setting->taxa_cash_in_padrao ?? 4.00;
            $taxaFixaAdicional = $user->taxa_fixa_deposito ?? 0.00;
            $descricao = "PERSONALIZADA_PORCENTAGEM";
        } else {
            // Usar taxas globais
            $taxaPercentual = $setting->taxa_cash_in_padrao ?? 4.00;
            $taxaFixaAdicional = 0.00;
            $descricao = "GLOBAL_PORCENTAGEM";
        }
        
        $baseline = $setting->baseline ?? 5.00;
        
        $taxatotal = ($amount * $taxaPercentual) / 100;
        $deposito_liquido = $amount - $taxatotal - $taxaFixaAdicional;
        $taxa_cash_in = $taxatotal + $taxaFixaAdicional;

        if ($taxatotal < $baseline) {
            $deposito_liquido = $amount - $baseline - $taxaFixaAdicional;
            $taxa_cash_in = $baseline + $taxaFixaAdicional;
            $descricao = str_replace('PORCENTAGEM', 'FIXA', $descricao);
        }

        return [
            'taxa_cash_in' => $taxa_cash_in,
            'deposito_liquido' => $deposito_liquido,
            'descricao' => $descricao
        ];
    }
}
