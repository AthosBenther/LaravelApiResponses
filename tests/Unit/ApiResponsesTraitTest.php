<?php

use AthosBenther\LaravelApiResponses\Traits\ApiResponses;
use Tests\TestCase;

describe('ApiResponse', function () {
    $apiResponses = new class {
        use ApiResponses;
    };

    it('can generate a basic Response', function () use ($apiResponses) {
        $message = TestCase::randomString();
        $status = TestCase::getRandomHttpStatus();
        $data = TestCase::randomArray();

        $content = $apiResponses->Response(
            $message,
            $data,
            $status['code']
        )->getContent();

        $result = json_decode($content, true);

        expect($content)->toBeJson();

        expect($result['message'])->toBe($message);
        expect($result['status']['code'])->toBe($status['code']);
        expect($result['status']['message'])->toBe($status['message']);


        expect($result['data'])->toBe($data);
        expect($result['meta']['timestamp'])->toBeString();
    });

    it('can generate a Message Response', function () use ($apiResponses) {
        $message = TestCase::randomString();
        $status = TestCase::getRandomHttpStatus();

        $content = $apiResponses->messageResponse($message, $status['code'])->getContent();
        $result = json_decode($content, true);

        expect($result['message'])->toBe($message);
        expect($result['status']['code'])->toBe($status['code']);
        expect($result['status']['message'])->toBe($status['message']);
    });

    it('can generate a Status Code Response', function () use ($apiResponses) {
        $status = TestCase::getRandomHttpStatus();

        $content = $apiResponses->statusCodeResponse($status['code'])->getContent();
        $result = json_decode($content, true);

        expect($result['message'])->toBe($status['message']);
        expect($result['status']['code'])->toBe($status['code']);
        expect($result['status']['message'])->toBe($status['message']);
    });

    it('can generate an Exception Response', function () use ($apiResponses) {

        $exCode = mt_rand(0, 999);
        $exMessage = TestCase::randomString();
        $exception = new Exception($exMessage, $exCode);
        $status = TestCase::getRandomHttpStatus();

        $content = $apiResponses->exceptionResponse($exception, $status['code'])->getContent();
        $result = json_decode($content, true);

        expect($result['message'])->toBe($exMessage);
        expect($result['status']['code'])->toBe($status['code']);
        expect($result['status']['message'])->toBe($status['message']);


        expect($result['data']['code'])->toBe($exCode);
        expect($result['data']['file'])->toBeReadableFile();
        expect($result['data']['line'])->toBeInt();
    });

    it('can generate a Not Implemented Response', function () use ($apiResponses) {


        $content = $apiResponses->notImplementedResponse()->getContent();
        $result = json_decode($content, true);


        expect($result['message'])->toBe('Not Implemented');
        expect($result['status']['code'])->toBe(501);
        expect($result['status']['message'])->toBe('Not Implemented');


        expect($result['meta']['timestamp'])->toBeString();
    });
});
