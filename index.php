<?php
/**
 * 入口文件
 * @author LL
 */

	define("BASEDIR",__DIR__);

	/**
	 * 设置程序运行时间为永久
	 */
	set_time_limit(0);

    /**
     * 设置自动加载
     */
	include BASEDIR.'/Core/Loader.php';
	spl_autoload_register("\\Core\\Loader::autoload");

    /**
     * 引入注册组件文件
     */
	include BASEDIR."/component.php";

    /**
     * 注册一个Config组件
     */
	Core\ClassFactory::bind("Config",$components["Config"]);

    /**
     * 调用启动类，开始程序运行
     */
	new Core\Entrance(Core\Component::Config(), $components);