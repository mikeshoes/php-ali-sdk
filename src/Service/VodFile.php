<?php


namespace Wdy\AliService\Service;


use Wdy\AliService\Support\Config;
use Wdy\AliService\Support\Transfer;
use vod\Request\V20170321\GetPlayInfoRequest;
use vod\Request\V20170321\GetVideoPlayAuthRequest;

class VodFile extends BaseFile
{
    protected  $config;

    /**
     * api
     */
    protected $gateway_create_upload_video = '/openapi/media/create_upload_video';

    protected $gateway_refresh_upload_video = '/openapi/media/refresh_upload_video';

    protected $gateway_query_media_info_by_video_id = '/openapi/media/query_media_info_by_video_id';

    protected $gateway_get_play_info = '/openapi/media/get_play_info';

    protected $gateway_get_play_auth = '/openapi/media/get_play_auth';

    protected $gateway_register_media = '/openapi/media/register_media';

    protected $gateway_submit_audit_media = '/openapi/media/submit_audit_media';

    protected $gateway_query_audit_media = '/openapi/media/query_audit_media';

    public function __construct($config)
    {
        $this->config = new Config($config);
    }


    /**
     * backend video upload
     *
     * @author Logan
     *
     * @param array $param
     *
     * @throws
     * @return boolean
     */
    public function backendVideoUpload(array $param = [])
    {
        date_default_timezone_set('PRC');

        $uploader = new \AliyunVodUploader($this->config->get('access_key_id'), $this->user_config->get('access_key_secret'));
        $uploadVideoRequest = new \UploadVideoRequest($param['file_path'], $param['title']);
        $uploadVideoRequest->setTemplateGroupId($this->config->get('tmp_id'));
        $videoId =  $uploader->uploadLocalVideo($uploadVideoRequest);

        //同步至视频服务
        $sendRequest = [
            'app_id'     => $this->config->get('app_id'),
            'video_id'  => $videoId,
        ];
        $sendRequest['sign'] = $this->makeSign($sendRequest);

        //请求地址
        $url = $this->config->get('uri') . $this->gateway_register_media;

        $trans = new Transfer();
        $result = $trans->sendRequest($url, $sendRequest, 'POST');
        if ($result['status'] != 200) {
            return false;
        }
        return $videoId;
    }

    /**
     * backend get video play info
     *
     * @author Logan
     *
     * @param array $param
     *
     * @return array
     */
    public function backendGetPlayInfo(array $param = []){
        try{
            $profile = \DefaultProfile::getProfile($this->config->get('region_id'), $this->config->get('access_key_id'), $this->config->get('access_key_secret'));
            $client = new \DefaultAcsClient($profile);

            $request = new GetPlayInfoRequest();
            $request->setVideoId($param['video_id']);
            $request->setAuthTimeout(3600*24);
            $request->setAcceptFormat('JSON');
            $request->setOutputType('cnd');
            $res = $client->getAcsResponse($request);

            $result = [
                'Status' => $res->VideoBase->status,
                'playList' => $res->PlayInfoList->PlayInfo,
            ];
        }catch (\Exception $e){
            $result = [
                'Status' => 'UploadFail',
                'playList' => [],
            ];
        }

        return $result;
    }

    /**
     * backend get video play auth
     *
     * @author Logan
     *
     * @param array $param
     *
     * @return array
     */
    public function backendGetPlayAuth(array $param = []){
        try{
            $profile = \DefaultProfile::getProfile($this->config->get('region_id'), $this->config->get('access_key_id'), $this->config->get('access_key_secret'));
            $client = new \DefaultAcsClient($profile);

            $request = new GetVideoPlayAuthRequest();
            $request->setVideoId($param['video_id']);
            $request->setAuthInfoTimeout(3600*24);
            $request->setAcceptFormat('JSON');
            $res = $client->getAcsResponse($request);

            $result = [
                'PlayAuth' => $res->PlayAuth,
            ];
        }catch (\Exception $e){
            $result = [
                'PlayAuth' => '',
            ];
        }
        return $result;
    }

    //===============================================================================================================//


