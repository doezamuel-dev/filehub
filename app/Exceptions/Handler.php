<?php

namespace App\Exceptions;

use App\Services\ErrorHandlerService;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
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
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    /**
     * Render an exception into an HTTP response.
     */
    public function render($request, Throwable $e)
    {
        // Handle AJAX requests with JSON responses
        if ($request->expectsJson() || $request->is('api/*')) {
            return $this->handleJsonResponse($request, $e);
        }

        // Handle web requests
        return $this->handleWebResponse($request, $e);
    }

    /**
     * Handle JSON responses for AJAX/API requests
     */
    protected function handleJsonResponse(Request $request, Throwable $e)
    {
        $errorHandler = new ErrorHandlerService();

        // Handle validation exceptions specially
        if ($e instanceof ValidationException) {
            $errorData = $errorHandler->handleValidationError($e);
            return response()->json([
                'success' => false,
                'error' => $errorData,
            ], 422);
        }

        // Handle HTTP exceptions
        if ($e instanceof HttpException) {
            $errorData = $errorHandler->handleError($e, $request);
            return response()->json([
                'success' => false,
                'error' => $errorData,
            ], $e->getStatusCode());
        }

        // Handle all other exceptions
        $errorData = $errorHandler->handleError($e, $request);
        return response()->json([
            'success' => false,
            'error' => $errorData,
        ], 500);
    }

    /**
     * Handle web responses for regular page requests
     */
    protected function handleWebResponse(Request $request, Throwable $e)
    {
        // For web requests, we can still use the error handler for logging
        // but return the default Laravel error pages
        $errorHandler = new ErrorHandlerService();
        $errorHandler->handleError($e, $request);

        // Let Laravel handle the rest
        return parent::render($request, $e);
    }
}
