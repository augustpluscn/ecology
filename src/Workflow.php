<?php

namespace Jmcc\Ecology;

class Workflow
{
    protected $http;

    public function __construct($userid = null)
    {
        $this->http = new OaClient($userid);
    }

    public function create(int $workflowId, string $requestName, string $mainData, string $detailData = null, string $otherParams = null, string $remark = null, string $requestLevel = null)
    {
        $body = [
            'workflowId' => $workflowId,
            'requestName' => $requestName,
            'mainData' => $mainData,
            'detailData' => $detailData,
            'otherParams' => $otherParams,
            'remark' => $remark,
            'requestLevel' => $requestLevel,
        ];

        return $this->http->httpPost('api/workflow/paService/doCreateRequest', $body);

    }

}
