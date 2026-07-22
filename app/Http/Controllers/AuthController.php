<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function index()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'senha' => 'required|string',
        ]);

        $usuario = Usuario::where('email', $request->email)->first();

        if (!$usuario) {
            return redirect()
                ->route('login')
                ->with('erro_login', 'Usuário não encontrado.');
        }

        $senhaInformada = $request->senha;
        $senhaBanco = $usuario->senha_hash;

        $senhaValida = Hash::check($senhaInformada, $senhaBanco)
            || $senhaInformada === $senhaBanco;

        if (!$senhaValida) {
            return redirect()
                ->route('login')
                ->with('erro_login', 'Senha incorreta.');
        }

        if ($senhaInformada === $senhaBanco) {
            $usuario->senha_hash = Hash::make($senhaInformada);
            $usuario->save();
        }

        $request->session()->put('usuario_id', $usuario->id_usuario);
        $request->session()->put('usuario_nome', $usuario->nome_usuario);
        $request->session()->put('tipo_login', $usuario->tipo_login ?? 'Cliente');

        return redirect()->route('dashboard');
    }

    public function logout(Request $request)
    {
        $request->session()->flush();

        return redirect()->route('login');
    }
}
