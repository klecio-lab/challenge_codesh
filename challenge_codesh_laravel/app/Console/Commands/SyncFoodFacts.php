<?php

namespace App\Console\Commands;

use App\Jobs\ProcessFoodFactsByChunkJob;
use App\Models\FoodFact;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
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

                $dataBatch = [];

                foreach ($linesChunk as $line) {

                    $jsonData = json_decode($line, true);

                    // Filtra e estrutura os dados que você deseja salvar
                    // $dataBatch[] = [
                    $dataBatch = [
                        'code' => (string)ltrim($jsonData['code'], '"') ?? null,
                        'status' => 'draft', // ou defina conforme a lógica do seu sistema
                        'imported_t' => now(),
                        'url' => $jsonData['url'] ?? null,
                        'creator' => $jsonData['creator'] ?? null,
                        'created_t' => $jsonData['created_t'] ?? null,
                        'last_modified_t' => $jsonData['last_modified_t'] ?? null,
                        'product_name' => $jsonData['product_name'] ?? null,
                        'quantity' => $jsonData['quantity'] ?? null,
                        'brands' => $jsonData['brands'] ?? null,
                        'categories' => $jsonData['categories'] ?? null,
                        'labels' => $jsonData['labels'] ?? null,
                        'cities' => $jsonData['cities'] ?? null,
                        'purchase_places' => $jsonData['purchase_places'] ?? null,
                        'stores' => $jsonData['stores'] ?? null,
                        'ingredients_text' => $jsonData['ingredients_text'] ?? null,
                        'traces' => $jsonData['traces'] ?? null,
                        'serving_size' => $jsonData['serving_size'] ?? null,
                        'serving_quantity' => (float)$jsonData['serving_quantity'] ?? null,
                        'nutriscore_score' => (integer)$jsonData['nutriscore_score'] ?? null,
                        'nutriscore_grade' => $jsonData['nutriscore_grade'] ?? null,
                        'main_category' => $jsonData['main_category'] ?? null,
                        'image_url' => $jsonData['image_url'] ?? null,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];

                    try {
                        FoodFact::updateOrCreate([
                            'code' => $dataBatch['code']
                        ], $dataBatch);

                        // FoodFact::insert($dataBatch);
                        Log::info("code: ",  [$dataBatch['code']]);

                    } catch (\Exception $e) {
                        Log::channel('error-sync-food-facts')->debug("erro ao sincronizar:\n", [
                            'message' => $e->getMessage(),
                            'dataBatch' => $dataBatch
                        ]);
                    }
                }


            // Despacha um job para processar este batch
            // ProcessFoodFactsByChunkJob::dispatch($linesChunk);
        });

        Cache::put('last_cron_run', now());
    }

}
