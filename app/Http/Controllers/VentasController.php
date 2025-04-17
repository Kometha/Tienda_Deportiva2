<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class VentasController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $ventas = [];
        $clientes = [];
        $productos = [];

        // Llamadas a las APIs para obtener los datos
        $respVentas = Http::get('http://localhost:3000/ventas/MostrarVentas/0/ventas');
        $respClientes = Http::get('http://localhost:3000/selects/clientes');
        $respProductos = Http::get('http://localhost:3000/selects/productos');

        if ($respVentas->successful()) {
            $ventas = $respVentas->json();
        }

        if ($respClientes->successful()) {
            $clientes = $respClientes->json();
        }

        if ($respProductos->successful()) {
            $productos = $respProductos->json();
        }

        return view('Ventas.ventas', compact('ventas', 'clientes', 'productos'));
    }

    public function store(Request $request)
{
    // Validar los datos
    $request->validate([
        'idCliente' => 'required|numeric',
        'fechaVenta' => 'required|date',
        'productos' => 'required|array',
        'productos.*.idProducto' => 'required|numeric',
        'productos.*.cantidad' => 'required|numeric|min:1',
    ]);

    // Armar los datos para la API
    $detalles = [];
    foreach ($request->productos as $producto) {
        $detalles[] = [
            'idProducto' => $producto['idProducto'],
            'cantidad' => $producto['cantidad']
        ];
    }

    $data = [
        'idCliente' => $request->idCliente,
        'fechaVenta' => $request->fechaVenta,
        'detallesVenta' => $detalles
    ];

    // Enviar a la API Node.js
    $response = Http::post('http://localhost:3000/ventas/RegistrarVenta', $data);

    if ($response->successful()) {
        return redirect()->route('ventas')->with('success', 'Venta registrada correctamente');
    } else {
        return redirect()->route('ventas')->with('error', 'Error al registrar la venta');
    }
}

}
