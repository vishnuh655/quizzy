<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Http\Response;
use App\Constants\ResponseCode;

class Controller extends BaseController
{
    /** @OA\Info(title="Quizzy API", version="0.1") */

    private function sendResponse($content, $status)
    {
        return (new Response($content, $status))->header(
            "Content-Type",
            "application/json"
        );
    }

    public function sendValidationResponse($error)
    {
        return $this->sendResponse(
            $error,
            ResponseCode::HTTP_UNPROCESSABLE_ENTITY
        );
    }

    public function sendSuccessResponse($data)
    {
        return $this->sendResponse($data, ResponseCode::HTTP_OK);
    }
}
