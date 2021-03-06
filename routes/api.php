<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get("/", function () use ($router) {
    return $router->app->version();
});

$router->group(["prefix" => "api"], function () use ($router) {
    //Questions
    $router->get("questions", [
        "uses" => "QuestionsController@fetchAll"
    ]);
    $router->get("questions/{questionId}", [
        "uses" => "QuestionsController@fetchById"
    ]);
    $router->post("questions", [
        "uses" => "QuestionsController@store"
    ]);
    $router->put("questions", [
        "uses" => "QuestionsController@update"
    ]);

    //Options
    $router->post("options", [
        "uses" => "OptionsController@store"
    ]);
    $router->get("options/{optionId}", [
        "uses" => "OptionsController@fetchById"
    ]);
    $router->get("options", [
        "uses" => "OptionsController@fetchAll"
    ]);
});
