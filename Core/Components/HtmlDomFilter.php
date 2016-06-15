<?php

namespace Core\Components;

use Core\Interfaces\FilterInterface;
use Core\Component;

class HtmlDomFilter implements FilterInterface
{
    /**
     * 页面内容的过滤规则
     */
    private $htmlRule = array();

    /**
     * url的选取规则
     */
    private $urlRule = array();

    /**
     * 链接的过滤规则
     */
    private $hrefRule = array();

    /**
     * 规则索引
     */
    private $ruleIndex;

    /**
     * 构造方法
     */
    public function __construct()
    {
        $this->ruleIndex = 0;
        $this->htmlRule = [
            0 => function($htmldom){
                $src = [];
                $img = $htmldom->find("img");
                foreach($img as $v){
                    $src[] = $v->src;
                }
                return ["img"=>$src];
            },
        ];
        $this->urlRule[0] = "";
        $this->hrefRule = [
            0 => function($htmldom){
                $href = [];
                $a = $htmldom->find("a");
                foreach($a as $v){
                    $href[] = $v->href;
                }
                return $href;
            }
        ];
    }

    /**
     * 获取所有规则
     * @return array
     */
    public function getRule()
    {
        return [
            "ruleIndex" => $this->ruleIndex,
            "htmlRule" => $this->htmlRule,
            "urlRule" => $this->urlRule,
            "hrefRule" => $this->hrefRule
        ];
    }

    /**
     * 设置规则
     * @param string $rulename 规则名称
     * @param array $rule 规则数组
     */
    public function setRule($rulename,$rule = array())
    {
        switch($rulename){
            case "html" :
                $quote =& $this->htmlRule;
                break;
            case "url" :
                $quote =& $this->urlRule;
                break;
            case "href" :
                $quote =& $this->hrefRule;
                break;
            default :
                throw new \Exception("No rule set found");
                break;
        }

        foreach($rule as $k=>$v){
            $quote[$k] = $v;
        }
    }

    /**
     * 执行过滤规则
     */
    public function exeRule($content)
    {
        $htmldom = Component::HtmlDom();
        $htmldom->load($content);

        //匹配链接
        if(!empty($this->hrefRule[$this->ruleIndex])) {
            $filteredHref = call_user_func_array($this->hrefRule[$this->ruleIndex], [$htmldom]);

            if(!empty($filteredHref)) {
                //对获取的链接进行处理
                Component::CorrectHref()->checkHref($filteredHref);
            }
        } else {
            $filteredHref = "";
        }

        //匹配内容
        if(!empty($this->htmlRule[$this->ruleIndex])) {
            $filteredHtml = call_user_func_array($this->htmlRule[$this->ruleIndex], [$htmldom]);
        } else {
            $filteredHtml = "";
        }

        unset($htmldom);

        return ["filteredHref" => $filteredHref, "filteredHtml" => $filteredHtml];
    }

    /**
     * 通过对urlRule的匹配来获取ruleIndex
     * @param string $url 当前的内容对应的url
     */
    public function setRuleIndex($url)
    {
        foreach ($this->urlRule as $k => $v) {
            if(empty($v)){
                $ruleIndex = $k;
                break;
            }
            if (preg_match($v, $url)) {
                $ruleIndex = $k;
                break;
            }
        }

        if (!isset($ruleIndex)) {
            $this->ruleIndex = 0;
        } else {
            $this->ruleIndex = $ruleIndex;
        }
    }

    /**
     * 强制设置rule索引
     * @param int $index 索引值
     */
    public function forceSetRuleIndex($index)
    {
        $this->ruleIndex = $index;
    }

    /**
     * 获取当前的ruleIndex
     */
    public function getRuleIndex()
    {
        return $this->ruleIndex;
    }
}