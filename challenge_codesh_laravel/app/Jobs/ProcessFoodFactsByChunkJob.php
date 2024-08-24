<?php

namespace App\Jobs;

use App\Models\FoodFact;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessFoodFactsByChunkJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    protected $linesChunk;

    public function __construct($linesChunk)
    {
        $this->linesChunk = $linesChunk;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::info('cheguei no job');
        $dataBatch = [];

        foreach ($this->linesChunk as $line) {
            $jsonData = json_decode($line, true);

            // Filtra e estrutura os dados que você deseja salvar
            $dataBatch[] = [
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
        }

        FoodFact::insert($dataBatch);
    }
}
