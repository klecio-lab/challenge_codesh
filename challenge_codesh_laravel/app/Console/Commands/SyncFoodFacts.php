<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class SyncFoodFacts extends Command
{

    protected $signature = 'app:sync-food-facts';

    protected $description = 'Sincroniza os arquivos JSON de Food Facts';

    protected $filesUrl = "https://challenges.coode.sh/food/data/json/index.txt";

    protected $urlJsonFoodFacts = "https://challenges.coode.sh/food/data/json/";

    public function handle()
    {
        $fileNames = explode("\n", trim(Http::get($this->filesUrl)->body()));

        foreach ($fileNames as $key => $fileName) {
            $compressedJsonFoodFacts = file_get_contents($this->urlJsonFoodFacts . $fileName);
            $jsonFoodFactData = gzdecode($compressedJsonFoodFacts);
            dd($jsonFoodFactData);
        }
    }
}
