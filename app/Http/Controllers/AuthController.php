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

        $usuario = Usuario::where(
            'cad_usuario_email',
            $request->email
        )->first();

        if (!$usuario) {
            return redirect()
                ->route('login')
                ->with('erro_login', 'Usuário não encontrado.');
        }

        $senhaInformada = $request->senha;
        $senhaBanco = $usuario->cad_usuario_senha;

        $senhaValida = Hash::check($senhaInformada, $senhaBanco)
            || $senhaInformada === $senhaBanco;

        if (!$senhaValida) {
            return redirect()
                ->route('login')
                ->with('erro_login', 'Senha incorreta.');
        }

        // Caso a senha esteja salva em texto puro, converte para hash
        if ($senhaInformada === $senhaBanco) {
            $usuario->cad_usuario_senha = Hash::make($senhaInformada);
            $usuario->save();
        }

        $request->session()->put(
            'usuario_id',
            $usuario->cad_usuario_id
        );

        $request->session()->put(
            'usuario_nome',
            $usuario->cad_usuario_nome
        );

        $request->session()->put(
            'tipo_login',
            $usuario->cad_usuario_tipo_login ?? 'Cliente'
        );

        return redirect()->route('index.informacoes');
    }

    public function logout(Request $request)
    {
        $request->session()->flush();

        return redirect()->route('login');
    }
}