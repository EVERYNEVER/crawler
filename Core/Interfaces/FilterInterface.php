<?php

namespace Core\Interfaces;

interface FilterInterface
{
    /**
     * 设置过滤规则
     */
    public function setRule($ruleNmae,$rule = array());

    /**
     * 获取过滤规则
     */
    public function getRule();

    /**
     * 执行过滤规则
     */
    public function exeRule($content);
}