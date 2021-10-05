<?php

namespace Orglinker;

/**
 * Class Orglinker
 * @package Orglinker
 */
class Orglinker
{

    public $email;
    public $password;
    public $domain_name;

    /**
     * Orglinker constructor.
     * @param $config
     */
    function __construct($config) {
        $this->email       = $config['email'];
        $this->password    = $config['password'];
        $this->domain_name = $config['domain_name'];
    }

    public function getCredentials($prepared_data)
    {
        $data = [
            'email' => $this->email,
            'password' => $this->password,
            'url' => 'https://' . $this->domain_name . '/api/external-integration/' . $prepared_data['table'],
        ];
        return $data;
    }

    /**
     * @param $prepared_data
     * @return bool|string
     */
    public function getItems($prepared_data)
    {
        $data = $this->getCredentials($prepared_data);

        if(isset($prepared_data['conditions'])){
            $data['conditions'] = $prepared_data['conditions'];
        }

        return $this->sendRequest($data, 'GET');
    }

    /**
     * @param $prepared_data
     * @return bool|string
     * @throws \Exception
     */
    public function createItems($prepared_data)
    {
        $data = $this->getCredentials($prepared_data);

        if(!isset($prepared_data['objects'])){
            throw new \Exception('Need to pass objects!', 422);
        }else{
            $data['objects'] = $prepared_data['objects'];
        }

        return $this->sendRequest($data, 'POST');
    }

    /**
     * @param $prepared_data
     * @return bool|string
     * @throws \Exception
     */
    public function updateItems($prepared_data)
    {
        $data = $this->getCredentials($prepared_data);

        if(!isset($prepared_data['objects'])){
            throw new \Exception('Need to pass objects!', 422);
        }else{
            $data['objects'] = $prepared_data['objects'];
        }

        if(isset($prepared_data['move'])){
            $data['move'] = $prepared_data['move'];
        }

        return $this->sendRequest($data, 'PUT');
    }

    /**
     * @param $prepared_data
     * @return bool|string
     * @throws \Exception
     */
    public function deleteItems($prepared_data)
    {
        $data = $this->getCredentials($prepared_data);

        if(!isset($prepared_data['ids'])){
            throw new \Exception('Need to pass ids parameter!', 422);
        }else{
            $data['ids'] = $prepared_data['ids'];
        }

        return $this->sendRequest($data, 'DELETE');
    }

    /**
     * @param $data
     * @param $method
     * @return bool|string
     */
    public function sendRequest($data, $method)
    {
        // Initializes a new cURL session
        $curl = curl_init($data['url']);
        // Set the CURLOPT_RETURNTRANSFER option to true
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        // Set the CURLOPT_POST option to true for POST request
        //curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_HTTPGET, true);
        // Set the request data as JSON using json_encode function
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);

        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
        // Set custom headers for RapidAPI Auth and Content-Type header
        curl_setopt($curl, CURLOPT_HTTPHEADER, [
            'X-RapidAPI-Host: kvstore.p.rapidapi.com',
            'X-RapidAPI-Key: 7xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx',
            'Content-Type: application/json'
        ]);
        // Execute cURL request with all previous settings
        $response = curl_exec($curl);
        // Close cURL session
        curl_close($curl);
        return $response;
    }

}
