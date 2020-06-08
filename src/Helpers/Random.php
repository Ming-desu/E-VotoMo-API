<?php

namespace EVotoMo\Helpers;

class Random {
    public static function Generate(int $length = 8) : String {
        $characters = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
        return substr(str_shuffle(str_repeat($characters, ceil($length/strlen($characters)))), 1, $length);
    }
}