<?php
namespace Wdy\AliService;

use Wdy\AliService\Service\OssFile;
use Wdy\AliService\Service\VodFile;
use Wdy\AliService\Interfaces\OssInterface;

require_once  dirname(__DIR__) . DIRECTORY_SEPARATOR . 'libs' . DIRECTORY_SEPARATOR .
    'voduploadsdk' . DIRECTORY_SEPARATOR . 'Autoloader.php';

class ServiceManager
{
    protected $config;

    public function __construct($config)
    {
        $this->config = $config;
    }

    /**
     *
     * @param $driver
     * @return OssInterface
     * @throws \Exception
     */
    public function gate($driver)
    {
        return $this->createDriver($driver);
    }

    /**
     * 创建driver
     * @param $driver
     * @return OssInterface
     * @throws \Exception
     */
    protected function createDriver($driver)
    {
        $method = 'create' . ucfirst($driver) . 'Driver';
        if (method_exists($this, $method)) {
            return $this->$method($this->getConfig($driver));
        }

        throw new \Exception("dont support $driver file uploader");
    }

    /**
     * 获取配置文件
     * @param $driver
     * @return array
     */
    protected function getConfig($driver)
    {
        return $this->config['uploader'][$driver] ?? [];
    }

    /**
     * 创建阿里云文件服务
     * @param $config
     * @return OssFile
     */
    private function createFileDriver($config)
    {
        return new OssFile($config);
    }

    /**
     * 创建阿里云Media服务
     * @param $config
     * @return VodFile
     */
    private function createMediaDriver($config)
    {
        return new VodFile($config);
    }
}