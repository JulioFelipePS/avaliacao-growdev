<?php

namespace App\Services;

use App\Models\Log;
use App\Models\Movimentacao;

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

    public function getDataComMaiorQuantidadeDeMovimentacoes(){
        $inicioOperacao = now();
        $movimentacoesPorData = Movimentacao::selectRaw('DATE(data_hora) as data, COUNT(*) as quantidade')
            ->groupBy('data')
            ->orderByDesc('quantidade')
            ->first();
        $fimOperacao = now();
        $tempoExtracao = $inicioOperacao->diffInSeconds($fimOperacao);
        $logOperacao = new Log([
            'acao'=>'Calculo',
            'detalhes'=>'Calculo da Data com maior quantidade de movimentacoes.Tempo total do calculo:' . $tempoExtracao.' segundos.'
        ]);
        $logOperacao->save();
        return response()->json([
                'data' => $movimentacoesPorData->data,
                'quantidade' => $movimentacoesPorData->quantidade
        ]);
        
    }

    public function getDataComMenorQuantidadeDeMovimentacoes(){
        $inicioOperacao = now();
        $movimentacoesPorData = Movimentacao::selectRaw('DATE(data_hora) as data, COUNT(*) as quantidade')
            ->groupBy('data')
            ->orderBy('quantidade','asc')
            ->first();
        $fimOperacao = now();
        $tempoExtracao = $inicioOperacao->diffInSeconds($fimOperacao);
        $logOperacao = new Log([
            'acao'=>'Calculo',
            'detalhes'=>'Calculo da Data com menor quantidade de movimentacoes.Tempo total do calculo:' . $tempoExtracao.' segundos.'
        ]);
        $logOperacao->save();
        return response()->json([
                'data' => $movimentacoesPorData->data,
                'quantidade' => $movimentacoesPorData->quantidade
        ]); 
    }

    public function getDataComMaiorSomaDeMovimentacoes(){
        $inicioOperacao = now();
        $movimentacoesPorData = Movimentacao::selectRaw('DATE(data_hora) as data, SUM(debito + credito) as total_movimentacao')
            ->groupBy('data')
            ->orderBy('total_movimentacao', 'desc')
            ->first();
        $fimOperacao = now();
        $tempoExtracao = $inicioOperacao->diffInSeconds($fimOperacao);
        $logOperacao = new Log([
            'acao'=>'Calculo',
            'detalhes'=>'Calculo da Data com maior somatorio de valor movimentado.Tempo total do calculo:' . $tempoExtracao.' segundos.'
        ]);
        $logOperacao->save();
        return response()->json([
                'data' => $movimentacoesPorData->data,
                'total_movimentacao' => $movimentacoesPorData->total_movimentacao
        ]);
    
    }

    public function getDataComMenorSomaDeMovimentacoes(){
        $inicioOperacao = now();
        $movimentacoesPorData = Movimentacao::selectRaw('DATE(data_hora) as data, SUM(debito + credito) as total_movimentacao')
            ->groupBy('data')
            ->orderBy('total_movimentacao', 'asc')
            ->first();
        $fimOperacao = now();
        $tempoExtracao = $inicioOperacao->diffInSeconds($fimOperacao);
        $logOperacao = new Log([
            'acao'=>'Calculo',
            'detalhes'=>'Calculo da Data com menor somatorio de valor movimentado.Tempo total do calculo:' . $tempoExtracao.' segundos.'
        ]);
        $logOperacao->save();
        return response()->json([
                'data' => $movimentacoesPorData->data,
                'total_movimentacao' => $movimentacoesPorData->total_movimentacao
        ]);
    
    }

    public function getDiaComMaisMovimentacoesRX1(){
        $inicioOperacao = now();
        $movimentacoesPorDia = Movimentacao::selectRaw('DAYOFWEEK(data_hora) as dia_da_semana, COUNT(*) as quantidade')
            ->where('codigo', 'RX1') // Filtra pelas movimentações do código 'RX1'
            ->groupBy('dia_da_semana')
            ->orderBy('quantidade', 'desc')
            ->first();
        $fimOperacao = now();
        $tempoExtracao = $inicioOperacao->diffInSeconds($fimOperacao);
        $logOperacao = new Log([
            'acao'=>'Calculo',
            'detalhes'=>'Calculo do dia da semana com mais movimentacoes RX1.Tempo total do calculo:' . $tempoExtracao.' segundos.'
        ]);
        $logOperacao->save();
        if ($movimentacoesPorDia) {
            // Conversão do número do dia da semana para nome do dia
            $diasSemana = [
                1 => 'Domingo',
                2 => 'Segunda-feira',
                3 => 'Terça-feira',
                4 => 'Quarta-feira',
                5 => 'Quinta-feira',
                6 => 'Sexta-feira',
                7 => 'Sábado'
            ];

            return response()->json([
                'dia_da_semana' => $diasSemana[$movimentacoesPorDia->dia_da_semana],
                'quantidade' => $movimentacoesPorDia->quantidade
            ]);
        } else {
            return response()->json([
                'message' => 'Nenhuma movimentacao encontrada para o codigo "RX1".'
            ]);
        }
    }

    public function getDiaComMaisMovimentacoesPX1(){
        $inicioOperacao = now();
        $movimentacoesPorDia = Movimentacao::selectRaw('DAYOFWEEK(data_hora) as dia_da_semana, COUNT(*) as quantidade')
            ->where('codigo', 'PX1') // Filtra pelas movimentações do código 'RX1'
            ->groupBy('dia_da_semana')
            ->orderBy('quantidade', 'desc')
            ->first();
        $fimOperacao = now();
        $tempoExtracao = $inicioOperacao->diffInSeconds($fimOperacao);
        $logOperacao = new Log([
            'acao'=>'Calculo',
            'detalhes'=>'Calculo do dia da semana com mais movimentacoes PX1.Tempo total do calculo:' . $tempoExtracao.' segundos.'
        ]);
        $logOperacao->save();

        if ($movimentacoesPorDia) {
            $diasSemana = [
                1 => 'Domingo',
                2 => 'Segunda-feira',
                3 => 'Terça-feira',
                4 => 'Quarta-feira',
                5 => 'Quinta-feira',
                6 => 'Sexta-feira',
                7 => 'Sábado'
            ];

            return response()->json([
                'dia_da_semana' => $diasSemana[$movimentacoesPorDia->dia_da_semana],
                'quantidade' => $movimentacoesPorDia->quantidade
            ]);
        } else {
            return response()->json([
                'message' => 'Nenhuma movimentacao encontrada para o codigo "RX1".'
            ]);
        }
    }
    public function getDiaComMaisMovimentacoesX18(){
        $inicioOperacao = now();
        $movimentacoesPorDia = Movimentacao::selectRaw('DAYOFWEEK(data_hora) as dia_da_semana, COUNT(*) as quantidade')
            ->where('codigo', 'X18') // Filtra pelas movimentações do código 'RX1'
            ->groupBy('dia_da_semana')
            ->orderBy('quantidade', 'desc')
            ->first();
        $fimOperacao = now();
        $tempoExtracao = $inicioOperacao->diffInSeconds($fimOperacao);
        $logOperacao = new Log([
            'acao'=>'Calculo',
            'detalhes'=>'Calculo do dia da semana com mais movimentacoes X18.Tempo total do calculo:' . $tempoExtracao.' segundos.'
        ]);
        $logOperacao->save();

        if ($movimentacoesPorDia) {
            $diasSemana = [
                1 => 'Domingo',
                2 => 'Segunda-feira',
                3 => 'Terça-feira',
                4 => 'Quarta-feira',
                5 => 'Quinta-feira',
                6 => 'Sexta-feira',
                7 => 'Sábado'
            ];

            return response()->json([
                'dia_da_semana' => $diasSemana[$movimentacoesPorDia->dia_da_semana],
                'quantidade' => $movimentacoesPorDia->quantidade
            ]);
        } else {
            return response()->json([
                'message' => 'Nenhuma movimentacao encontrada para o codigo "X18".'
            ]);
        }
    }
    public function getQuantidadeMovimentacoesPorCoopAgencia(){
        $inicioOperacao=now();
        $movimentacoesPorCoopAgencia = Movimentacao::selectRaw('cooperativa, agencia, COUNT(*) as quantidade')
            ->groupBy('cooperativa', 'agencia')
            ->orderBy('quantidade', 'desc')
            ->get();
        $fimOperacao = now();
        $tempoExtracao = $inicioOperacao->diffInSeconds($fimOperacao);
        $logOperacao = new Log([
            'acao'=>'Calculo',
            'detalhes'=>'Calculo da maior quantidade de movimentacoes por agencia.Tempo total do calculo:' . $tempoExtracao.' segundos.'
        ]);
        $logOperacao->save();

        return response()->json($movimentacoesPorCoopAgencia);
    }

    public function getSomaValoresMovimentacoesPorCoopAgencia(){
        $inicioOperacao=now();
        $movimentacoesPorCoopAgencia = Movimentacao::selectRaw('cooperativa, agencia, SUM(debito + credito) as total_valores')
            ->groupBy('cooperativa', 'agencia')
            ->orderBy('total_valores', 'desc')
            ->get();
        $fimOperacao = now();
        $tempoExtracao = $inicioOperacao->diffInSeconds($fimOperacao);
        $logOperacao = new Log([
            'acao'=>'Calculo',
            'detalhes'=>'Calculo da maior soma movimentada por agencia.Tempo total do calculo:' . $tempoExtracao.' segundos.'
        ]);
        $logOperacao->save();

        return response()->json($movimentacoesPorCoopAgencia);
    }

    public function getRelacaoSomaValoresQuantidadePorCoopAgencia(){
        $inicioOperacao=now();
        $movimentacoesPorCoopAgencia = Movimentacao::selectRaw('cooperativa, agencia, COUNT(*) as quantidade, SUM(debito + credito) as total_valores')
            ->groupBy('cooperativa', 'agencia')
            ->get()
            ->map(function($item) {
                $item->relacao = $item->total_valores / $item->quantidade; 
                return $item;
            })
            ->sortByDesc('relacao');
        $fimOperacao = now();
        $tempoExtracao = $inicioOperacao->diffInSeconds($fimOperacao);
        $logOperacao = new Log([
            'acao'=>'Calculo',
            'detalhes'=>'Calculo da maior relacao soma movimentada/quantidade de movimentacao por agencia.Tempo total do calculo:' . $tempoExtracao.' segundos.'
        ]);
        $logOperacao->save();

        return response()->json($movimentacoesPorCoopAgencia);
    }

    public function getRelacaoCreditosDebitosPorHora(){
        $inicioOperacao=now();
        $movimentacoesPorHora = Movimentacao::selectRaw('HOUR(data_hora) as hora, SUM(debito) as total_debito, SUM(credito) as total_credito, SUM(debito + credito) as total_valores')
            ->groupByRaw('HOUR(data_hora)')
            ->orderBy('hora')
            ->get();
    
        $relacaoPorHora = $movimentacoesPorHora->map(function($item) {
            if ($item->total_valores > 0) {
                $item->relacao_creditos = $item->total_credito / $item->total_valores;
                $item->relacao_debitos = $item->total_debito / $item->total_valores;
            } else {
                $item->relacao_creditos = 0; 
                $item->relacao_debitos = 0;  
            }
    
            return $item;
        });
        $fimOperacao = now();
        $tempoExtracao = $inicioOperacao->diffInSeconds($fimOperacao);
        $logOperacao = new Log([
            'acao'=>'Calculo',
            'detalhes'=>'Calculo da relacao credito e debito hora a hora .Tempo total do calculo:' . $tempoExtracao.' segundos.'
        ]);
        $logOperacao->save();
    
        return response()->json($relacaoPorHora);
    }
    



    
        
}