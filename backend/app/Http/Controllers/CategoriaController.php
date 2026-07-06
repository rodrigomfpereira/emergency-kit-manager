<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Http\Request;

class CategoriaController extends Controller
{
    // LISTAR (SELECT * WHERE user_id)
    public function index()
    {
        $id_do_utilizador = auth()->id(); // Descobre quem está logado
        
        $categorias = Categoria::where('user_id', $id_do_utilizador)
                           ->with('items')
                           ->get();
        
        return response()->json($categorias);
    }

    // CRIAR (INSERT INTO)
    public function store(Request $request)
    {
        $categoria = new Categoria();
        $categoria->nome = $request->nome; // Pega no nome que veio do ecrã
        $categoria->user_id = auth()->id(); // Diz de quem é a categoria
        $categoria->save(); // Grava na tabela

        return response()->json($categoria);
    }

    // MOSTRAR APENAS UMA (SELECT * WHERE id AND user_id LIMIT 1)
    public function show($id)
    {
        $categoria = Categoria::where('id', $id)->where('user_id', auth()->id())->first();
        
        if (!$categoria) {
            return response()->json(['message' => 'Não existe'], 404);
        }
        
        return response()->json($categoria);
    }

    // EDITAR (UPDATE SET nome WHERE id AND user_id)
    public function update(Request $request, $id)
    {
        $categoria = Categoria::where('id', $id)->where('user_id', auth()->id())->first();
        
        if (!$categoria) {
            return response()->json(['message' => 'Não existe'], 404);
        }

        $categoria->nome = $request->nome; // Muda o nome para o novo nome
        $categoria->save(); // Atualiza no MySQL

        return response()->json($categoria);
    }

    // APAGAR (DELETE WHERE id AND user_id)
    public function destroy($id)
    {
        $categoria = Categoria::where('id', $id)->where('user_id', auth()->id())->first();
        
        if (!$categoria) {
            return response()->json(['message' => 'Não existe'], 404);
        }

        $categoria->delete(); // Apaga a linha da tabela
        
        return response()->json(['message' => 'Eliminado']);
    }
}