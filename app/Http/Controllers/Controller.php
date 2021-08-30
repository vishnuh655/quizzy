<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Http\Response;
use App\Constants\ResponseCode;
use App\Constants\ResponseMessage;
use App\Constants\ResponseStatus;

class Controller extends BaseController
{
    /** @OA\Info(title="Quizzy API", version="0.1") */

    private function sendResponse($content, $status)
    {
        return (new Response($content, $status))
            ->header(
                "Content-Type",
                "application/json",
                "Access-Control-Allow-Origin"
            )
            ->header("Access-Control-Allow-Origin", "*");
    }

    private function responseBody($status, $data, $responseCode = null)
    {
        return $status == ResponseStatus::ERROR
            ? [
                "status" => $status,
                "message" => $data,
                "code" => $responseCode
            ]
            : [
                "status" => $status,
                "data" => $data
            ];
    }

    /**
     * When an API call is rejected due to invalid data or call conditions
     */
    public function sendValidationFailResponse($error)
    {
        return $this->sendResponse(
            $this->responseBody(ResponseStatus::FAIL, $error),
            ResponseCode::HTTP_UNPROCESSABLE_ENTITY
        );
    }

    /**
     * When an API call is successful
     */
    public function sendSuccessResponse($data)
    {
        return $this->sendResponse(
            $this->responseBody(ResponseStatus::SUCCESS, $data),
            ResponseCode::HTTP_OK
        );
    }

    /**
     * When an API call fails due to an error on the server
     */
    public function sendErrorResponse($data)
    {
        return $this->sendResponse(
            $this->responseBody(
                ResponseStatus::ERROR,
                $data,
                ResponseCode::HTTP_BAD_REQUEST
            ),
            ResponseCode::HTTP_BAD_REQUEST
        );
    }

    public function sendNotFoundResponse()
    {
        return $this->sendResponse(
            $this->responseBody(
                ResponseStatus::FAIL,
                ResponseMessage::ITEM_NOT_FOUND
            ),
            ResponseCode::HTTP_NOT_FOUND
        );
    }
}
