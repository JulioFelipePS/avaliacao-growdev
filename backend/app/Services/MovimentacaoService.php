<?php

namespace App\Services;



class MovimentacaoService
{
    function converterParaFloat($valor) {
        // Remover o ponto de milhar
        $valor = str_replace('.', '', $valor);
    
        // Substituir a vírgula por ponto
        $valor = str_replace(',', '.', $valor);
    
        // Converter para float
        return (float) $valor;
    }
        
}