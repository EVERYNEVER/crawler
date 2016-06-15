<?php

namespace Extra;

require BASEDIR."/Extra/vendor/ParserDom/ParserDom.php";

class HtmlDom extends \HtmlParser\ParserDom
{
    public function __construct()
    {
        parent::__construct();
    }
}