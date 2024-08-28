<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response as HttpFoundationResponse;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email',
                'password' => 'required|string',
            ], [
                'required' => 'o campo :attribute é obrigatório',
                'string' => 'o campo :attribute deve ser do tipo string',
                'email' => 'O campo :attribute precisa ser um email válido',
            ]);

            $user = User::where('email', $request->email)->first();
            if (!$user || !Hash::check($request->password, $user->password)) {
                return response()->json(
                    [
                        'success' => false,
                        'message' => 'Email e/ou senha inválidos'
                    ],
                    HttpFoundationResponse::HTTP_UNAUTHORIZED
                );
            }

            $user->tokens()->delete();
            $token = $user->createToken('auth_token', ['admin'], now()->addDay())->plainTextToken;

            return response()->json(
                [
                    'success' => true,
                    'message' => 'Login feito com sucesso',
                    'data' => $user,
                    'token' => $token
                ],
                HttpFoundationResponse::HTTP_OK
            );
        } catch (\Throwable $th) {
            Log::error('Erro ao logar', ['error' => $th->getMessage()]);

            return response()->json(
                [
                    'success' => false,
                    'message' => 'Erro interno, tente novamente!',
                ],
                HttpFoundationResponse::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }


    public function destroy(Request $request)
    {
        try {
            $request->user()->tokens()->delete();
            return response()->json(
                [
                    'success' => true,
                    'msg' => "Autorização deletada com sucesso"
                ],
                HttpFoundationResponse::HTTP_OK
            );
        } catch (\Throwable $th) {
            Log::error('Erro ao deslogar', ['error' => $th->getMessage()]);
            return response()->json(
                [
                    'success' => false,
                    'msg' => "Erro ao deletar autorização, tente novamente!",
                ],
                HttpFoundationResponse::HTTP_BAD_REQUEST
            );
        }
    }
}
