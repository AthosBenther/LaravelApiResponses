<?php

namespace Tests\Extras;

use Illuminate\Contracts\Support\Arrayable;

class ArrayableObject implements Arrayable
{
    public function __construct(private array $array) {}
    public function toArray()
    {
        return $this->array;
    }
}
