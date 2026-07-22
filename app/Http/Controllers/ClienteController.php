<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ClienteController extends Controller
{
    public function index()
    {
        $clientes = Usuario::orderBy('id_usuario')->get();

        return view('clientes.index', compact('clientes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'email' => ['required', 'email', 'max:255', Rule::unique('usuarios', 'email')],
            'senha' => 'required|string|min:4|max:255',
            'tipo_login' => 'required|string|max:50',
        ]);

        Usuario::create([
            'nome_usuario' => $request->nome,
            'email' => $request->email,
            'senha_hash' => Hash::make($request->senha),
            'data_cadastro' => now(),
            'tipo_login' => $request->tipo_login,
        ]);

        return redirect()
            ->route('clientes.index')
            ->with('sucesso', 'Usuário cadastrado com sucesso.');
    }
}
