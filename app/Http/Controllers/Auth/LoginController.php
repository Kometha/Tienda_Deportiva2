<?php

namespace App\Http\Controllers\Auth;


namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;



class LoginController extends Controller
{
    public function authenticate(Request $request)
    {
        $usuario = $request->input('usuario');
        $clave = $request->input('clave');

        $response = Http::post('http://localhost:3000/auth/login', [
            'usuario' => $usuario,
            'clave' => $clave
        ]);

        if ($response->successful()) {
            $data = $response->json();

            Session::put('usuario', $data['usuario']['usuario']);

            return redirect('/home')->with('status', 'Inicio de sesión exitoso.');
        } elseif ($response->status() === 401) {
            return back()->withErrors(['usuario' => 'Credenciales incorrectas.'])->withInput();
        } else {
            return back()->withErrors(['usuario' => 'Error en el servidor de autenticación.'])->withInput();
        }
    }


    public function showLoginForm()
{
    return view('home');
}

public function logout(Request $request)
{
    Session::forget('usuario');
    Session::flash('status', 'Sesión cerrada correctamente.');
    return redirect('/');
}

}
