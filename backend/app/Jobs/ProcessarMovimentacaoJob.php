<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Storage;

class ProcessarMovimentacaoJob implements ShouldQueue
{
    use Queueable;
    protected $filePath;
    /**
     * Create a new job instance.
     */
    public function __construct($filePath)
    {
        $this->filePath=$filePath;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $conteudoArquivo = Storage::get($this->filePath);
        $linhas = explode("\n",$conteudoArquivo);
        foreach($linhas as $linha){
            if (preg_match('/(\d+\/\d+)\s(\d{5}-\d{1})\s([A-Z\s]+)\s(\d+)\s([A-Z0-9]+)\s([A-Z\s]+)\s([0-9,\.]+)\s(\d{2})\s(\d{2}\/\d{2}\/\d{4}\s\d{2}:\d{2})/', $linha, $matches)) {

            }
        }

    }
}
