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
        parent::__construct('', $statusCode, $headers);
        $this->encodingOptions = $encodingOptions ?? JSON_PRETTY_PRINT;
        $this->setThisContent();
    }

    public function message(?string $message): ApiResponse
    {
        if (isset($message)) {
            $this->message = $message;
            $this->setThisContent();
        }
        return $this;
    }

    public function data(array|Arrayable $data = null): ApiResponse
    {
        if (is_null($data)) $this->data = null;
        else {
            $this->data = is_array($data) ? $data : $data->toArray();
            $this->setThisContent();
        }
        return $this;
    }

    public function meta(?array $meta): ApiResponse
    {
        if (isset($meta)) {
            $this->meta = $meta;
            $this->setThisContent();
        }
        return $this;
    }

    public function headers(?array $headers = null): ApiResponse
    {
        if ($headers)
            $this->headers = new ResponseHeaderBag($headers);

        return $this;
    }

    public function getStatusMessage(): string
    {
        return Response::$statusTexts[$this->statusCode];
    }

    private function setThisContent()
    {
        $response = [
            "message" => $this->message ?? $this->getStatusMessage()
        ];

        $response['data'] = $this->data == [] ? null :  $this->data;
        $response['status'] = [
            "code" => $this->statusCode,
            "message" => $this->getStatusMessage()
        ];
        $response['meta'] = array_merge($this->meta ?? [], [
            "timestamp" => date("Y-m-d H:i:s")
        ]);

        $this->setContent(json_encode($response, $this->encodingOptions));
    }
}
