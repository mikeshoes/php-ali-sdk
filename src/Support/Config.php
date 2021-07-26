<?php


namespace Wdy\AliService\Support;


class Config
{

    protected $config = [
        'app_id'    => '',
        'secret_key'=> '',
        'uri'       => '',
        'region_id'  => '',
        'access_key_id' => '',
        'access_key_secret' => '',
    ];

    public function __construct($config = [])
    {
        $this->config = array_merge($this->config, $config);
        $this->invalidConfig();
    }

    public function get($key)
    {
        if (empty($this->config[$key])) {
            throw new \InvalidArgumentException("dont find config $key");
        }

       return $this->config[$key];
    }

    private function invalidConfig()
    {
        if (is_null($this->config['app_id']) || is_null($this->config['uri'])) {
            throw new \InvalidArgumentException('Missing Config -- [app_id] [uri]');
        }
    }
}