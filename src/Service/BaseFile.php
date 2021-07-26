<?php
namespace Wdy\AliService\Service;

class BaseFile {

    /**
     * sign加密
     * @param array $data 请求传输的数据
     * @return string 返回加密结果
     */
    protected function makeSign($data = array())
    {
        if (empty($data)) {
            return '';
        }

        ksort($data);
        $a = [];
        foreach ($data as $kReq => $vReq) {
            $a[] = $kReq.'='.$vReq;
        }
        $reqStr   = implode('&', $a);
        $arrToStr = $reqStr.'&key='. $this->config['secret_key'];
        return strtoupper(hash_hmac('sha256', $arrToStr, $this->config['secret_key']));
    }
}