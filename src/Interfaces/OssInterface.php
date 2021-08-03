<?php
namespace Wdy\AliService\Interfaces;

interface OssInterface
{
    public function uploadFile($param);

    public function getUploadToken();

    public function getStsToken();

    /**
     * backend video upload.
     *
     * @param array $param
     *
     * @return mixed
     */
    public function backendVideoUpload(array $param);

    /**
     * backend get video play info
     *
     * @param array $param
     *
     * @return boolean
     */
    public function backendGetPlayInfo(array $param);

    /**
     * backend get video play auth
     *
     * @param array $param
     *
     * @return boolean
     */
    public function backendGetPlayAuth(array $param);

    /**
     * create upload video.
     *
     * @param array $param
     *
     * @return mixed
     */
    public function createUploadVideo(array $param);

    /**
     * refresh Upload Video.
     *
     * @param array $param
     *
     * @return mixed
     */
    public function refreshUploadVideo(array $param);

    /**
     * query Media Info By VideoId.
     *
     * @param array $param
     *
     * @return mixed
     */
    public function queryMediaInfoByVideoId(array $param);

    /**
     * get Play Info.
     *
     * @param array $param
     *
     * @return mixed
     */
    public function getPlayInfo(array $param);

    /**
     * get Play Auth.
     *
     * @param array $param
     *
     * @return mixed
     */
    public function getPlayAuth(array $param);


    /**
     * submit audit media.
     *
     * @param array $param
     *
     * @return mixed
     */
    public function submitAuditMedia(array $param);

    /**
     * query audit media.
     *
     * @param array $param
     *
     * @return mixed
     */
    public function queryAuditMedia(array $param);

    /**
     * scan image
     *
     * @param array $param
     *
     * @return mixed
     */
    public function scanImage(array $param);
}