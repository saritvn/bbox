<?php

namespace Bbox
class Base
{
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
    protected $URL_REGISTER = BBOX_API_URL.'auth/register';
    protected $URL_GET_TOKEN = BBOX_API_URL.'admin/get-token';
    protected $URL_UPGRADE_PLAN = BBOX_API_URL.'payment/upgrade-plan';
    protected $URL_RESEND_ACTIVE = BBOX_API_URL.'user/resend-active';
    /**
     * Post data using CURL.
     *
     * @param string $url
     * @param array postFields
     * @param bool getJson
     *
     * @return array response
     **/
    public function makeCurlPost($url, $postFields, $getJson = true)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        //Set Header
        $header = array("secret_key: {$this->secret_key}");
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
    public function getToken($user_id)
    {
        $tokenInfo = $this->makeCurlPost($this->URL_GET_TOKEN, array('user_id' => $user_id), true);
        if (!empty($tokenInfo)) {
            if ($tokenInfo['error_code'] == '0') {
                $this->token = $tokenInfo['data']['token'];

                return $tokenInfo['data']['token'];
            }
        }

        return false;
    }
}
