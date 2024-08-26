<?php

namespace App\Http\Controllers;

use App\Models\Mentor;
use Dotenv\Exception\ValidationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response as HttpFoundationResponse;

class MentorController extends Controller
{
    public function index()
    {
        try {
            $mentors = Mentor::all();
            return response()->json(
                [
                    'success' => true,
                    'message' => 'Mentores listados com sucesso',
                    'data' => $mentors
                ],
                HttpFoundationResponse::HTTP_FOUND
            );
        } catch (\Throwable $th) {
            Log::error('Erro ao mostrar mentores', ['error' => $th->getMessage()]);
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
        try {
            $request->validate([
                'name' => 'required|string|min:2',
                'email' => 'required|email|unique:users,email',
                'cpf' => 'required|string|max:11|min:11',
            ], [
                'name.min' => 'O campo :attribute deve conter no minimo 2 caracteres',
                ['cpf.max', 'cpf.min'] => 'O campo :attribute deve conter 11 caracteres',
                'required' => 'o campo :attribute é obrigatório',
                'string' => 'o campo :attribute deve ser do tipo string',
                'email' => 'O campo :attribute precisa ser um email válido',
                'unique' => 'o campo :attribute deve ser único',
            ]);

            $data = $request->only([
                'name',
                'email',
                'cpf',
            ]);

            $mentor = Mentor::create($data);

            return response()->json(
                [
                    'success' => true,
                    'message' => 'Mentor cadastrado com sucesso',
                    'data' => $mentor
                ],
                HttpFoundationResponse::HTTP_CREATED
            );
        } catch (ValidationException $validationException) {
            Log::error('Erro de validação', ['error' => $validationException->getMessage()]);
            return response()->json(
                [
                    'success' => false,
                    'message' => $validationException->getMessage(),
                ],
                HttpFoundationResponse::HTTP_UNPROCESSABLE_ENTITY
            );
        } catch (\Throwable $th) {
            Log::error('Erro ao cadastrar mentor', ['error' => $th->getMessage()]);
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Erro interno, tente novamente!',
                ],
                HttpFoundationResponse::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    public function show(Mentor $mentor)
    {
        try {
            $mentor = Mentor::find($mentor->id);
            return response()->json(
                [
                    'success' => true,
                    'message' => 'Mentor cadastrado com sucesso',
                    'data' => $mentor
                ],
                HttpFoundationResponse::HTTP_FOUND
            );
        } catch (\Throwable $th) {
            Log::error('Erro ao procurar mentor', ['error' => $th->getMessage()]);
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Erro interno, tente novamente!',
                ],
                HttpFoundationResponse::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    public function update(Request $request, Mentor $mentor)
    {
        try {
            $request->validate([
                'name' => 'required|string|min:2',
                'email' => 'required|email|unique:users,email',
                'cpf' => 'required|string|max:11|min:11',
            ], [
                'name.min' => 'O campo :attribute deve conter no minimo 2 caracteres',
                ['cpf.max', 'cpf.min'] => 'O campo :attribute deve conter 11 caracteres',
                'required' => 'o campo :attribute é obrigatório',
                'string' => 'o campo :attribute deve ser do tipo string',
                'email' => 'O campo :attribute precisa ser um email válido',
                'unique' => 'o campo :attribute deve ser único',
            ]);

            if($request->name) $mentor->name = $request->name;
            if($request->email) $mentor->email = $request->email;
            if($request->cpf) $mentor->cpf = $request->cpf;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function destroy(Mentor $mentor)
    {
        //
    }
}