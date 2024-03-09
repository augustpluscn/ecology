<?php

namespace Ecology;

class OaMessage
{
    protected $http;

    public function __construct()
    {
        $this->http = new OaClient();
    }

    public function sendMsg($workCodeList, $title, $content, $code = null, $linkUrl = null)
    {
        // $body = [
        //     'code' => 518,
        //     'workCodeList' => 'C0879',
        //     'title' => '测试标题',
        //     'context' => '测试内容',
        //     'linkUrl' => 'https://www.baidu.com/',
        // ];
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
