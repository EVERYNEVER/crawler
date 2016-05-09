<?php

namespace Core\Interfaces;

interface ConfigInterface
{
    public function get($configName);

    public function set($configName,$value);
}