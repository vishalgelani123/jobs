<?php

namespace App\Helpers;

class GenerateStringNumberHelper
{
    static function generateTimeRandomString($length = 10)
    {
        $characters = time() . '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'.rand();
        $randomString = '';

        for ($i = 0; $i < $length; $i++) {
            $randomIndex = rand(0, strlen($characters) - 1);
            $randomString .= $characters[$randomIndex];
        }

        return $randomString;
    }
}
