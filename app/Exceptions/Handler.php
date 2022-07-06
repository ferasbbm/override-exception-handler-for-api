<?php

namespace App\Exceptions;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Auth\AuthenticationException;
use App\Http\Controllers\Api\V1\ApiResponseAble;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Throwable;

/**
 * Class Handler
 *
 * @author  <feras.bbm@gmail.com>
 * @package App\Exceptions
 */
class Handler extends ExceptionHandler
{
    use ApiResponseAble;

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
    }

    /**
     * @param                         $request
     * @param AuthenticationException $exception
     *
     * @return RedirectResponse | Response
     * @author <ferasbbm>
     */
    protected function unauthenticated($request, AuthenticationException $exception): ?JsonResponse
    {
        return $request->wantsJson() || $request->routeIs('*/api/*')
            ? $this->unAuthenticatedResponse()
            : redirect()->guest($exception->guards()[ 0 ] == 'admin' ? route('admin.login') : $exception->redirectTo() ?? route('login'));
    }

    protected function invalidJson($request, ValidationException $exception)
    {
        if ($request->wantsJson() || $request->routeIs('*/api/*'))
            return $this->validationErrorResponse($exception->errors());
    }
}

