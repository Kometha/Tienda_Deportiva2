<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;  // Usamos la fachada HTTP para hacer las peticiones
use Illuminate\Support\Facades\Storage;

class ProductosController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    protected $baseUrl;
    public function __construct()
    {
        $this->baseUrl = 'http://localhost:3000/productos/';
        $this->middleware('auth');
    }
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // Obtener productos
        $response = Http::get($this->baseUrl . 'MostrarProducto/0');
    
        // Obtener selects
        $categoriasResponse = Http::get('http://localhost:3000/otros/selects/categorias');
        $marcasResponse = Http::get('http://localhost:3000/otros/selects/marcas');
        $proveedoresResponse = Http::get('http://localhost:3000/otros/selects/proveedores');
    
        if ($response->successful() && $categoriasResponse->successful() && $marcasResponse->successful() && $proveedoresResponse->successful()) {
            $productos = $response->json();
            $categorias = $categoriasResponse->json();
            $marcas = $marcasResponse->json();
            $proveedores = $proveedoresResponse->json();
    
            return view('Almacen.productos', compact('productos', 'categorias', 'marcas', 'proveedores'));
        } else {
            return view('Almacen.productos', [
                'productos' => [],
                'categorias' => [],
                'marcas' => [],
                'proveedores' => [],
            ])->withErrors('Error al obtener los datos desde la API.');
        }
    }
    
    
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
    // Validación del formulario
    $request->validate([
        'nombreProducto' => 'required|string|max:255',
        'descripcion' => 'nullable|string|max:255',
        'idProveedor' => 'required|integer', // Cambiado a minúsculas
        'idCategoria' => 'required|integer', // Cambiado a minúsculas
        'idMarca' => 'required|integer', // Cambiado para coincidir con vista
        'precioCompra' => 'required|numeric',
        'precioVenta' => 'required|numeric',
        'stockInicial' => 'required|integer', // Cambiado para coincidir con vista
        'bajoStock' => 'required|integer',
        'imagen' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
    ]);

    // Guardar la imagen
    $imagenPath = '/default.jpg';
    if ($request->hasFile('imagen')) {
        try {
            $imagen = $request->file('imagen');
            $nombreArchivo = time().'_'.$imagen->getClientOriginalName();
            $ruta = $imagen->storeAs('public/imagenes', $nombreArchivo);
            $imagenPath = Storage::url($ruta);
        } catch (\Exception $e) {
            \Log::error('Error al guardar imagen: '.$e->getMessage());
        }
    }

    // Preparar datos para el API
    $data = [
        "nombreProducto" => $request->nombreProducto,
        "descripcion" => $request->descripcion,
        "idProveedor" => $request->idProveedor, // Cambiado a minúsculas
        "idCategoria" => $request->idCategoria, // Cambiado a minúsculas
        "idMarca" => $request->idMarca, // Cambiado para coincidir con vista
        "precioCompra" => $request->precioCompra,
        "precioVenta" => $request->precioVenta,
        "stockInicial" => $request->stockInicial, // Cambiado para coincidir con vista
        "bajoStock" => $request->bajoStock,
        "imagen" => $imagenPath,
    ];

    //\Log::info('Datos enviados al API:', $data);

    // Enviar POST a la API Node (corregir la URL según tu endpoint real)
    $response = Http::post('http://localhost:3000/productos/RegistrarProducto', $data);

    if ($response->successful()) {
        return redirect()->route('productos.index')->with('success', 'Producto registrado correctamente.');
    } else {
        $error = $response->json()['message'] ?? 'Error al registrar el producto.';
        \Log::error('Error en API:', ['response' => $response->json()]);
        return redirect()->back()->with('error', $error)->withInput();
    }
    }






    

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {

       // Realizamos la petición GET para obtener el producto
       $response = Http::get("http://localhost:3000/productos/MostrarProducto/{$id}");

       // Verificamos si la petición fue exitosa
        if ($response->successful()) {
            $producto = $response->json();
        
            return view('Almacen.productos',compact('producto'));
        } else {
           return redirect()->route('productos.index')->with('error', 'Error al mostrar el producto');
        }
    }

    

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
{
    // Validación
    $request->validate([
        'nombreProducto' => 'required|string|max:255',
        'descripcion' => 'nullable|string|max:255',
        'idProveedor' => 'required|integer',
        'idCategoria' => 'required|integer',
        'idMarca' => 'required|integer',
        'precioCompra' => 'required|numeric',
        'precioVenta' => 'required|numeric',
        'stock' => 'required|integer',
        'bajoStock' => 'required|integer',
        'imagen' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
    ]);

    // Imagen actual por defecto
    $imagenPath = $request->input('imagen_actual', '/default.jpg');

    // Si se subió nueva imagen
    if ($request->hasFile('imagen')) {
        try {
            $imagen = $request->file('imagen');
            $nombreArchivo = time() . '_' . $imagen->getClientOriginalName();
            $ruta = $imagen->storeAs('public/imagenes', $nombreArchivo);
            $imagenPath = Storage::url($ruta);
        } catch (\Exception $e) {
            \Log::error('Error al guardar imagen: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error al subir la imagen.');
        }
    }

    // Datos que espera la API
    $data = [
        "nombreProducto" => $request->nombreProducto,
        "descripcion" => $request->descripcion,
        "idProveedor" => $request->idProveedor,
        "idCategoria" => $request->idCategoria,
        "idMarca" => $request->idMarca,
        "precioCompra" => $request->precioCompra,
        "precioVenta" => $request->precioVenta,
        "stock" => $request->stock,
        "bajoStock" => $request->bajoStock,
        "imagen" => $imagenPath,
    ];

    // Enviar PUT a la API con el ID en la URL
    $response = Http::put("http://localhost:3000/productos/ActualizarProducto/$id", $data);

    if ($response->successful()) {
        return redirect()->route('productos.index')->with('success', 'Producto actualizado correctamente.');
    } else {
        \Log::error('Error en API al actualizar:', [
            'status' => $response->status(),
            'body' => $response->body(),
            'data_enviada' => $data
        ]);

        $error = $response->json()['message'] ?? 'Error al actualizar el producto.';
        return redirect()->back()->with('error', $error)->withInput();
    }
}

    



    public function destroy(string $id)
    {
        // Realizamos la petición DELET para actualizar el producto
        $response = Http::delete("http://localhost:3000/productos/EliminarProducto/{$id}");
         
        // Verificamos si la petición fue exitosa
        if ($response->successful()) {
            return redirect()->route('productos.index')->with('success', 'Producto eliminado correctamente');
        } else {
            return redirect()->route('productos.index')->with('error', 'Error al eliminar el producto');
        }
    }
}