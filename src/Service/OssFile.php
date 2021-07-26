<?php
namespace Wdy\AliService\Service;


use OSS\OssClient;
use Wdy\AliService\Support\Config;
use Wdy\AliService\Support\Transfer;

class OssFile extends BaseFile
{
    protected $gateway_create_upload_video = '/openapi/media/create_upload_file';

    protected $gateway_create_sts_token = '/openapi/media/create_sts_token';

    protected $gateway_scan_image = '/openapi/media/scan_image';

    protected $config = [

    ];

    public function __construct($config)
    {
        $this->config = new Config($config);
    }

    /**
     * @param $param
     * @return bool
     * @throws \OSS\Core\OssException
     */
    public function uploadFile($param)
    {
        $stsInfo = $this->getSTSToken();

        $accessKeyId     = $stsInfo['access_key_id'];
        $accessKeySecret = $stsInfo['access_key_secret'];
        $endpoint        = $stsInfo['endpoint'];
        $securityToken   = $stsInfo['security_token'];

        //解析文件
        $fileContent = $param['file'];
        $fileNames = explode('.', $fileContent['name']);
        $end       = array_pop($fileNames);

        $bucket   = $stsInfo['bucket'];
        $object   = $this->config['app_id'].$param['path'].md5(time().'_'.$fileContent['name']).'.'.$end;
        $filePath = $fileContent['tmp_name'];

        $ossClient = new OssClient($accessKeyId, $accessKeySecret, $endpoint, false, $securityToken);
        $ossResult = $ossClient->uploadFile($bucket, $object, $filePath);

        if (!isset($ossResult['info']['url'])) {
            return false;
        }

        return $ossResult['info']['url'];
    }

    public function getUploadToken()
    {
        $sendRequest = [
            'app_id'     => $this->config->get('app_id'),
        ];
        $sendRequest['sign'] = $this->makeSign($sendRequest);

        //请求地址
        $url = $this->config->get('uri') . $this->gateway_create_upload_video;

        $transfer = new Transfer();
        return $transfer->sendRequest($url, $sendRequest, 'POST');
    }

    public function getStsToken()
    {
        $sendRequest = [
            'app_id' => $this->config->get('app_id'),
        ];
        $sendRequest['sign'] = $this->makeSign($sendRequest);

        //获取STSToken
        $url = $this->config->get('uri') . $this->gateway_create_sts_token;

        $transfer = new Transfer();
        $stsResult = $transfer->sendRequest($url, $sendRequest, 'POST');

        return $stsResult['data']['sts_info'];
    }

    /**
     * scan_image
     *
     * @param array $param
     *
     * @return boolean
     */
    public function scanImage(array $param = []){
        $sendRequest = [
            'app_id'     => $this->config->get('app_id'),
            'url'        => $param['url'],
        ];
        $sendRequest['sign'] = $this->makeSign($sendRequest);

        //请求地址
        $url = $this->config->get('uri') . $this->gateway_scan_image;

        $trans = new Transfer();
        return $trans->sendRequest($url, $sendRequest, 'POST');
    }
}