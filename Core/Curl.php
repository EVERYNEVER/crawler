<?php

namespace Core;

/**
 * curl操作类
 */
class Curl
{
    private $curl;

    public function __construct()
    {
        require BASEDIR."/Extra/vendor/Curl/autoload.php";
        $this->curl = new \Curl\Curl();

        //设置连接超时时间
        $this->curl->setConnectTimeout(2);
    }

    /**
	 * 通过curl获取网页内容
	 * @param string $url 目标url地址
	 * @return string 网站内容
	 */
	public function openLink($url) {

		$data = $this->curl->get($url);
		return $data;
	}
}