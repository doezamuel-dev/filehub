<?php

namespace App\Services;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

class ErrorHandlerService
{
    /**
     * Handle and format errors for user display
     */
    public function handleError(Throwable $exception, Request $request = null): array
    {
        $errorCode = $this->getErrorCode($exception);
        $userMessage = $this->getUserFriendlyMessage($exception);
        $technicalMessage = $exception->getMessage();
        
        // Log the error for debugging
        Log::error('Application Error', [
            'error_code' => $errorCode,
            'message' => $technicalMessage,
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'trace' => $exception->getTraceAsString(),
            'user_id' => auth()->id(),
            'url' => $request?->fullUrl(),
            'method' => $request?->method(),
        ]);

        return [
            'error_code' => $errorCode,
            'user_message' => $userMessage,
            'technical_message' => $technicalMessage,
            'suggestions' => $this->getSuggestions($exception),
            'timestamp' => now()->toISOString(),
        ];
    }

    /**
     * Create a JSON error response
     */
    public function createErrorResponse(Throwable $exception, Request $request = null, int $statusCode = 500): JsonResponse
    {
        $errorData = $this->handleError($exception, $request);
        
        return response()->json([
            'success' => false,
            'error' => $errorData,
        ], $statusCode);
    }

    /**
     * Get error code based on exception type
     */
    private function getErrorCode(Throwable $exception): string
    {
        if ($exception instanceof ValidationException) {
            return 'VALIDATION_ERROR';
        }
        
        if ($exception instanceof HttpException) {
            return match ($exception->getStatusCode()) {
                400 => 'BAD_REQUEST',
                401 => 'UNAUTHORIZED',
                403 => 'FORBIDDEN',
                404 => 'NOT_FOUND',
                405 => 'METHOD_NOT_ALLOWED',
                422 => 'UNPROCESSABLE_ENTITY',
                429 => 'TOO_MANY_REQUESTS',
                500 => 'INTERNAL_SERVER_ERROR',
                default => 'HTTP_ERROR',
            };
        }

        // File system errors
        if (str_contains($exception->getMessage(), 'No space left on device')) {
            return 'STORAGE_FULL';
        }
        
        if (str_contains($exception->getMessage(), 'Permission denied')) {
            return 'PERMISSION_DENIED';
        }
        
        if (str_contains($exception->getMessage(), 'File too large')) {
            return 'FILE_TOO_LARGE';
        }
        
        if (str_contains($exception->getMessage(), 'Disk quota exceeded')) {
            return 'QUOTA_EXCEEDED';
        }

        // Database errors
        if (str_contains($exception->getMessage(), 'Connection refused')) {
            return 'DATABASE_CONNECTION_ERROR';
        }
        
        if (str_contains($exception->getMessage(), 'Duplicate entry')) {
            return 'DUPLICATE_ENTRY';
        }

        return 'UNKNOWN_ERROR';
    }

    /**
     * Get user-friendly error message
     */
    private function getUserFriendlyMessage(Throwable $exception): string
    {
        $errorCode = $this->getErrorCode($exception);

        return match ($errorCode) {
            'VALIDATION_ERROR' => 'Please check your input and try again. Some fields may be missing or invalid.',
            'BAD_REQUEST' => 'The request was invalid. Please try again with correct information.',
            'UNAUTHORIZED' => 'You need to log in to access this feature.',
            'FORBIDDEN' => 'You don\'t have permission to perform this action.',
            'NOT_FOUND' => 'The requested item could not be found. It may have been moved or deleted.',
            'METHOD_NOT_ALLOWED' => 'This action is not allowed.',
            'UNPROCESSABLE_ENTITY' => 'The request was well-formed but contains invalid data.',
            'TOO_MANY_REQUESTS' => 'You\'re making requests too quickly. Please wait a moment and try again.',
            'STORAGE_FULL' => 'The server is out of storage space. Please contact support or try again later.',
            'PERMISSION_DENIED' => 'You don\'t have permission to access this file or folder.',
            'FILE_TOO_LARGE' => 'The file is too large to upload. Please choose a smaller file or contact support.',
            'QUOTA_EXCEEDED' => 'You\'ve exceeded your storage quota. Please delete some files or upgrade your plan.',
            'DATABASE_CONNECTION_ERROR' => 'We\'re experiencing technical difficulties. Please try again in a few moments.',
            'DUPLICATE_ENTRY' => 'This item already exists. Please choose a different name.',
            'INTERNAL_SERVER_ERROR' => 'Something went wrong on our end. We\'ve been notified and are working to fix it.',
            default => 'An unexpected error occurred. Please try again or contact support if the problem persists.',
        };
    }

    /**
     * Get helpful suggestions based on error type
     */
    private function getSuggestions(Throwable $exception): array
    {
        $errorCode = $this->getErrorCode($exception);

        return match ($errorCode) {
            'VALIDATION_ERROR' => [
                'Check that all required fields are filled',
                'Ensure file formats are supported',
                'Verify file sizes are within limits'
            ],
            'UNAUTHORIZED' => [
                'Please log in to your account',
                'Check if your session has expired',
                'Try refreshing the page'
            ],
            'FORBIDDEN' => [
                'Contact the file owner for access',
                'Check if you have the correct permissions',
                'Verify you\'re logged into the right account'
            ],
            'NOT_FOUND' => [
                'Check if the file or folder still exists',
                'Look in the trash if it was recently deleted',
                'Verify the link is correct'
            ],
            'FILE_TOO_LARGE' => [
                'Try compressing the file',
                'Split large files into smaller parts',
                'Contact support for assistance with large files'
            ],
            'QUOTA_EXCEEDED' => [
                'Delete unused files to free up space',
                'Empty your trash folder',
                'Consider upgrading your storage plan'
            ],
            'STORAGE_FULL' => [
                'Try again later when space is available',
                'Contact support for immediate assistance',
                'Check our status page for updates'
            ],
            'DATABASE_CONNECTION_ERROR' => [
                'Wait a few moments and try again',
                'Check your internet connection',
                'Contact support if the problem persists'
            ],
            default => [
                'Try refreshing the page',
                'Check your internet connection',
                'Contact support if the problem continues'
            ]
        };
    }

    /**
     * Handle validation errors specifically
     */
    public function handleValidationError(ValidationException $exception): array
    {
        $errors = $exception->errors();
        $userMessage = 'Please correct the following errors:';
        
        $formattedErrors = [];
        foreach ($errors as $field => $messages) {
            $formattedErrors[$field] = $messages[0]; // Get first error message
        }

        return [
            'error_code' => 'VALIDATION_ERROR',
            'user_message' => $userMessage,
            'validation_errors' => $formattedErrors,
            'suggestions' => [
                'Check that all required fields are filled',
                'Ensure file formats are supported',
                'Verify file sizes are within limits'
            ],
            'timestamp' => now()->toISOString(),
        ];
    }
}
