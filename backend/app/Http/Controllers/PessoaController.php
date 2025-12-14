<?php

namespace App\Http\Controllers;

use App\Models\Pessoa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PessoaController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/pessoas",
     *     summary="Listar todas as pessoas",
     *     tags={"Pessoas"},
     *     security={{"sanctum":{}}},
     *     @OA\Response(response=200, description="Lista de pessoas")
     * )
     */
    public function index()
    {
        Log::info('Usuário autenticado: ' . auth()->user()->email);
        return Pessoa::all();
    }

    /**
     * @OA\Post(
     *     path="/api/pessoas",
     *     summary="Criar nova pessoa",
     *     tags={"Pessoas"},
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"nome","cpf","tipo","telefone","email"},
     *             @OA\Property(property="nome", type="string", example="João Silva"),
     *             @OA\Property(property="cpf", type="string", example="123.456.789-00"),
     *             @OA\Property(property="tipo", type="string", enum={"fisica","juridica"}),
     *             @OA\Property(property="telefone", type="string", example="(11) 98765-4321"),
     *             @OA\Property(property="email", type="string", example="joao@email.com")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Pessoa criada"),
     *     @OA\Response(response=422, description="Erro de validação")
     * )
     */
    public function store(Request $request)
    {
        $rules = [
            'nome' => 'required|string|max:255',
            'tipo' => 'required|in:fisica,juridica',
            'telefone' => 'required|string',
            'email' => 'required|email',
        ];

        if ($request->tipo === 'fisica') {
            $rules['documento'] = 'required|string|size:11|unique:pessoas,documento';
        } else {
            $rules['documento'] = 'required|string|size:14|unique:pessoas,documento';
        }

        $validated = $request->validate($rules);

        $validated['documento'] = preg_replace('/\D/', '', $validated['documento']);
        $validated['telefone'] = preg_replace('/\D/', '', $validated['telefone']);

        $pessoa = Pessoa::create($validated);

        return response()->json($pessoa, 201);
    }

    /**
     * @OA\Get(
     *     path="/api/pessoas/{id}",
     *     summary="Buscar pessoa por ID",
     *     tags={"Pessoas"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Pessoa encontrada"),
     *     @OA\Response(response=404, description="Pessoa não encontrada")
     * )
     */
    public function show(Pessoa $pessoa)
    {
        return $pessoa;
    }

    /**
     * @OA\Put(
     *     path="/api/pessoas/{id}",
     *     summary="Atualizar pessoa",
     *     tags={"Pessoas"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="nome", type="string"),
     *             @OA\Property(property="cpf", type="string"),
     *             @OA\Property(property="tipo", type="string"),
     *             @OA\Property(property="telefone", type="string"),
     *             @OA\Property(property="email", type="string")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Pessoa atualizada")
     * )
     */
    public function update(Request $request, Pessoa $pessoa)
    {
        $rules = [
            'nome' => 'sometimes|required|string|max:255',
            'tipo' => 'sometimes|required|in:fisica,juridica',
            'telefone' => 'sometimes|required|string',
            'email' => 'sometimes|required|email',
        ];

        $tipo = $request->tipo ?? $pessoa->tipo;
        if ($tipo === 'fisica') {
            $rules['documento'] = 'sometimes|required|string|size:11|unique:pessoas,documento,' . $pessoa->id;
        } else {
            $rules['documento'] = 'sometimes|required|string|size:14|unique:pessoas,documento,' . $pessoa->id;
        }

        $validated = $request->validate($rules);

        if (isset($validated['documento'])) {
            $validated['documento'] = preg_replace('/\D/', '', $validated['documento']);
        }
        if (isset($validated['telefone'])) {
            $validated['telefone'] = preg_replace('/\D/', '', $validated['telefone']);
        }

        $pessoa->update($validated);

        return response()->json($pessoa);
    }

    /**
     * @OA\Delete(
     *     path="/api/pessoas/{id}",
     *     summary="Excluir pessoa",
     *     tags={"Pessoas"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=204, description="Pessoa excluída")
     * )
     */
    public function destroy(Pessoa $pessoa)
    {
        $pessoa->delete();

        return response()->json(null, 204);
    }
}