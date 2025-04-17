<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CategoriasController extends Controller
{
    public function index(Request $request)
    {
        // CREAR categoría
        if ($request->isMethod('post')) {
            $request->validate([
                'nombreCategoria' => 'required|string|max:255',
            ]);

            Http::post('http://localhost:3000/categorias', [
                'nombreCategoria' => $request->nombreCategoria,
            ]);

            return redirect('/categorias');
        }

        // ACTUALIZAR categoría
        if ($request->isMethod('put')) {
            $request->validate([
                'idCategoria' => 'required|integer',
                'nombreCategoria' => 'required|string|max:255',
            ]);

            Http::put("http://localhost:3000/categorias/{$request->idCategoria}", [
                'nombreCategoria' => $request->nombreCategoria,
            ]);

            return redirect('/categorias');
        }

        // ELIMINAR categoría
        if ($request->isMethod('delete')) {
            $id = $request->input('idCategoria');
            Http::delete("http://localhost:3000/categorias/{$id}");
            return redirect('/categorias');
        }

        // MOSTRAR categorías
        $response = Http::get('http://localhost:3000/categorias');

        if ($response->successful()) {
            $categorias = $response->json();
            return view('almacen.categorias', compact('categorias'));
        } else {
            return view('almacen.categorias')->with('error', 'No se pudieron obtener las categorías.');
        }
    }
}
