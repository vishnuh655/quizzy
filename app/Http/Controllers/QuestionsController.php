<?php

namespace App\Http\Controllers;

use App\Constants\Status;
use Illuminate\Http\Request;
use App\Models\Question;

class QuestionsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }
    /**
     * @OA\Get(
     *     path="/api/questions",
     *     summary="Get All Questions",
     *     description="Get list of all questions",
     *     operationId="findPetsByTags",
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *         @OA\Schema(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Pet")
     *         ),
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Invalid tag value",
     *     ),
     *     security={
     *         {"petstore_auth": {"write:pets", "read:pets"}}
     *     },
     * )
     */
    public function fetchAll()
    {
        return Question::all();
    }

    /**
     * @OA\Post(
     *     path="/api/questions",
     *     summary="Create a question",
     *     description="Create a question",
     *     operationId="findPetsByTags",
     *     @OA\RequestBody(
     *         required=true,
     *         description="Data required for creating the question",
     *         @OA\JsonContent(
     *              @OA\Property(
     *                  property="content",
     *                  type="string"
     *              ),
     *              @OA\Property(
     *                  property="typeId",
     *                  type="integer"
     *              ),
     *          ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *         @OA\Schema(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Pet")
     *         ),
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Invalid tag value",
     *     ),
     *     security={
     *         {"petstore_auth": {"write:pets", "read:pets"}}
     *     },
     * )
     */
    public function storeQuestion(Request $request)
    {
        $this->validate($request, [
            "content" => "required|string",
            "typeId" => "required|numeric"
        ]);

        $createdQuestion = Question::create([
            "content" => $request->input("content"),
            "type_id" => $request->input("typeId"),
            "status" => Status::Active
        ]);

        return $createdQuestion;
    }
}
