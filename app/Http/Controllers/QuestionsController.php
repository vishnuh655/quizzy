<?php

namespace App\Http\Controllers;

use App\Constants\Status;
use Illuminate\Http\Request;
use App\Models\Question;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

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
     *              type="array",
     *              @OA\Items(
     *                 @OA\Property(
     *                      property="content",
     *                      type="string",
     *                      example="Which popular video game franchise has released games with the subtitles World At War and Black Ops?"
     *                 ),
     *                  @OA\Property(
     *                      property="typeId",
     *                      type="integer",
     *                      example="1"
     *                 ),
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
    public function storeQuestions(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "*.content" => "required|string",
            "*.typeId" => "required|numeric"
        ]);

        if ($validator->fails()) {
            return $this->sendValidationResponse($validator->errors());
        }

        $questionsToBeInserted = [];
        foreach ($request->all() as $question) {
            array_push($questionsToBeInserted, [
                "id" => Str::uuid()->toString(),
                "content" => $question["content"],
                "type_id" => $question["typeId"],
                "status" => Status::Active
            ]);
        }
        $createdQuestion = Question::insert($questionsToBeInserted);
        return $this->sendSuccessResponse($createdQuestion);
    }
}
