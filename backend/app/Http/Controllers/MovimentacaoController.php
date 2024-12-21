<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessarMovimentacaoJob;
use App\Models\Log as ModelsLog;
use App\Models\Movimentacao;
use App\Services\MovimentacaoService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use PhpParser\Node\Stmt\TryCatch;

class MovimentacaoController extends Controller
{
    public function options(Request $request){
        return response('', 200)
            ->header('Access-Control-Allow-Origin', 'http://localhost:5173')
            ->header('Access-Control-Allow-Methods', 'POST, GET, OPTIONS, PUT, DELETE')
            ->header('Access-Control-Allow-Headers', 'Content-Type, X-Auth-Token, Authorization');
    }
    public function upload(Request $request)
    {
        try {
            header('Access-Control-Allow-Origin: http://localhost:5173'); 
            header('Access-Control-Allow-Methods: GET, POST, PUT, OPTIONS');
            header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token, Cookie');
    
            $request->validate([
                'arquivo' => 'required|file|max:2097152'
            ]);

            $MovimentacaoService = new MovimentacaoService();

            $inicioExtracao = now();
            $filePath = $request->file('arquivo')->store('uploads');
            $conteudoArquivo = Storage::get($filePath);
            $linhas = explode("\n", $conteudoArquivo);
            $fimExtracao = now();
            $tempoExtracao = $inicioExtracao->diffInSeconds($fimExtracao);
            $logExtracao = new ModelsLog([
                'acao'=>'EXTRACAO',
                'detalhes'=>'Fim da operacao de carga e extracao de arquivo. Tempo total de carga:' . $tempoExtracao.' segundos.'
            ]);
            $logExtracao->save();

            $coop = $agencia = $conta = $nome = $doc = $cod = $descricao = null;
            $debito = 0.00;
            $credito = 0.00;
            $dataHora = null;

            $inicioCarga = now();
            foreach ($linhas as $linha) {    

                if (preg_match_all('/(^\d{4})\/(\d{2})\s+(\d{5}-\d)\s+([a-zA-Z\s]+)\s+([[:alnum:]]+)\s+([a-zA-Z0-9]{3})\s+([a-zA-Z\s]+)\s+(\d{1,3}(?:\.\d{3})*,\d{2})\s+(\d{2})/', $linha, $matches)) {
                    $coop = $matches[1][0];
                    $agencia = $matches[2][0];
                    $conta = $matches[3][0];
                    $nome = $matches[4][0];
                    $doc = $matches[5][0];
                    $cod = $matches[6][0];
                    $descricao = $matches[7][0];
                    $debito = $MovimentacaoService->converterParaFloat($matches[8][0]);
                    $credito = 0.00;
                    
                }

                if (preg_match_all('/\s+(\d{5}-\d)\s+([a-zA-Z\s]+)\s+([[:alnum:]]+)\s+([a-zA-Z0-9]{3})\s+([a-zA-Z\s]+)\s+(\d{1,3}(?:\.\d{3})*,\d{2})\s+(\d{2})/', $linha, $matches)) {
                    $conta = $matches[1][0];
                    $nome = $matches[2][0];
                    $doc = $matches[3][0];
                    $cod = $matches[4][0];
                    $descricao = $matches[5][0];
                    $debito = $MovimentacaoService->converterParaFloat($matches[6][0]);
                    $credito = 0.00;
                }

                if (preg_match_all('/(^\d{4})\/(\d{2})\s+(\d{5}-\d)\s+([a-zA-Z\s]+)\s+([[:alnum:]]+)\s+([a-zA-Z0-9]{3})\s+([a-zA-Z\s]+)\s+(\d{1,3}(?:\.\d{3})*,\d{2})\s(\d{2})/', $linha, $matches)) {
                    $coop = $matches[1][0];
                    $agencia = $matches[2][0];
                    $conta = $matches[3][0];
                    $nome = $matches[4][0];
                    $doc = $matches[5][0];
                    $cod = $matches[6][0];
                    $descricao = $matches[7][0];
                    $credito = $MovimentacaoService->converterParaFloat($matches[8][0]);
                    $debito = 0.00;
                }

                if (preg_match_all('/\s+(\d{5}-\d)\s+([a-zA-Z\s]+)\s+([[:alnum:]]+)\s+([a-zA-Z0-9]{3})\s+([a-zA-Z\s]+)\s+(\d{1,3}(?:\.\d{3})*,\d{2})\s(\d{2})/', $linha, $matches)) {
                    $conta = $matches[1][0];
                    $nome = $matches[2][0];
                    $doc = $matches[3][0];
                    $cod = $matches[4][0];
                    $descricao = $matches[5][0];
                    $credito = $MovimentacaoService->converterParaFloat($matches[6][0]);
                    $debito = 0.00;
                }

                if (preg_match('/(\d{2})\/(\d{2})\/(\d{4})\s(\d{2}):(\d{2})/', $linha, $matches)) {
                    $dia = $matches[1];
                    $mes = $matches[2];
                    $ano = $matches[3];
                    $hora = $matches[4];
                    $minuto = $matches[5];
                    $dataHora = Carbon::createFromFormat('d/m/Y H:i', "$dia/$mes/$ano $hora:$minuto");

                    if ($coop && $agencia && $conta && $nome && $doc) {
                        $movimentacao = new Movimentacao([
                            'cooperativa' => $coop,
                            'agencia' => $agencia,
                            'conta' => $conta,
                            'nome' => $nome,
                            'documento' => $doc,
                            'codigo' => $cod,
                            'descricao' => $descricao,
                            'debito' => $debito,
                            'credito' => $credito,
                            'data_hora' => $dataHora,
                        ]);
                        $movimentacao->save();
                    } else {
                        Log::error("Dados nulos " . json_encode(compact('coop', 'agencia', 'conta', 'nome', 'doc','debito','credito')));
                    }
                }
            }
            $fimcarga = now();
            $tempocarga = $inicioCarga->diffInSeconds($fimcarga);
            $logExtracao = new ModelsLog([
                'acao'=>'CARGA',
                'detalhes'=>'Fim da operacao de carga e extracao de arquivo. Tempo total de carga:' . $tempocarga.' segundos.'
            ]);
            $logExtracao->save();
            $dataMaisMovimentacao = $MovimentacaoService->getDataComMaiorQuantidadeDeMovimentacoes();
            Log::error('Data com maior movimentação: ' . json_encode($dataMaisMovimentacao));
            Log::error('Data com menor movimentação: ' . json_encode($MovimentacaoService->getDataComMenorQuantidadeDeMovimentacoes()));
            Log::error('Data com menor soma de  movimentação: ' . json_encode($MovimentacaoService->getDataComMenorSomaDeMovimentacoes()));
            Log::error('Data com maior soma de  movimentação: ' . json_encode($MovimentacaoService->getDataComMaiorSomaDeMovimentacoes()));
            Log::error('Data com maior PX1: ' . json_encode($MovimentacaoService->getDiaComMaisMovimentacoesPX1()));
            Log::error('Data com maior RX1: ' . json_encode($MovimentacaoService->getDiaComMaisMovimentacoesRX1()));
            Log::error('Data com maior X18: ' . json_encode($MovimentacaoService->getDiaComMaisMovimentacoesX18()));
            Log::error('QTD POR AGENC ' . json_encode($MovimentacaoService->getQuantidadeMovimentacoesPorCoopAgencia()));
            Log::error('SOMA POR AGENC ' . json_encode($MovimentacaoService->getSomaValoresMovimentacoesPorCoopAgencia()));
            Log::error('REL SOMA/QTD POR AGENC ' . json_encode($MovimentacaoService->getRelacaoSomaValoresQuantidadePorCoopAgencia()));
            Log::error('REL CRE DEB HORA ' . json_encode($MovimentacaoService->getRelacaoCreditosDebitosPorHora()));
            


            return response()->json(['success' => true, 'msg' => "Upload completo"], 200)
            ->header('Access-Control-Allow-Origin', 'http://localhost:5173')
            ->header('Access-Control-Allow-Methods', 'POST, GET, OPTIONS, PUT, DELETE')
            ->header('Access-Control-Allow-Headers', 'Content-Type, X-Auth-Token, Authorization');
        } catch (\Exception $e) {
            Log::error('Erro ao fazer upload do arquivo: ' . $e->getMessage());

            return response()->json(['success' => false, 'msg' => $e->getMessage()], 400); 
            
        }
    }

