<?php

namespace Ecology;

use Illuminate\Support\Facades\Cache;

class OaClient extends BaseClient
{
    protected $baseUri;
    protected $appId;
    protected $token;
    protected $timeout;
    protected $userid;

    protected function init(): void
    {
        $this->baseUri = config('oa.uri');
        $this->appId = config('oa.appid');
        $this->timeout = config('oa.timeout', 86400);
        $this->userid = config('oa.userid', '600');
        $this->accessToken = $this->getToken();
    }

    protected function getToken()
    {
        $token = "";
        if (Cache::has('accessToken')) {
            //存在缓存
            $token = Cache::get('accessToken');
        } else {
            $reg = $this->regist();
            $rsa = new Rsa('', '', '', $reg->spk);
            $secret = $rsa->publicEncrypt($reg->secret);
            $headers = [
                'appid' => $this->appId,
                'secret' => $secret,
                'time' => $this->timeout,
            ];
            $url = 'api/ec/dev/auth/applytoken';
            $response = $this->request($url, 'POST', ['headers' => $headers]);
            $body = \json_decode($response->getBody()->getContents());

            if ($body->code != 0) {
                throw new \Exception($body->msg);
            }
            $token = $body->token;
            \Cache::put('token', $token, $this->timeout);
        }

        return $token;
    }

    protected function regist()
    {
        $reg = [];
        if (Cache::has('reg')) {
            //存在缓存
            $reg = Cache::get('reg');
        } else {
            $headers = [
                'appid' => $this->appId,
                'cpk' => '123',
            ];
            $url = 'api/ec/dev/auth/regist';
            $response = $this->request($url, 'POST', ['headers' => $headers]);
            $reg = \json_decode($response->getBody()->getContents());

            if ($reg->code != 0) {
                throw new \Exception($reg->msg, $reg->errcode);
            }
            \Cache::put('reg', $reg, now()->addDays(30));
        }
        return $reg;
    }

    public function setHeader(array $header = [])
    {
        $reg = Cache::get('reg');
        $rsa = new Rsa('', '', '', $reg->spk);
        $userid = $rsa->publicEncrypt($this->userid);

        $arr = [
            'appid' => $this->appId,
            'token' => $this->accessToken,
            'userid' => $userid,
            // 'Content-Type' => 'application/x-www-form-urlencoded;charset=utf-8',
        ];
        return array_merge($arr, $header);
    }

}
