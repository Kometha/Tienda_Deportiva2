<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class MarcasController extends Controller
{
    public function index(Request $request)
    {
        // CREAR marca
        if ($request->isMethod('post')) {
            $request->validate([
                'nombreMarca' => 'required|string|max:255',
            ]);

            Http::post('http://localhost:3000/marcas', [
                'nombreMarca' => $request->nombreMarca,
            ]);

            return redirect('/marcas');
        }

        // ACTUALIZAR marca (PUT)
        if ($request->isMethod('put')) {
            $request->validate([
                'idMarca' => 'required|integer',
                'nombreMarca' => 'required|string|max:255',
            ]);

            Http::put("http://localhost:3000/marcas/{$request->idMarca}", [
                'nombreMarca' => $request->nombreMarca,
            ]);

            return redirect('/marcas');
        }

        // ELIMINAR marca
        if ($request->isMethod('delete')) {
            $id = $request->input('idMarca');
            Http::delete("http://localhost:3000/marcas/{$id}");
            return redirect('/marcas');
        }

        // MOSTRAR marcas (GET)
        $response = Http::get('http://localhost:3000/marcas');

        if ($response->successful()) {
            $marcas = $response->json();
            return view('almacen.marcas', compact('marcas'));
        } else {
            return view('almacen.marcas')->with('error', 'No se pudieron obtener las marcas.');
        }
    }
}
