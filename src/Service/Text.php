<?php


namespace Wdy\AliService\Service;


use Wdy\AliService\Support\Config;
use Green\Request\V20180509 as Green;

class Text
{

    protected $config;

    public function __construct($config)
    {
        $this->config = new Config($config);
    }

    /**
     * text identify
     *
     * @author Logan
     *
     * @param string $text 'text'
     *
     * @return array
     */
    public function textIdentify($text = '')
    {
        $iClientProfile = \DefaultProfile::getProfile(
            $this->config->get('region_id'),
            $this->config->get('access_key_id'),
            $this->config->get('access_key_secret')
        );
        \DefaultProfile::addEndpoint(
            $this->config->get('regionid'),
            $this->config->get('regionid'),
            "Green",
            "green.cn-shanghai.aliyuncs.com"
        );
        $client  = new \DefaultAcsClient($iClientProfile);
        $request = new Green\TextScanRequest();
        $request->setMethod("POST");
        $request->setAcceptFormat("JSON");
        $task1 = array('dataId' => uniqid(),
            'content'               => $text,
        );
        $request->setContent(json_encode(array("tasks" => array($task1),
            "scenes"                                       => array("antispam"))));
        try {
            $response = $client->getAcsResponse($request);
            if (200 == $response->code) {
                $taskResults = $response->data;
                foreach ($taskResults as $taskResult) {
                    if (200 == $taskResult->code) {
                        $sceneResults = $taskResult->results;
                        foreach ($sceneResults as $sceneResult) {
                            $label      = $sceneResult->label;
                            $suggestion = $sceneResult->suggestion;
                            if ($label != "normal" || $suggestion != "pass") {
                                $detail  = $sceneResult->details;
                                $context = $detail[0]->label;
                                return ["status" => false, "content" => $context, 'code' => $taskResult->code]; //文本不合法
                            }
                            return ["status" => true, "content" => $taskResult->content, 'code' => $taskResult->code]; //文本正常
                        }
                    } else {
                        return ["status" => false, "content" => '', 'code' => $taskResult->code];
                    }
                }
            } else {
                return ["status" => false, "content" => '', 'code' => $response->code];
            }
        } catch (\Exception $e) {
            return ["status" => false, "content" => '', 'code' => 500];
        }
    }

}