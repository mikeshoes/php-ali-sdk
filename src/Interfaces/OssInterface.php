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
     * @author Logan
     *
     * @param array $param
     *
     * @return mixed
     */
    public function backendVideoUpload(array $param);

    /**
     * backend get video play info
     *
     * @author Logan
     *
     * @param array $param
     *
     * @return boolean
     */
    public function backendGetPlayInfo(array $param);

    /**
     * backend get video play auth
     *
     * @author Logan
     *
     * @param array $param
     *
     * @return boolean
     */
    public function backendGetPlayAuth(array $param);

    /**
     * create upload video.
     *
     * @author Logan
     *
     * @param array $param
     *
     * @return mixed
     */
    public function createUploadVideo(array $param);

    /**
     * refresh Upload Video.
     *
     * @author Logan
     *
     * @param array $param
     *
     * @return mixed
     */
    public function refreshUploadVideo(array $param);

    /**
     * query Media Info By VideoId.
     *
     * @author Logan
     *
     * @param array $param
     *
     * @return mixed
     */
    public function queryMediaInfoByVideoId(array $param);

    /**
     * get Play Info.
     *
     * @author Logan
     *
     * @param array $param
     *
     * @return mixed
     */
    public function getPlayInfo(array $param);

    /**
     * get Play Auth.
     *
     * @author Logan
     *
     * @param array $param
     *
     * @return mixed
     */
    public function getPlayAuth(array $param);


    /**
     * submit audit media.
     *
     * @author Logan
     *
     * @return mixed
     */
    public function submitAuditMedia();

    /**
     * query audit media.
     *
     * @author Logan
     *
     * @return mixed
     */
    public function queryAuditMedia();

    /**
     * scan image
     *
     * @author Logan
     *
     * @param array $param
     *
     * @return mixed
     */
    public function scanImage(array $param);
}