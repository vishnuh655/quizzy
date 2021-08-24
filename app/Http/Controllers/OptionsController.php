<?php

namespace App\Http\Controllers;

use App\Constants\ResponseMessage;
use App\Constants\Status;
use App\Models\Option;
use Illuminate\Http\Request;
use App\Models\Question;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class OptionsController extends Controller
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
     *     path="/api/options/{optionId}",
     *     summary="Get Option By Id",
     *     description="Get Option By Id",
     *     @OA\Parameter(
     *         name="optionId",
     *         in="path",
     *         description="ID of option that needs to be fetched",
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
    public function fetchById($optionId)
    {
        if (!Str::isUuid($optionId)) {
            return $this->sendValidationFailResponse(
                ResponseMessage::INVALID_ID
            );
        }
        $option = Option::find($optionId);
        return $option
            ? $this->sendSuccessResponse($option)
            : $this->sendNotFoundResponse();
    }

    /**
     * @OA\Get(
     *     path="/api/options",
     *     summary="Get All Options",
     *     description="Get list of all options",
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
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
        $options = Option::all();
        return $this->sendSuccessResponse($options);
    }

    /**
     * @OA\Post(
     *     path="/api/options",
     *     summary="Create an option",
     *     description="Create an option(s) for a question",
     *     @OA\RequestBody(
     *         required=true,
     *         description="Data required for creating the options for the question",
     *         @OA\JsonContent(
     *              type="object",
     *              @OA\Property(
     *                  property="options",
     *                  type="array",
     *                  @OA\Items(
     *                      @OA\Property(
     *                          property="optionContent",
     *                          type="string",
     *                          example="Call Of Duty"
     *                      ),
     *                      @OA\Property(
     *                          property="isAnswer",
     *                          type="boolean",
     *                          example="true"
     *                      ),
     *                      @OA\Property(
     *                          property="questionId",
     *                          type="string",
     *                          example=""
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
        if (!array_key_exists("options", $request->all())) {
            return $this->sendErrorResponse(ResponseMessage::INVALID_JSON);
        }
        $validator = Validator::make($request->all(), [
            "options" => "array",
            "options.*.optionContent" => "required|string",
            "options.*.isAnswer" => "required|boolean",
            "options.*.questionId" => "required|string"
        ]);
        if ($validator->fails()) {
            return $this->sendValidationFailResponse($validator->errors());
        }

        $optionsToBeInserted = [];
        $questionIds = [];
        foreach ($request->all()["options"] as $option) {
            if (!Str::isUuid($option["questionId"])) {
                return $this->sendValidationFailResponse(
                    ResponseMessage::INVALID_ID
                );
            }
            array_push($optionsToBeInserted, [
                "optionId" => Str::uuid()->toString(),
                "questionId" => $option["questionId"],
                "optionContent" => $option["optionContent"],
                "isAnswer" => $option["isAnswer"]
            ]);
            array_push($questionIds, $option["questionId"]);
        }
        $questionIds = array_unique($questionIds);
        if (
            Question::whereIn("questionId", $questionIds)->count() ==
            count($questionIds)
        ) {
            $createdOptions = Option::insert($optionsToBeInserted);
            return $this->sendSuccessResponse($createdOptions);
        } else {
            return $this->sendValidationFailResponse(
                ResponseMessage::INVALID_ID
            );
        }
    }
}
