<?php

namespace Core\Components;

/**
 * Class ProcessUrl
 * url处理类
 * @author LL
 */
class ProcessUrl
{

    /**
     * 存储url数组
     */
    private $urlArray = array();

	/**
	 * 更新url数组
	 * @param array $url_array 要处理的url数组
	 */
	public function updateUrlArray($newUrlArray,$level)
    {
		$newUrlArray = $this->machiningUrl($newUrlArray);

		foreach($newUrlArray as &$v) {
			$v['level'] = $level+1;
			$v['status'] = 1;
		}

		$this->urlArray = $this->urlArray + $newUrlArray;
	}

	/**
	 * 对url数组建立索引
	 * @param array $urlArray 要建立索引的数组
     * @return array 键为值md5后的数组
	 */
	private function machiningUrl($urlArray)
    {
		$data = array();
		foreach($urlArray as $v) {
			$data[md5($v)]['url'] = $v;
		}

		return $data;
	}

    /**
     * 获得urlArray
     * @return array ProcessUrl的urlArray属性
     */
    public function getUrlArray()
    {
        return $this->urlArray;
    }
}
?>