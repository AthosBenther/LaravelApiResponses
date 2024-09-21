<?php

namespace AthosBenther\LaravelApiResponses\Traits;

use AthosBenther\LaravelApiResponses\ApiResponse;
use Exception;
use Illuminate\Contracts\Support\Arrayable;
use Symfony\Component\HttpFoundation\Response;

trait ApiResponses
{

    /**
     * Generates a success response.
     *
     * @param int $statusCode The HTTP status code.
     * @param string|null $message The response message. If null, default message for the given status code will be used.
     * @param array|null $data Additional data to include in the response.
     * @param int $jsonEncodingFlags Response JSON Encoding flags
     * @return ApiResponse The HTTP response.
     */
    public function Response(
        string $message = null,
        array|Arrayable $data = null,
        int $statusCode = Response::HTTP_OK,
        int $jsonEncodingFlags = 0
    ): ApiResponse {
        return new ApiResponse(
            $message,
            $data,
            $statusCode,
            $jsonEncodingFlags
        );
    }

    /**
     * Generates a success response.
     *
     * @param int $statusCode The HTTP status code.
     * @param string|null $message The response message. If null, default message for the given status code will be used.
     * @param array|null $data Additional data to include in the response.
     * @param int $jsonEncodingFlags Response JSON Encoding flags
     * @return ApiResponse The HTTP response.
     */
    public function MessageResponse(
        string $message = null,
        int $statusCode = Response::HTTP_OK,
        int $jsonEncodingFlags = 0
    ): ApiResponse {
        return new ApiResponse(
            $message,
            [],
            $statusCode,
            $jsonEncodingFlags
        );
    }

    /**
     * Generates a success response.
     *
     * @param int $statusCode The HTTP status code.
     * @param string|null $message The response message. If null, default message for the given status code will be used.
     * @param array|null $data Additional data to include in the response.
     * @param int $jsonEncodingFlags Response JSON Encoding flags
     * @return ApiResponse The HTTP response.
     */
    public function StatusCodeResponse(
        int $statusCode = Response::HTTP_OK,
        int $jsonEncodingFlags = 0
    ): ApiResponse {
        return new ApiResponse(
            null,
            [],
            $statusCode,
            $jsonEncodingFlags
        );
    }

    /**
     * Generates a success response.
     *
     * @param Exception $exception The exception.
     * @param int $statusCode The HTTP status code.
     * @param bool $trace Indicates if the response should contain the stack trace or not.
     * @param int $jsonEncodingFlags Response JSON Encoding flags
     *
     * @return ApiResponse The HTTP response.
     */
    public function ExceptionResponse(
        Exception $exception,
        int $statusCode = Response::HTTP_INTERNAL_SERVER_ERROR,
        bool $trace = false,
        int $jsonEncodingFlags = 0
    ): ApiResponse {
        $data =
            [
                'code' => $exception->getCode(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
                'code' => $exception->getCode(),
            ];

        if ($exception->getPrevious()) $data['previous'] = $exception->getPrevious()->getMessage();

        if ($trace) $data['trace'] = $exception->getTrace();

        return new ApiResponse(
            $exception->getMessage(),
            $data,
            $statusCode,
            $jsonEncodingFlags
        );
    }

    /**
     * Generates a response for a not implemented feature.
     *
     * @return ApiResponse The HTTP response with status code 501.
     */
    public function NotImplemented(): ApiResponse
    {
        return $this->Response(null, null, 501);
    }
}
