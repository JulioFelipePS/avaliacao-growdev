<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessarMovimentacaoJob;
use App\Models\Movimentacao;
use App\Services\MovimentacaoService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class MovimentacaoController extends Controller
{
    public function upload(Request $request)
    {
        try {
            $request->validate([
                'arquivo' => 'required|file|max:2097152',
            ]);

            $MovimentacaoService = new MovimentacaoService();

            $filePath = $request->file('arquivo')->store('uploads');
            $conteudoArquivo = Storage::get($filePath);
            $linhas = explode("\n", $conteudoArquivo);
            $coop = $agencia = $conta = $nome = $doc = $cod = $descricao = null;
            $debito = 0.00;
            $credito = 0.00;
            $dataHora = null;

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
                    Log::info($linha);
                    Log::error('AQUI2' . json_encode(compact('coop', 'agencia', 'conta', 'nome', 'doc','cod','debito','credito')));
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
                        Log::error("Dados salvos para movimentação: " . json_encode(compact('coop', 'agencia', 'conta', 'nome', 'doc','debito','credito')));
                    } else {
                        Log::error("Dados nulos " . json_encode(compact('coop', 'agencia', 'conta', 'nome', 'doc','debito','credito')));
                    }
                }
            }

            return response()->json(['success' => true, 'msg' => "Upload completo"], 200);
        } catch (\Exception $e) {
            Log::error('Erro ao fazer upload do arquivo: ' . $e->getMessage());

            return response()->json(['success' => false, 'msg' => $e->getMessage()], 400);
        }
    }

    

}
