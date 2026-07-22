<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\LoginRequest;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        $usuario = Usuario::where('email', $request->email)->first();

        if (!$usuario) {
            return $this->erro('Usuário não encontrado.', 404);
        }

        $senhaInformada = $request->senha;
        $senhaBanco = $usuario->senha_hash;

        $senhaValida = Hash::check($senhaInformada, $senhaBanco)
            || $senhaInformada === $senhaBanco;

        if (!$senhaValida) {
            return $this->erro('Senha incorreta.', 401);
        }

        // Migra senha em texto puro para hash na primeira autenticação
        if ($senhaInformada === $senhaBanco) {
            $usuario->senha_hash = Hash::make($senhaInformada);
            $usuario->save();
        }

        $token = $usuario->emitirToken();

        return $this->sucesso([
            'usuario' => $usuario->fresh()->paraResposta(),
            'api_token' => $token,
            'token_type' => 'Bearer',
        ], 'Login realizado com sucesso.');
    }

    public function me(Request $request)
    {
        /** @var Usuario $usuario */
        $usuario = $request->attributes->get('usuario_api');

        return $this->sucesso([
            'usuario' => $usuario->paraResposta(),
        ]);
    }

    public function logout(Request $request)
    {
        /** @var Usuario $usuario */
        $usuario = $request->attributes->get('usuario_api');
        $usuario->revogarToken();

        return $this->sucesso(null, 'Logout realizado com sucesso.');
    }
}