    /**
     * create upload video
     *
     * @author Logan
     *
     * @param array $param
     *
     * @return boolean
     */
    public function createUploadVideo(array $param = [])
    {
        $sendRequest = [
            'app_id'     => $this->config->get('app_id'),
            'file_name'  => $param['file_name'],
        ];
        $sendRequest['sign'] = $this->makeSign($sendRequest);

        //请求地址
        $url = $this->config->get('uri') . $this->gateway_create_upload_video;

        $trans = new Transfer();
        return $trans->sendRequest($url, $sendRequest, 'POST');
    }

    /**
     * refresh upload video
     *
     * @author Logan
     *
     * @param array $param
     *
     * @return boolean
     */
    public function refreshUploadVideo(array $param = [])
    {
        $sendRequest = [
            'app_id'     => $this->config->get('app_id'),
            'video_id' => $param['video_id'],
        ];
        $sendRequest['sign'] = $this->makeSign($sendRequest);

        //请求地址
        $url = $this->config->get('uri') . $this->gateway_refresh_upload_video;

        $trans = new Transfer();
        return $trans->sendRequest($url, $sendRequest, 'POST');
    }

    /**
     * query_media_info_by_video_id
     *
     * @author Logan
     *
     * @param array $param
     *
     * @return boolean
     */
    public function queryMediaInfoByVideoId(array $param = [])
    {
        $sendRequest = [
            'app_id'     => $this->config->get('app_id'),
            'video_id' => $param['video_id'],
        ];
        $sendRequest['sign'] = $this->makeSign($sendRequest);

        //请求地址
        $url = $this->config->get('uri') . $this->gateway_query_media_info_by_video_id;

        $trans = new Transfer();
        return $trans->sendRequest($url, $sendRequest, 'POST');
    }

    /**
     * get_play_info
     *
     * @author Logan
     *
     * @param array $param
     *
     * @return boolean
     */
    public function getPlayInfo(array $param = [])
    {
        $sendRequest = [
            'app_id'     => $this->config->get('app_id'),
            'video_id' => $param['video_id'],
        ];
        $sendRequest['sign'] = $this->makeSign($sendRequest);

        //请求地址
        $url = $this->config->get('uri') . $this->gateway_get_play_info;

        $trans = new Transfer();
        return $trans->sendRequest($url, $sendRequest, 'POST');
    }

    /**
     * get_play_auth
     *
     * @author Logan
     *
     * @param array $param
     *
     * @return boolean
     */
    public function getPlayAuth(array $param = [])
    {
        $sendRequest = [
            'app_id'     => $this->config->get('app_id'),
            'video_id' => $param['video_id'],
        ];
        $sendRequest['sign'] = $this->makeSign($sendRequest);

        //请求地址
        $url = $this->config->get('uri') . $this->gateway_get_play_auth;

        $trans = new Transfer();
        return $trans->sendRequest($url, $sendRequest, 'POST');
    }

    /**
     * submit_audit_media
     *
     * @author Logan
     *
     * @param array $param
     *
     * @return boolean
     */
    public function submitAuditMedia(array $param = []){

        $sendRequest = [
            'app_id'     => $this->config->get('app_id'),
            'video_id' => $param['video_id'],
        ];
        $sendRequest['sign'] = $this->makeSign($sendRequest);

        //请求地址
        $url = $this->config->get('uri') . $this->gateway_submit_audit_media;

        $trans = new Transfer();
        return $trans->sendRequest($url, $sendRequest, 'POST');
    }

    /**
     * query_audit_media
     *
     * @author Logan
     *
     * @param array $param
     *
     * @return boolean
     */
    public function queryAuditMedia(array $param = []){
        $sendRequest = [
            'app_id'     => $this->config->get('app_id'),
            'video_id' => $param['video_id'],
        ];
        $sendRequest['sign'] = $this->makeSign($sendRequest);

        //请求地址
        $url = $this->config->get('uri') . $this->gateway_query_audit_media;

        $trans = new Transfer();
        return $trans->sendRequest($url, $sendRequest, 'POST');
    }
}