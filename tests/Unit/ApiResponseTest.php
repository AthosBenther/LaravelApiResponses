<?php

use AthosBenther\LaravelApiResponses\ApiResponse;
use Illuminate\Contracts\Support\Arrayable;
use Tests\TestCase;


beforeEach(function () {
    $this->message = TestCase::randomString();
    $this->status = TestCase::getRandomHttpStatus();
    $this->data = TestCase::randomArray();

    $this->apiResponse = new ApiResponse($this->message, $this->data, $this->status['code']);
});

describe('ApiResponse', function () {
    it('Constructs', function () {
        $content = $this->apiResponse->getContent();
        $result = json_decode($content, true);

        expect($content)->toBeJson();

        expect($result['message'])->toBe($this->message);
        expect($result['status']['code'])->toBe($this->status['code']);
        expect($result['status']['message'])->toBe($this->status['message']);


        expect($result['data'])->toBe($this->data);
        expect($result['meta']['timestamp'])->toBeString();
    });

    it('sets forces Json response', function () {
        $randomMessage = TestCase::randomString();
        $headers = $this->apiResponse->headers->all();

        expect($headers)->toMatchArray([
            "content-type" => [
                "application/json"
            ]
        ]);
    });

    it('sets Message', function () {
        $randomMessage = TestCase::randomString();
        $content = $this->apiResponse->message($randomMessage)->getContent();
        $result = json_decode($content, true);

        expect($result['message'])->toBe($randomMessage);
    });

    it('sets Data as Array', function () {
        $data = TestCase::randomArray();

        $content = $this->apiResponse->data($data)->getContent();
        $result = json_decode($content, true);

        expect($result['data'])->toBe($data);
    });

    it('sets Data as Arrayable', function () {
        $data = TestCase::randomArray();

        $arrayable = new class implements Arrayable {
            public array $data;
            public function toArray(): array
            {
                return $this->data;
            }
        };

        $arrayable->data = $data;

        $content = $this->apiResponse->data($arrayable)->getContent();
        $result = json_decode($content, true);

        expect($result['data'])->toBe($data);
    });

    it('sets Meta', function () {
        $data = TestCase::randomArray();

        $content = $this->apiResponse->meta($data)->getContent();
        $result = json_decode($content, true);

        expect($result['meta'])->toMatchArray($data);
    });

    it('sets Header', function () {
        $data = TestCase::randomArray(3, 15, 3, 'key-', "");
        $expectedData = array_map(fn($v) => [$v], $data);
        $this->apiResponse->headers($data);

        $headers = $this->apiResponse->headers->all();

        expect($headers)->toMatchArray($expectedData);
    });
});
