<?php

namespace App\Helpers;

class OTPHelper
{
    public static function generateOTP($length = 4)
    {
        $min = pow(10, $length - 1);
        $max = pow(10, $length) - 1;

        return random_int($min, $max);
    }
}
