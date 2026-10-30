<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AuthController extends Controller
{
    public function login()
    {
        return view('auth.login');
    }

    public function cadastro()
    {
        return view('cadastro.cadastro');
    }

    public function salvarCadastro(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:100',

            'email' => [
                'required',
                'email',
                'max:150',
                Rule::unique(
                    'usuarios',
                    'email'
                )
            ],

            'senha' =>
                'required|string|min:4|max:255|confirmed',
        ], [
            'nome.required' =>
                'Informe seu nome.',

            'email.required' =>
                'Informe seu e-mail.',

            'email.email' =>
                'Informe um e-mail válido.',

            'email.unique' =>
                'Este e-mail já está cadastrado.',

            'senha.required' =>
                'Informe uma senha.',

            'senha.min' =>
                'A senha deve possuir pelo menos 4 caracteres.',

            'senha.confirmed' =>
                'As senhas não conferem.',
        ]);

        Usuario::create([
            'nome_usuario' =>
                trim($request->nome),

            'email' =>
                trim($request->email),

            'senha_hash' =>
                Hash::make($request->senha),

            'data_cadastro' =>
                now(),
        ]);

        return redirect()
            ->route('login')
            ->with(
                'sucesso',
                'Cadastro realizado com sucesso. Faça seu login.'
            );
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
            Hash::check(
                $senhaInformada,
                $senhaBanco
            )
        ) {

            $senhaValida = true;

        } elseif (
            $senhaInformada === $senhaBanco
        ) {

            $senhaValida = true;

            $usuario->senha_hash =
                Hash::make(
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

        return redirect()
            ->route('index.informacoes');
    }

    public function logout(Request $request)
    {
        $request->session()->flush();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()
            ->route('login');
    }
}