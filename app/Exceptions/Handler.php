<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use App\Http\Controllers\Api\V1\ApiResponseAble;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

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
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param Exception $exception
     *
     * @return void
     *
     * @throws Exception
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * @param           $request
     * @param Exception $exception
     *
     * @return JsonResponse | Response
     * @throws Exception
     * @author <ferasbbm>
     */
    public function render($request, Exception $exception): ?JsonResponse
    {
        if ($request->wantsJson() || $request->is('api/*')) {
            if ($exception instanceof ModelNotFoundException) {
                return $this->notFoundResponse();
            }

            if ($exception instanceof AuthenticationException) {
                return $this->unAuthenticatedResponse();
            }

            if ($exception instanceof ValidationException) {
                return $this->validationErrorResponse($exception->errors());
            }
        }

        return parent::render($request, $exception);
    }

}
