<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use App\Http\Controllers\Api\V1\ApiResponseAble;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class Handler
 *
 * @author  <feras.bbm@gmail.com>
 * @package https://github.com/ferasbbm/override-exception-handler-for-api
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
     * @param Exception $exception
     *
     * @return void
     * @throws Exception
     * @author <ferasbbm>
     */
    public function report(Exception $exception): void
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
    public function render($request, Exception $exception)
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

            if ($exception instanceof NotFoundHttpException) {
                return $this->ApiErrorResponse(null, trans('api.urlNotFound'),Response::HTTP_NOT_FOUND);
            }
        }

        return parent::render($request, $exception);
    }

}
