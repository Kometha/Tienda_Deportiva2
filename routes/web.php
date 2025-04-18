<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReportesController;
use App\Http\Controllers\ProductosController;
use App\Http\Controllers\Auth\LoginController;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/empleados', [App\Http\Controllers\EmpleadosController::class, 'index'])->name('empleados');
Route::get('/usuarios', [App\Http\Controllers\UsuariosController::class, 'index'])->name('usuarios');
Route::resource('productos', ProductosController::class);
Route::get('/categorias', [App\Http\Controllers\categoriasController::class, 'index'])->name('categorias');
Route::get('/marcas', [App\Http\Controllers\MarcasController::class, 'index'])->name('marcas');
Route::get('/compras', [App\Http\Controllers\ComprasController::class, 'index'])->name('compras');
Route::get('/proveedores', [App\Http\Controllers\ProveedoresController::class, 'index'])->name('proveedores');
Route::get('/ventas', [App\Http\Controllers\VentasController::class, 'index'])->name('ventas');
Route::get('/clientes', [App\Http\Controllers\ClientesController::class, 'index'])->name('clientes');
Route::post('/auth/login', [LoginController::class, 'authenticate'])->name('auth.login');
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
//Route::resource('compras', 'ComprasController');


Route::resource('reportes', ReportesController::class);



Route::get('Reportes/download', [ReportesController::class, 'download'])->name('Reportes.download');

use App\Http\Controllers\MarcasController;

Route::match(['get', 'post', 'put', 'delete'], '/marcas', [MarcasController::class, 'index']);

use App\Http\Controllers\CategoriasController;

Route::match(['get', 'post', 'put', 'delete'], '/categorias', [CategoriasController::class, 'index']);

use App\Http\Controllers\ProveedoresController;

Route::match(['get', 'post', 'put', 'delete'], '/proveedores', [ProveedoresController::class, 'index']);

//Nuevas Rutas Compras
use App\Http\Controllers\ComprasController;

Route::delete('/compras/{id}', [ComprasController::class, 'destroy'])->name('compras.destroy');

Route::post('/compras', [ComprasController::class, 'store'])->name('compras.store');

Route::delete('/compras/{id}', [App\Http\Controllers\ComprasController::class, 'destroy'])->name('compras.destroy');

// VENTAS
use App\Http\Controllers\VentasController;

Route::get('/ventas', [App\Http\Controllers\VentasController::class, 'index'])->name('ventas');
Route::post('/ventas', [App\Http\Controllers\VentasController::class, 'store'])->name('ventas.store');




