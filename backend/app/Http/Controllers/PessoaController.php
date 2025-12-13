<?php

namespace App\Http\Controllers;

use App\Models\Pessoa;
use Illuminate\Http\Request;

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
        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'cpf' => 'required|string|unique:pessoas,cpf',
            'tipo' => 'required|in:fisica,juridica',
            'telefone' => 'required|string',
            'email' => 'required|email',
        ]);

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
        $validated = $request->validate([
            'nome' => 'sometimes|required|string|max:255',
            'cpf' => 'sometimes|required|string|unique:pessoas,cpf,' . $pessoa->id,
            'tipo' => 'sometimes|required|in:fisica,juridica',
            'telefone' => 'sometimes|required|string',
            'email' => 'sometimes|required|email',
        ]);

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