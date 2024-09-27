<?php

namespace AthosBenther\LaravelApiResponses;

use AthosBenther\LaravelApiResponses\Contracts\Arrayable;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class ApiResponse extends Response implements Arrayable
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
        return json_encode($this->toArray(), $this->encodingOptions);
    }

    /**
     * Sends content for the current web response.
     *
     * @return $this
     */
    public function sendContent(): static
    {
        echo $this->getContent();

        return $this;
    }

    public function toArray(): array
    {
        $array = [
            "message" => $this->message ?? $this->getStatusMessage()
        ];

        if ($this->data) $array['data'] = is_array($this->data) ? $this->data : $this->data->toArray();
        $array['status'] = [
            "code" => $this->statusCode,
            "message" => $this->getStatusMessage()
        ];
        $array['meta'] = array_merge($this->meta ?? [], [
            "timestamp" => date("Y-m-d H:i:s")
        ]);

        return $array;
    }
}
