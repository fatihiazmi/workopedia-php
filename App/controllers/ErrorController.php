<?php

namespace App\Controllers;

class ErrorController
{

    public static function notFound($message = 'Resource not found')
    {
        http_response_code(404);

        loadView('errorView', [
            'status' => '404',
            'message' => $message
        ]);
    }
    public static function unAuthorized($message = 'You are not allowed to view this resource')
    {
        http_response_code(403);

        loadView('errorView', [
            'status' => '403',
            'message' => $message
        ]);
    }
}
