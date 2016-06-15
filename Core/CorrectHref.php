<?php

namespace Core;

class CorrectHref
{
    public function checkHref(&$href = array())
    {
        $this->remove($href);
        $this->correct($href);

        if(Component::Config()->get("isdomain")) {
            $this->removeLinksByDomain($href,Component::Config()->get("domain"));
        }
    }

    /**
     * 根据域名过滤链接，去除掉域名外的链接
     * @param array $href
     * @return array
     */
    private function removeLinksByDomain(&$href,$domain)
    {
        foreach($href as $k=>&$v){
            if(!preg_match('/^((https|http|ftp|rtsp|mms)?:\/\/)[^\s]*('.$domain.')+[^\s]*/',$v)){
                unset($href[$k]);
            }
        }
    }

    /**
     * 对没有域名的链接拼接域名
     * @param array $href
     */
    private function correct(&$href = array())
    {
        static $pressentDomain;

        $url = parse_url(Component::Crawler()->getPresentUrl());

        if($url){
            $pressentDomain = $url["scheme"]."://".$url["host"];
        }

        foreach($href as &$v){
            if(!preg_match('/^((https|http|ftp|rtsp|mms)?:\/\/)[^\s]+/',$v)){
                $v = $pressentDomain."/".trim($v,"/");
            }
        }
    }

    private function remove(&$href = array())
    {
        foreach($href as $k=>&$v) {
            if (preg_match('/^javascript:[^\s]*/', $v) || !$v) {
                unset($href[$k]);
            }
        }
    }
}