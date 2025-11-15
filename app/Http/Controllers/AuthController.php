<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ProfesorDinf;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'rut_profesor' => 'required|integer',
            'password' => 'required'
        ]);

        // Buscar profesor por RUT
        $profesor = ProfesorDinf::where('rut_profesor', $credentials['rut_profesor'])->first();

        // Verificar contraseña (sin hash por ahora)
        if ($profesor && $profesor->password === $credentials['password']) {
            // Login exitoso - usar guard personalizado
            Auth::guard('web')->login($profesor);
            $request->session()->regenerate();
            
            return redirect()->intended('/dashboard');
        }

        return back()->withErrors([
            'rut_profesor' => 'Las credenciales no son válidas.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/');
    }
}