<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use \Illuminate\Auth\AuthenticationException as AuthenticationException;
use Illuminate\Session\TokenMismatchException as TokenMismatchException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->renderable(function (TokenMismatchException $e, $request) {
            return response()->json([
                'status' => 419,
                'message' => $e->getMessage()
            ], 419);
        });
        $this->renderable(function (AuthenticationException $e, $request) {
            return response()->json([
                'status' => 401,
                'message' => $e->getMessage()
            ], 401);
        });

        $this->reportable(function (Throwable $e) {
            //
        });

        $this->renderable(function (NotFoundHttpException $e, $request) {
            return response()->json([
                'status' => 404,
                'message' => 'Not found'
            ], 404);
        });



    }
}