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
        $clientes = Usuario::orderBy('cad_usuario_id')->get();

        return view('clientes.index', compact('clientes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('cad_usuarios', 'cad_usuario_email')
            ],
            'senha' => 'required|string|min:4|max:255',
            'tipo_login' => 'required|string|max:50',
        ]);

        Usuario::create([
            'cad_usuario_nome' => $request->nome,
            'cad_usuario_email' => $request->email,
            'cad_usuario_senha' => Hash::make($request->senha),
            'cad_usuario_usu_cad' => now(),
            'cad_usuario_tipo_login' => $request->tipo_login,
        ]);

        return redirect()
            ->route('clientes.index')
            ->with('sucesso', 'Usuário cadastrado com sucesso.');
    }
}