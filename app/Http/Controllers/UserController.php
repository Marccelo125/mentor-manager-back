<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response as HttpFoundationResponse;

class UserController extends Controller
{
    public function index()
    {
        try {
            $users = User::all();

            return response()->json(
                [
                    'success' => true,
                    'message' => 'Mostrando todo os usuários',
                    'data' => $users
                ],
                HttpFoundationResponse::HTTP_FOUND
            );
        } catch (\Throwable $th) {
            Log::error('Erro ao mostrar usuários', ['error' => $th->getMessage()]);
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Erro interno, tente novamente!',
                ],
                HttpFoundationResponse::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    public function store(Request $request)
    {
        // TODO = criar validação personalizada (laravel nativo)
        try {
            $request->validate([
                'name' => 'required|string|min:2',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|string|min:6',
1            ]);

            $data = $request->only([
                'name',
                'email',
                'password',
            ]);

            $data['password'] = Hash::make($data['password']);

            $user = User::create($data);

            return response()->json(
                [
                    'success' => true,
                    'message' => 'Usuário cadastrado com sucesso',
                    'data' => $user,
                ],
                HttpFoundationResponse::HTTP_CREATED
            );
        } catch (ValidationException $validationException) {
            return response()->json(
                [
                    'success' => false,
                    'message' => $validationException->getMessage(),
                ],
                HttpFoundationResponse::HTTP_UNPROCESSABLE_ENTITY
            );
        } catch (\Throwable $th) {
            Log::error('Erro ao cadastrar usuário', ['error' => $th->getMessage()]);
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Erro interno, tente novamente!',
                ],
                HttpFoundationResponse::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
}
