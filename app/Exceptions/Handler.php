<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\{NotFoundHttpException, AccessDeniedHttpException};
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });

        $this->renderable(function (NotFoundHttpException $exception, $request) {
            if ($request->wantsJson()) {
                return response()->json(['error' => 'Resource not found'], 404);
            }
        });

        $this->renderable(function (AccessDeniedHttpException $exception, $request) {
            if ($request->wantsJson()) {
                return response()->json(['error' => 'This action is unauthorized'], 403);
            }
        });
    }
}
