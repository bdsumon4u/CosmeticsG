<?php

namespace App\Services\OTP;

use App\Contracts\SendSms;

class Elitbuzz implements SendSms
{

    public function send($to, $from, $text, $template_id)
    {
        $url = "https://msg.elitbuzz-bd.com/smsapi";
        $data = [
            "api_key" => env('ELITBUZZ_API_KEY'),
            "type" => "text",
            "contacts" => $to,
            "senderid" => env('ELITBUZZ_USER_SENDER_ID'),
            "label" => "transactional",
            "msg" => $text,
        ];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }
}
