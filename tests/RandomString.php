<?php

namespace App\Tests;

class RandomString
{
    public function createString($length)
    {
        $char = '123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $string = '';

        for ($i = 0; $i < $length+1; $i++) {
            $offset = rand(0, 60);
            $string = $string . substr($char, $offset, 1);
        }
        return $string;
    }
}

