<?php

namespace App\Http\Controllers;

use App\Models\Kit;
use Illuminate\Http\Request;

class KitController extends Controller
{
    // LISTAR (SELECT * WHERE user_id)
    public function index()
    {
        $id_do_utilizador = auth()->id(); // Descobre quem está logado
        
        $kits = Kit::where('user_id', $id_do_utilizador)
               ->with('items')
               ->get();
        
        return response()->json($kits);
    }

    // CRIAR (INSERT INTO)
    public function store(Request $request)
    {
        $kit = new Kit();
        $kit->nome = $request->nome; 
        $kit->localizacao = $request->localizacao; 
        $kit->user_id = auth()->id(); // Diz de quem é o kit
        $kit->save(); 
        
        if ($request->has('items_ids') && is_array($request->items_ids)) {
            \App\Models\Item::whereIn('id', $request->items_ids)->update(['kit_id' => $kit->id]);
        }
        return response()->json($kit);
    }

    // MOSTRAR APENAS UM (SELECT * WHERE id AND user_id LIMIT 1)
    public function show($id)
{
    // Adicionamos o ->with('items') aqui:
    $kit = Kit::where('id', $id)->where('user_id', auth()->id())->with('items')->first();
    
    if (!$kit) {
        return response()->json(['message' => 'Não existe'], 404);
    }
    
    return response()->json($kit);
}

    // EDITAR (UPDATE SET nome, localizacao WHERE id AND user_id)
    public function update(Request $request, $id)
{
    $kit = Kit::where('id', $id)->where('user_id', auth()->id())->first();
    
    if (!$kit) {
        return response()->json(['message' => 'Não existe'], 404);
    }

    $kit->nome = $request->nome; 
    $kit->localizacao = $request->localizacao; 
    $kit->save(); // Atualiza no MySQL

    // limpa o kit de todos os itens associados
    \App\Models\Item::where('kit_id', $kit->id)->update(['kit_id' => null]);

    // associa apenas os itens que vieram com o check do svelte
    if ($request->has('items_ids') && is_array($request->items_ids) && count($request->items_ids) > 0) {
        \App\Models\Item::whereIn('id', $request->items_ids)->update(['kit_id' => $kit->id]);
    }

    return response()->json($kit);
}

    // APAGAR (DELETE WHERE id AND user_id)
    public function destroy($id)
    {
        $kit = Kit::where('id', $id)->where('user_id', auth()->id())->first();
        
        if (!$kit) {
            return response()->json(['message' => 'Não existe'], 404);
        }

        $kit->delete(); // Apaga a linha da tabela
        
        return response()->json(['message' => 'Eliminado']);
    }
}