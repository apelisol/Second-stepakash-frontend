<?php

class Operations extends CI_Model
{
    public function CurlPost($url, $body)
    {
        $c = curl_init();

        curl_setopt($c, CURLOPT_URL, $url);
        curl_setopt($c, CURLOPT_POST, true);
        curl_setopt($c, CURLOPT_POSTFIELDS, $body);
        curl_setopt($c, CURLOPT_RETURNTRANSFER, true);

        $page = curl_exec($c);

        if (curl_errno($c)) {
            $error_msg = curl_error($c);
            echo $error_msg;
        }

        curl_close($c);
        return $page;
    }

    public function CurlFetch($url)
    {
        $c = curl_init();

        curl_setopt($c, CURLOPT_URL, $url);
        curl_setopt($c, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($c);

        if (curl_errno($c)) {
            $error_msg = curl_error($c);
            echo $error_msg;
        }

        curl_close($c);
        return $response;
    }

    public function Password_Generator($length)
    {
        $string = "";
        $chars = "abcdefghijklmanopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $size = strlen($chars);

        for ($i = 0; $i < $length; $i++) {
            $string .= $chars[rand(0, $size - 1)];
        }

        return $string;
    }

    public function Generator($length)
    {
        $string = "";
        $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $size = strlen($chars);

        for ($i = 0; $i < $length; $i++) {
            $string .= $chars[rand(0, $size - 1)];
        }

        return $string;
    }

    public function OTP($length)
    {
        $string = "";
        $chars = "0123456789";
        $size = strlen($chars);

        for ($i = 0; $i < $length; $i++) {
            $string .= $chars[rand(0, $size - 1)];
        }

        return $string;
    }

    public function DEPOTP($length)
    {
        $string = "";
        $chars = "123456789";
        $size = strlen($chars);

        for ($i = 0; $i < $length; $i++) {
            $string .= $chars[rand(0, $size - 1)];
        }

        return $string;
    }

    public function get_receipt()
    {
        $url = APP_INSTANCE . 'next_receipt';
        $response = $this->CurlFetch($url);
        $decode = json_decode($response, true);

        $status = $decode['status'];
        $message = $decode['message'];
        $data = $decode['data'];

        return $data;
    }
}
