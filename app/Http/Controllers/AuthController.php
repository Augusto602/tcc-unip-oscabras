<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login()
    {
        return view('auth.login');
    }

    public function autenticar(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'senha' => 'required',
        ]);

        $usuario = Usuario::where(
            'email',
            $request->email
        )->first();

        if (!$usuario) {

            return back()
                ->withErrors([
                    'email' => 'Usuário não encontrado.'
                ])
                ->withInput();

        }

        $senhaInformada = $request->senha;
        $senhaBanco = $usuario->senha_hash;

        $senhaValida = false;

        if (
            !empty($senhaBanco) &&
            Hash::check($senhaInformada, $senhaBanco)
        ) {

            $senhaValida = true;

        } elseif ($senhaInformada === $senhaBanco) {

            $senhaValida = true;

            $usuario->senha_hash = Hash::make(
                $senhaInformada
            );

            $usuario->save();

        }

        if (!$senhaValida) {

            return back()
                ->withErrors([
                    'senha' => 'Senha inválida.'
                ])
                ->withInput();

        }

        session([
            'usuario_id' =>
                $usuario->id_usuario,

            'usuario_nome' =>
                $usuario->nome_usuario,

            'tipo_login' =>
                'Cliente'
        ]);

        return redirect()->route('index.informacoes');
    }

    public function logout(Request $request)
    {
        $request->session()->flush();

        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}