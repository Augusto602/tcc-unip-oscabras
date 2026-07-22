<?php

namespace App\Http\Middleware;

use App\Models\Usuario;
use Closure;
use Illuminate\Http\Request;

class ApiAutenticado
{
    public function handle(Request $request, Closure $next)
    {
        $token = $request->bearerToken()
            ?: $request->header('X-Api-Token')
            ?: $request->input('api_token');

        if (!$token) {
            return response()->json([
                'sucesso' => false,
                'mensagem' => 'Não autenticado. Informe um token válido (Bearer).',
            ], 401);
        }

        $usuario = Usuario::where('api_token', $token)->first();

        if (!$usuario) {
            return response()->json([
                'sucesso' => false,
                'mensagem' => 'Não autenticado. Informe um token válido (Bearer).',
            ], 401);
        }

        $request->attributes->set('usuario_api', $usuario);

        return $next($request);
    }
}
