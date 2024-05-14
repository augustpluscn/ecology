<?php

namespace Jmcc\Ecology;

class Workflow
{
    protected $http;

    public function __construct()
    {
        $this->http = new OaClient();
    }

    public function create(int $workflowId, string $requestName, array $mainData, array $detailData = null, array $otherParams = null, string $remark = null, string $requestLevel = null)
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
