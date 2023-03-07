<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Ecommify\Platform\Exceptions\ApiRequestException;
use Throwable;
use InvalidArgumentException;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
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
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });

        $this->renderable(function (AuthenticationException | AccessDeniedHttpException $e, $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'error' => [
                        'message' => $e->getMessage(),
                        'code' => 'unauthorized',
                    ]
                ], 401);
            }
        });

        $this->renderable(function (NotFoundHttpException $e, $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'error' => [
                        'message' => trans('app.page_not_found'),
                        'code' => 'not_found',
                    ]
                ], 404);
            }
        });

        $this->renderable(function (ApiRequestException $e, $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'error' => [
                        'message' => trans('app.platform_not_access'),
                        'code' => 'platform_not_access',
                    ]
                ], 400);
            }
        });
        // $this->renderable(function (InvalidArgumentException $e, $request) {
        //     if ($request->expectsJson()) {
        //         return response()->json([
        //             'success' => false,
        //             'error' => [
        //                 'message' => trans('app.server_error'),
        //                 'code' => 'server_error',
        //             ]
        //         ], 400);
        //     }
        // });
    }
}
