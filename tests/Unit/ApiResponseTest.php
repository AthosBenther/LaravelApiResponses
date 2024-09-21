<?php

use AthosBenther\LaravelApiResponses\ApiResponse;
use Tests\Extras\ArrayableObject;

describe('ApiResponse', function () {
    $apiResponse = new ApiResponse();

    $expected = [
        "message" => "OK",
        "status" => [
            "code" => 200,
            "message" => "OK"
        ],
        "meta" => []
    ];

    it('Constructs', function () use ($apiResponse, $expected) {
        $content = $apiResponse->getContent();
        $result = json_decode($content, true);
        unset($result['meta']['timestamp']);

        expect($result)->toBe($expected);
    });

    it('sets Message', function () use ($apiResponse) {
        $random = substr(md5(mt_rand()), 0, 7);
        $content = $apiResponse->message($random)->getContent();
        $result = json_decode($content, true);

        expect($result['message'])->toBe($random);
    });

    it('sets Data as Array', function () use ($apiResponse) {
        $data = [];

        for ($i = 0; $i < mt_rand(3, 10); $i++) {
            $data[] = [substr(md5(mt_rand()), 0, 7) => substr(md5(mt_rand()), 0, 7)];
        }

        $content = $apiResponse->data($data)->getContent();
        $result = json_decode($content, true);

        expect($result['data'])->toBe($data);
    });

    it('sets Data as Arrayable', function () use ($apiResponse) {
        $data = [];

        for ($i = 0; $i < mt_rand(3, 10); $i++) {
            $data[] = [substr(md5(mt_rand()), 0, 7) => substr(md5(mt_rand()), 0, 7)];
        }

        $arrayable = new ArrayableObject($data);

        $content = $apiResponse->data($arrayable)->getContent();
        $result = json_decode($content, true);

        expect($result['data'])->toBe($data);
    });

    it('sets Meta', function () use ($apiResponse) {
        $data = [];

        for ($i = 0; $i < mt_rand(3, 10); $i++) {
            $data[] = [substr(md5(mt_rand()), 0, 7) => substr(md5(mt_rand()), 0, 7)];
        }

        $content = $apiResponse->meta($data)->getContent();
        $result = json_decode($content, true);
        unset($result['meta']['timestamp']);


        expect($result['meta'])->toBe($data);
    });
});
