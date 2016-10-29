<?php
namespace Think;
/**
 * ThinkPHP Behavior基础类
 */
abstract class Behavior {
    /**
     * 执行行为 run方法是Behavior唯一的接口
     * @access public
     * @param mixed $params  行为参数
     * @return void
     */
    abstract public function run(&$params);

}