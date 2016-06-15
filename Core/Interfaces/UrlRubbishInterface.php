<?php

namespace Core\Interfaces;

interface UrlRubbishInterface
{
    public function isInRubbish($value);

    public function joinRubbish($value);
}