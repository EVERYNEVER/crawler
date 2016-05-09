<?php

require __DIR__."/index.php";

$urlQueue = Core\Component::GuideUrl();
$urlQueue->setUrl(["a","b","c"]);
$a = $urlQueue->getUrl();
var_dump($a);
$b = $urlQueue->getUrl();
var_dump($b);
$urlQueue->setUrl(["a","e","g","h"]);
var_dump($urlQueue);