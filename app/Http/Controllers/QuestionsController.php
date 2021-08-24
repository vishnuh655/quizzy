<?php

namespace App\Http\Controllers;

use App\Constants\ResponseMessage;
use App\Constants\Status;
use Illuminate\Http\Request;
use App\Models\Question;
use App\Models\Option;
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
     *     path="/api/questions/{questionId}",
     *     summary="Get Question By Id",
     *     tags={"Questions"},
     *     description="Get Question By Id",
     *     @OA\Parameter(
     *         name="questionId",
     *         in="path",
     *         description="ID of question that needs to be fetched",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *         @OA\Schema(
     *             type="array"
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
    public function fetchById($questionId)
    {
        if (!Str::isUuid($questionId)) {
            return $this->sendValidationFailResponse(
                ResponseMessage::INVALID_ID
            );
        }
        $questions = Question::find($questionId);
        return $questions
            ? $this->sendSuccessResponse($questions)
            : $this->sendNotFoundResponse();
    }

    /**
     * @OA\Get(
     *     path="/api/questions",
     *     tags={"Questions"},
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
        $questions = Question::with("options")->get();
        return $this->sendSuccessResponse($questions);
    }

    /**
     * @OA\Post(
     *     path="/api/questions",
     *     summary="Create a Question",
     *     description="Create a question or questions with option",
     *     tags={"Questions"},
     *     operationId="findPetsByTags",
     *     @OA\RequestBody(
     *         required=true,
     *         description="Data required for creating the question",
     *         @OA\JsonContent(
     *              type="object",
     *              @OA\Property(
     *                  property="questions",
     *                  type="array",
     *                  @OA\Items(
     *                      @OA\Property(
     *                          property="questionContent",
     *                          type="string",
     *                          example="Which popular video game franchise has released games with the subtitles World At War and Black Ops?"
     *                      ),
     *                      @OA\Property(
     *                          property="typeId",
     *                          type="integer",
     *                          example="1"
     *                      ),
     *                      @OA\Property(
     *                          property="options",
     *                          type="array",
     *                          @OA\Items(
     *                              @OA\Property(
     *                                  property="optionContent",
     *                                  type="string",
     *                                  example="Call Of Duty"
     *                              ),
     *                              @OA\Property(
     *                                  property="isAnswer",
     *                                  type="boolean",
     *                                  example="true"
     *                              ),
     *                          ),
     *                      ),
     *                  ),
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
    public function store(Request $request)
    {
        if (!array_key_exists("questions", $request->all())) {
            return $this->sendErrorResponse(ResponseMessage::INVALID_JSON);
        }
        $validator = Validator::make($request->all(), [
            "questions" => "required|array",
            "questions.*.questionContent" => "required|string",
            "questions.*.typeId" => "required|numeric",
            "questions.*.options" => "array",
            "questions.*.options.*.optionContent" => "required|string",
            "questions.*.options.*.isAnswer" => "required|boolean"
        ]);
        if ($validator->fails()) {
            return $this->sendValidationFailResponse($validator->errors());
        }

        $questionsToBeInserted = [];
        $optionsToBeInserted = [];
        foreach ($request->all()["questions"] as $question) {
            $questionId = Str::uuid()->toString();
            array_push($questionsToBeInserted, [
                "questionId" => $questionId,
                "questionContent" => $question["questionContent"],
                "typeId" => $question["typeId"],
                "status" => Status::ACTIVE
            ]);
            if (array_key_exists("options", $question)) {
                foreach ($question["options"] as $option) {
                    array_push($optionsToBeInserted, [
                        "optionId" => Str::uuid()->toString(),
                        "questionId" => $questionId,
                        "optionContent" => $option["optionContent"],
                        "isAnswer" => $option["isAnswer"]
                    ]);
                }
            }
        }
        $createdQuestion = Question::insert($questionsToBeInserted);
        $createdOptions = Option::insert($optionsToBeInserted);
        return $this->sendSuccessResponse($createdOptions && $createdQuestion);
    }
}
