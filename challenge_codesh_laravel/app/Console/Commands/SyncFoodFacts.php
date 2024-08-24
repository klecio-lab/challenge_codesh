<?php

namespace App\Console\Commands;

use App\Jobs\ProcessFoodFactsByChunkJob;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\LazyCollection;

class SyncFoodFacts extends Command
{

    protected $signature = 'app:sync-food-facts';

    protected $description = 'Sincroniza os arquivos JSON de Food Facts';

    protected $filesUrl = "https://challenges.coode.sh/food/data/json/index.txt";

    protected $urlJsonFoodFacts = "https://challenges.coode.sh/food/data/json/";

    public function handle()
    {
        $fileNames = explode("\n", trim(Http::timeout(30)->get($this->filesUrl)->body()));

        foreach ($fileNames as $fileName) {
            $compressedJsonFoodFacts = file_get_contents($this->urlJsonFoodFacts . $fileName);

            // Descompactar o conteúdo do GZIP
            $jsonFoodFactData = gzdecode($compressedJsonFoodFacts);

            // Salvar o JSON descompactado no storage
            $jsonFileName = pathinfo($fileName, PATHINFO_FILENAME);
            $jsonFilePath = "food-facts/uncompressed/{$jsonFileName}";
            Storage::put($jsonFilePath, $jsonFoodFactData);

            // Obter o caminho completo do arquivo salvo (opcional)
            $fileFullPath = Storage::path($jsonFilePath);

             // Libera a memória ao remover as variáveis
            unset($compressedJsonFoodFacts, $jsonFoodFactData);

            $this->dispatchFoodFactsByChunkJob($fileFullPath);
        }
    }

    public function dispatchFoodFactsByChunkJob($fileFullPath){
        LazyCollection::make(function () use($fileFullPath) {
            $file = fopen($fileFullPath, 'r');

            while ($line = fgets($file)) {
                yield $line;
            }

            fclose($file);
        })
        ->chunk(100) // define o tamanho do chunk
        ->each(function ($linesChunk) {
            // Despacha um job para processar este batch
            ProcessFoodFactsByChunkJob::dispatch($linesChunk);
            // dd($linesChunk);
        });

        Cache::put('last_cron_run', now());
    }

}
