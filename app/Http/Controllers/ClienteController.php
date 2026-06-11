<?php

namespace App\Http\Controllers;

use App\Models\CadUsuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ClienteController extends Controller
{
    public function index()
    {
        $clientes = CadUsuario::where('cad_usuario_ativo_sn', 'S')
            ->orderBy('cad_usuario_id', 'asc')
            ->get();

        return view('clientes.index', compact('clientes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:cad_usuarios,cad_usuario_email',
            'senha' => 'required|string|min:4|max:255',
            'tipo_login' => 'required|string|max:50',
        ]);

        CadUsuario::create([
            'cad_usuario_nome' => $request->nome,
            'cad_usuario_ativo_sn' => 'S',
            'cad_usuario_dt_cad' => now(),
            'cad_usuario_usu_cad' => $request->session()->get('usuario_nome'),
            'cad_usuario_tipo_login' => $request->tipo_login,
            'cad_usuario_senha' => Hash::make($request->senha),
            'cad_usuario_email' => $request->email,
        ]);

        return redirect()
            ->route('clientes.index')
            ->with('sucesso', 'Usuário cadastrado com sucesso.');
    }
}