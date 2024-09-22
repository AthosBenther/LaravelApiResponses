<?php

namespace Tests;

use PHPUnit\Framework\TestCase as BaseTestCase;
use Symfony\Component\HttpFoundation\Response;

abstract class TestCase extends BaseTestCase
{
    static function randomString(int $lenght = 7)
    {
        return substr(md5(mt_rand()), 0, $lenght);
    }

    static function randomArray(
        int $arrayMinLenght = 3,
        int $arrayMaxLenght = 15,
        int $stringLenght = 7,
        string $keyPrefix = "Key_",
        string $valuePrefix = "Value_"
    ) {
        $result = [];
        for ($i = 0; $i < mt_rand($arrayMinLenght, $arrayMaxLenght); $i++) {
            $result[$keyPrefix . TestCase::randomString()] = $valuePrefix . TestCase::randomString();
        }
        return $result;
    }

    static function getRandomKeyValue(array $array)
    {
        if (empty($array)) {
            return null; // Return null if the array is empty
        }

        $randomKey = array_rand($array);
        return [$randomKey => $array[$randomKey]];
    }

    static function getRandomHttpStatus()
    {
        $rand = TestCase::getRandomKeyValue(Response::$statusTexts);

        return [
            'code' => array_keys($rand)[0],
            'message' => $rand[array_keys($rand)[0]]
        ];
    }
}
