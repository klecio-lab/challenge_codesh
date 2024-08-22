<?php

namespace App\Http\Controllers;

use App\Models\FoodFact;
use Illuminate\Http\Request;
use App\Http\Requests\FoodFact\UpdateRequest;

class FoodFactController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->input("per_page",10);
        $products = FoodFact::paginate($perPage); // Paginação de 10 itens por página
        return response()->json(['success' => true, 'data' => $products], 200);
    }

    public function show($code)
    {
        $foodFact = FoodFact::where('code', $code)->first();

        if (!isset($foodFact->id)) {
            return response()->json(['success' => false, 'message' => 'food fact não encontrado'], 404);
        }

        return response()->json(['success' => true, 'data' => $foodFact], 200);
    }

    public function update(UpdateRequest $request, $code)
    {
        $data = $request->validated();

        $foodFact = FoodFact::where('code', $code)->first();

        if (!isset($foodFact->id)) {
            return response()->json(['success' => false, 'message' => 'food fact não encontrado'], 404);
        }

        $updateResult = $foodFact->update($data);

        return response()->json(['success' => $updateResult], 200);
    }

    public function destroy($code)
    {
        $foodFact = FoodFact::where('code', $code)->first();

        if (!isset($foodFact->id)) {
            return response()->json(['success' => false, 'message' => 'food fact não encontrado'], 404);
        }

        $updateResult = $foodFact->update([
            'status' => 'trash'
        ]);

        return response()->json(['success' => $updateResult], 200);
    }
}
