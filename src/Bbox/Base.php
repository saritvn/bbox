<?php

namespace Bstar\Bbox;
class Base
{
    const BBOX_API_URL = "https://app.bbox.vn/api/";
    /**
     *
     **/
    protected $secret = '';
    protected $client_id = '';
    protected $token = '';
    /**
     * Register URL.
     *
     * @var string
     */
    protected $URL_WEB_SERVICE = self::BBOX_API_URL.'web-service/get-token/';
    protected $URL_LIST_FILE = self::BBOX_API_URL.'bbox/';
    /**
    * Create contrstructor
    * @param $client_id
    * @param $secret
    **/
    public function __construct($client_id,$secret)
    {
        $this->client_id = $client_id;
        $this->secret = $secret;
        $this->getToken();
    }

    /**
     * Post data using CURL.
     *
     * @param string $url
     * @param array postFields
     * @param Boolean $isPost: CURL Method
     * @param Boolean getJson
     * @param bool getJson
     *
     * @return array response
     **/
    public function makeCurlPost($url, $postFields = [], $isPost = true, $getJson = true)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        if($isPost){
            curl_setopt($ch, CURLOPT_POST, true);
        }else{
            curl_setopt($ch, CURLOPT_POST, false);
            curl_setopt($ch, CURLOPT_HTTPGET, true);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        }
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        //Set Header
        $header = ["secret: {$this->secret}",
                   "client_id: {$this->client_id}"];
        //Set header Authorization if have.
        if (!empty($this->token)) {
            $header[] = "Authorization: Bearer {$this->token}";
        }
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

        $body = curl_exec($ch);
        curl_close($ch);
        if (!$getJson) {
            return $body;
        }
        $json = @json_decode($body, true);
        if (empty($json)) {
            die('Unexpected response from server: '.$body);
        }

        return $json;
    }

    /**
     * Get Token User by User_ID.
     *
     * @param string user_id
     *
     * @return string $token
     **/
    public function getToken()
    {
        $tokenInfo = $this->makeCurlPost($this->URL_WEB_SERVICE);
        if (!empty($tokenInfo)) {
            if ($tokenInfo['error_code'] == '0') {
                $this->token = $tokenInfo['data']['token'];

                return $tokenInfo['data']['token'];
            }
        }

        return false;
    }
}
