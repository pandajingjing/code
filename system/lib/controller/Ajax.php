<?php

/**
 * lib_controller_ajax
 *
 * ajax控制器基类
 *
 * @package lib_controller
 */
namespace panda\lib\controller;

use panda\lib\traits\Response;

/**
 * lib_controller_ajax
 *
 * ajax控制器基类
 */
abstract class Ajax extends Web
{
    use Response;
}