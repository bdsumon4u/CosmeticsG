<?php

namespace App\Pathao\Apis;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Storage;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\ClientException;
use App\Pathao\Exceptions\PathaoException;
use App\Pathao\Exceptions\PathaoCourierValidationException;

class BaseApi
{
    /**
     * @var string
     */
    private $baseUrl;

    /**
     * @var Client
     */
    private $request;

    /**
     * @var array
     */
    private $headers;

    public function __construct()
    {
        $this->setBaseUrl();
        $this->setHeaders();
        $this->request = new Client([
            'base_uri' => $this->baseUrl,
            'headers'  => $this->headers
        ]);
    }

    /**
     * Set Base Url on sandbox mode
     */
    private function setBaseUrl()
    {
        if (config("pathao.sandbox") == true) {
            $this->baseUrl = "https://hermes-api.p-stageenv.xyz";
        } else {
            $this->baseUrl = "https://api-hermes.pathaointernal.com";
        }
    }

    /**
     * Set Default Headers
     */
    private function setHeaders()
    {
        $this->headers = [
            "Accept"       => "application/json",
            "Content-Type" => "application/json",
        ];
    }

    /**
     * Merge Headers
     *
     * @param array $header
     */
    private function mergeHeader($header)
    {
        $this->headers = array_merge($this->headers, $header);
    }

    /**
     * set authentication token
     *
     * @throws PathaoException|GuzzleException
     */
    private function authenticate()
    {
        try {
            $jsonToken = json_decode(file_get_contents(storage_path('app/public/pathao_courier_token.json')), true);
            $response = $this->send("POST", "aladdin/api/v1/issue-token", array_merge($jsonToken, [
                "username"   => "tareq12489@gmail.com", # config("pathao.username"),
                "password"   => config("pathao.password"),
                "grant_type" => "password",
            ]));

            $accessToken = [
                "token"      => "Bearer " . $response->access_token,
                "expires_in" => time() + $response->expires_in
            ];

            file_put_contents(storage_path('app/public/pathao_bearer_token.json'), json_encode($accessToken));

        } catch (ClientException $e) {
            $response = json_decode($e->getResponse()->getBody()->getContents());
            throw new PathaoException($response->message, $response->code);
        }
    }

    /**
     * Authorization set to header
     *
     * @return $this
     * @throws PathaoException|GuzzleException
     */
    public function authorization()
    {
        $storageExits = file_exists(storage_path('app/public/pathao_bearer_token.json'));

        if (!$storageExits) {
            $this->authenticate();
        }

        $jsonToken = file_get_contents(storage_path('app/public/pathao_bearer_token.json'));
        $jsonToken = json_decode($jsonToken);

        if ($jsonToken->expires_in < time()) {
            $this->authenticate();
            $jsonToken = file_get_contents(storage_path('app/public/pathao_bearer_token.json'));
            $jsonToken = json_decode($jsonToken);
        }

        $this->mergeHeader([
            'Authorization' => $jsonToken->token
        ]);

        return $this;
    }

    /**
     * Sending Request
     *
     * @param string $method
     * @param string $uri
     * @param array $body
     *
     * @return mixed
     * @throws GuzzleException
     * @throws PathaoException
     */
    public function send($method, $uri, $body = [])
    {
        try {
            $response = $this->request->request($method, $uri, [
                "headers" => $this->headers,
                "body"    => json_encode($body)
            ]);
            return json_decode($response->getBody());
        } catch (ClientException $e) {
            if ($e->getCode() == 401) {
                $message = "Unauthorized";
                $errors  = [];
            } else {
                $response = json_decode($e->getResponse()->getBody()->getContents());
                $message  = $response->message;
                $errors   = isset($response->errors) ? $response->errors : [];
            }
            throw new PathaoException($message, $e->getCode(), $errors);
        }
    }

    /**
     * Data Validation
     *
     * @param array $data
     * @param array $requiredFields
     *
     * @throws PathaoCourierValidationException
     */
    public function validation($data, $requiredFields)
    {
        if (!is_array($data) || !is_array($requiredFields)) {
            throw new \TypeError("Argument must be of the type array", 500);
        }

        if (!count($data) || !count($requiredFields)) {
            throw new PathaoCourierValidationException("Invalid data!", 422);
        }

        $requiredColumns = array_diff($requiredFields, array_keys($data));
        if (count($requiredColumns)) {
            throw new PathaoCourierValidationException($requiredColumns, 422);
        }

        foreach ($requiredFields as $filed) {
            if (isset($data[$filed]) && empty($data[$filed])) {
                throw new PathaoCourierValidationException("$filed is required", 422);
            }
        }

    }

}
