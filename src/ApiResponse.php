<?php

namespace AthosBenther\LaravelApiResponses;

use Symfony\Component\HttpFoundation\Response;
use Illuminate\Contracts\Support\Arrayable;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class ApiResponse extends Response
{

    private array|Arrayable $meta;



    public function __construct(
        private ?string $message = null,
        private array|Arrayable $data = [],
        public int $statusCode = Response::HTTP_OK,
        private int $encodingOptions = 0,
        array $headers = []
    ) {
        $jsonHeaders = array_merge([
            'Content-type' => 'application/json'
        ], $headers);
        parent::__construct('', $statusCode, $jsonHeaders);
        $this->encodingOptions = $encodingOptions;
    }

    public function message(string $message): ApiResponse
    {

        $this->message = $message;

        return $this;
    }

    public function data(array|Arrayable $data): ApiResponse
    {
        $this->data = $data;

        return $this;
    }

    public function meta(array $meta): ApiResponse
    {
        $this->meta = $meta;

        return $this;
    }

    public function headers(array $headers): ApiResponse
    {

        $jsonHeaders = array_merge([
            'Content-type' => 'application/json'
        ], $headers);

        $this->headers = new ResponseHeaderBag($jsonHeaders);

        return $this;
    }

    public function getStatusMessage(): string
    {
        return Response::$statusTexts[$this->statusCode];
    }

    public function getContent(): string|false
    {
        $response = [
            "message" => $this->message ?? $this->getStatusMessage()
        ];

        if ($this->data) $response['data'] = is_array($this->data) ? $this->data : $this->data->toArray();
        $response['status'] = [
            "code" => $this->statusCode,
            "message" => $this->getStatusMessage()
        ];
        $response['meta'] = array_merge($this->meta ?? [], [
            "timestamp" => date("Y-m-d H:i:s")
        ]);

        return json_encode($response, $this->encodingOptions);
    }
}
