<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ProveedoresController extends Controller
{
    public function index(Request $request)
    {
        // CREAR proveedor
        if ($request->isMethod('post')) {
            $request->validate([
                'nombreProveedor' => 'required|string|max:255',
                'email' => 'required|email',
                'telefono' => 'required|string|max:15',
                'direccion' => 'required|string|max:255',
            ]);

            Http::post('http://localhost:3000/proveedores/RegistrarProveedor', [
                'nombreProveedor' => $request->nombreProveedor,
                'email' => $request->email,
                'telefono' => $request->telefono,
                'direccion' => $request->direccion,
            ]);

            return redirect('/proveedores');
        }

        // ACTUALIZAR proveedor (ahora sí incluido)
        if ($request->isMethod('put')) {
            $request->validate([
                'idProveedor' => 'required|integer',
                'nombreProveedor' => 'required|string|max:255',
                'email' => 'required|email',
                'telefono' => 'required|string|max:15',
                'direccion' => 'required|string|max:255',
            ]);

            Http::put("http://localhost:3000/proveedores/ActualizarProveedor/{$request->idProveedor}", [
                'nombreProveedor' => $request->nombreProveedor,
                'email' => $request->email,
                'telefono' => $request->telefono,
                'direccion' => $request->direccion,
            ]);

            return redirect('/proveedores');
        }

        // ELIMINAR proveedor
        if ($request->isMethod('delete')) {
            $id = $request->input('idProveedor');
            Http::delete("http://localhost:3000/proveedores/EliminarProveedor/{$id}");
            return redirect('/proveedores');
        }

        // MOSTRAR proveedores
        $response = Http::get('http://localhost:3000/proveedores/MostrarProveedor/0');

        if ($response->successful()) {
            $proveedores = $response->json();
            return view('compras.proveedores', compact('proveedores'));
        } else {
            return view('compras.proveedores')->with('error', 'No se pudieron obtener los proveedores.');
        }
    }
}
