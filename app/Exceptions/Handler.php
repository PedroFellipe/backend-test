<?php

namespace App\Exceptions;

use Throwable;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

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
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param \Exception $exception
     * @return void
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Exception $exception
     * @return \Illuminate\Http\Response
     */
public function render($request, Throwable $exception)
    {
        if ($exception instanceof UnauthorizedHttpException) {
            $preException = $exception->getPrevious();
            if ($preException instanceof
                \Tymon\JWTAuth\Exceptions\TokenExpiredException) {
                return response()->json(['error' => 'TOKEN_EXPIRED'], 401);
            } else if ($preException instanceof
                \Tymon\JWTAuth\Exceptions\TokenInvalidException) {
                return response()->json(['error' => 'TOKEN_INVALID'], 401);
            } else if ($preException instanceof
                \Tymon\JWTAuth\Exceptions\TokenBlacklistedException) {
                return response()->json(['error' => 'TOKEN_BLACKLISTED'], 401);
            }
            if ($exception->getMessage() === 'Token not provided') {
                return response()->json(['error' => 'Token not provided'], 401);
            }
        }

        if ($request->wantsJson()) {
            $this->renderExceptionAsJson($request, $exception);
        }
        return parent::render($request, $exception);
    }

    public function renderExceptionAsJson($request, Exception $exception)
    {
        // Currently converts AuthorizationException to 403 HttpException
        // and ModelNotFoundException to 404 NotFoundHttpException
        $exception = $this->prepareException($exception);
        // Default response
        $response = [
            'error' => 'Sorry, something went wrong.'
        ];

        // Add debug info if app is in debug mode
        if (config('app.debug')) {
            // Add the exception class name, message and stack trace to response
            $response['exception'] = get_class($exception); // Reflection might be better here
            $response['message'] = $exception->getMessage();
            $response['trace'] = $exception->getTrace();
        }

        $status = 400;
        // Build correct status codes and status texts
        switch ($exception) {
            case $exception instanceof ValidationException:
                return $this->convertValidationExceptionToResponse($exception, $request);
            case $exception instanceof AuthenticationException:
                $status = 401;
                $response['error'] = Response::$statusTexts[$status];
                break;
            case $this->isHttpException($exception):
                $status = $exception->getStatusCode();
                $response['error'] = Response::$statusTexts[$status];
                break;
            default:
                break;
        }

        return response()->json($response, $status);
    }
}
