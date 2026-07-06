<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    // LISTAR (SELECT * WHERE user_id)
    public function index()
    {
        $id_do_utilizador = auth()->id(); // Descobre quem está logado
        
        $items = Item::where('user_id', $id_do_utilizador)
                 ->with(['categoria', 'kit']) 
                 ->get();
        
        return response()->json($items);
    }

    // CRIAR (INSERT INTO)
    public function store(Request $request)
    {
        $item = new Item();
        $item->nome = $request->nome; 
        $item->validade = $request->validade; 
        $item->comprado = $request->comprado ?? false; 
        $item->categoria_id = $request->categoria_id; 
        $item->kit_id = $request->kit_id; 
        $item->user_id = auth()->id(); 
        $item->save(); 

        return response()->json($item);
    }

    // MOSTRAR APENAS UM (SELECT * WHERE id AND user_id LIMIT 1)
    public function show($id)
    {
        $item = Item::where('id', $id)->where('user_id', auth()->id())->first();
        
        if (!$item) {
            return response()->json(['message' => 'Não existe'], 404);
        }
        
        return response()->json($item);
    }

    // EDITAR (UPDATE SET nome, validade, comprado, chaves forasteiras WHERE id AND user_id)
    public function update(Request $request, $id)
    {
        $item = Item::where('id', $id)->where('user_id', auth()->id())->first();
        
        if (!$item) {
            return response()->json(['message' => 'Não existe'], 404);
        }

        $item->nome = $request->nome; 
        $item->validade = $request->validade; 
        $item->comprado = $request->comprado; 
        $item->categoria_id = $request->categoria_id; 
        $item->kit_id = $request->kit_id; 
        $item->save(); // Atualiza no MySQL

        return response()->json($item);
    }

    // APAGAR (DELETE WHERE id AND user_id)
    public function destroy($id)
    {
        $item = Item::where('id', $id)->where('user_id', auth()->id())->first();
        
        if (!$item) {
            return response()->json(['message' => 'Não existe'], 404);
        }

        $item->delete(); // Apaga a linha da tabela
        
        return response()->json(['message' => 'Eliminado']);
    }
}