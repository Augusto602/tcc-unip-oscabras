<?php

namespace App\Http\Controllers;

use App\Models\CadUsuario;
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

        $usuario = CadUsuario::where('cad_usuario_email', $request->email)
            ->where('cad_usuario_ativo_sn', 'S')
            ->first();

        if (!$usuario) {
            return redirect()
                ->route('login')
                ->with('erro_login', 'Usuário não encontrado ou inativo.');
        }

        $senhaInformada = $request->senha;
        $senhaBanco = $usuario->cad_usuario_senha;

        $senhaValida = Hash::check($senhaInformada, $senhaBanco);

        /*
         * Compatibilidade com senha antiga em texto puro.
         * Seu projeto atual compara senha diretamente.
         * Esse bloco permite login antigo e já converte para Hash.
         */
        if (!$senhaValida && $senhaInformada === $senhaBanco) {
            $usuario->cad_usuario_senha = Hash::make($senhaInformada);
            $usuario->save();

            $senhaValida = true;
        }

        if (!$senhaValida) {
            return redirect()
                ->route('login')
                ->with('erro_login', 'Senha incorreta.');
        }

        $request->session()->put('usuario_id', $usuario->cad_usuario_id);
        $request->session()->put('usuario_nome', $usuario->cad_usuario_nome);
        $request->session()->put('tipo_login', $usuario->cad_usuario_tipo_login);

        return redirect()->route('dashboard');
    }

    public function logout(Request $request)
    {
        $request->session()->flush();

        return redirect()->route('login');
    }
}