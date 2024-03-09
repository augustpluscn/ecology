<?php

namespace Jmcc\Ecology;

class OaMessage
{
    protected $http;

    public function __construct()
    {
        $this->http = new OaClient();
    }

    public function sendMsg($workCodeList, $title, $content, $code = null, $linkUrl = null)
    {
        $body = [
            'code' => $code,
            'workCodeList' => $workCodeList,
            'title' => $title,
            'context' => $content,
            'linkUrl' => $linkUrl,
        ];
        return $this->http->httpPost('api/ec/dev/message/sendCustomMessageSingle', $body);
    }

}
