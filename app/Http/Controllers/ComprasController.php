<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Http;





class ComprasController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
{
    $compras = [];
    $respCompras = Http::get('http://localhost:3000/compras/MostrarCompra/0/compras');
        if ($respCompras->successful()) {
            $compras = collect($respCompras->json())->sortByDesc('idCompra')->values()->all();
        }
    $proveedores = [];
    $productos = [];

    $respCompras = Http::get('http://localhost:3000/compras/MostrarCompra/0/compras');
    $respProveedores = Http::get('http://localhost:3000/proveedores/MostrarProveedor/0');
    $respProductos = Http::get('http://localhost:3000/productos/MostrarProducto/0');

    if ($respCompras->successful()) $compras = $respCompras->json();
    if ($respProveedores->successful()) $proveedores = $respProveedores->json();
    if ($respProductos->successful()) $productos = $respProductos->json();

    return view('Compras.compras', compact('compras', 'proveedores', 'productos'));
}

    //public function index()
    //{
        //return view('Compras.compras');
    //}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */

public function store(Request $request)
{
    $data = [
        'idProveedor' => $request->input('idProveedor'),
        'fechaCompra' => $request->input('fechaCompra'),
        'detallesCompra' => $request->input('productos')
    ];

    $response = Http::post('http://localhost:3000/compras/RegistrarCompra', $data);

    if ($response->successful()) {
        return redirect()->route('compras')->with('success', 'Compra registrada con éxito.');
    } else {
        return redirect()->route('compras')->with('error', 'Error al registrar la compra.');
    }
}


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */


     public function destroy($id)
     {
         $response = Http::delete("http://localhost:3000/compras/EliminarCompra/$id");
     
         if ($response->successful()) {
             return redirect()->route('compras')->with('success', 'Compra eliminada correctamente.');
         } else {
             return redirect()->route('compras')->with('error', 'No se pudo eliminar la compra.');
         }
     }

}