    public function dashboard(){
        try {
            $MovimentacaoService = new MovimentacaoService();
            $dataMaisMovimentacao = $MovimentacaoService->getDataComMaiorQuantidadeDeMovimentacoes();
            $dataMaiorSomaMovimentacao = $MovimentacaoService->getDataComMaiorSomaDeMovimentacoes();
            $dataMenorSomaMovimentacao = $MovimentacaoService->getDataComMenorSomaDeMovimentacoes();
            $dataMenosMovimentacao = $MovimentacaoService->getDataComMenorQuantidadeDeMovimentacoes();
            $diaMaisRX1 = $MovimentacaoService->getDiaComMaisMovimentacoesRX1();
            $diaMaisPX1 = $MovimentacaoService->getDiaComMaisMovimentacoesPX1();
            $diaMaisX18= $MovimentacaoService->getDiaComMaisMovimentacoesX18();
            $AgenciaMaiorMovimentacao = $MovimentacaoService->getQuantidadeMovimentacoesPorCoopAgencia();
            $AgenciaSomaMovimentacao = $MovimentacaoService->getSomaValoresMovimentacoesPorCoopAgencia();
            $RelacaoSomaQuantidade = $MovimentacaoService->getRelacaoSomaValoresQuantidadePorCoopAgencia();
            $CreditoDebitoPorHora = $MovimentacaoService->getRelacaoCreditosDebitosPorHora();

            return response()->json(['success' => true,
                'dataMaisMovimentacao' => $dataMaisMovimentacao,
                'dataMaiorSomaMovimentacao' => $dataMaiorSomaMovimentacao,
                'dataMenorSomaMovimentacao' => $dataMenorSomaMovimentacao,
                'dataMenosMovimentacao' => $dataMenosMovimentacao,
                'diaMaisRX1' => $diaMaisRX1,
                'diaMaisPX1' => $diaMaisPX1,
                'diaMaisX18' => $diaMaisX18,
                'AgenciaMaiorMovimentacao' => $AgenciaMaiorMovimentacao,
                'AgenciaSomaMovimentacao' => $AgenciaSomaMovimentacao,
                'RelacaoSomaQuantidade' => $RelacaoSomaQuantidade,
                'CreditoDebitoPorHora' => $CreditoDebitoPorHora,
            ]);

        } catch (\Throwable $e) {
            Log::error('Erro no Dashboard: ' . $e->getMessage());

            return response()->json(['success' => false, 'msg' => $e->getMessage()]);
        }
    }

    

}
