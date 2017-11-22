<?php
/**
 * ajax
 *
 * ajax控制器基类
 * @namespace panda\lib\controller
 * @package lib_controller
 */
namespace panda\lib\controller;

use panda\lib\traits\response;

/**
 * ajax
 *
 * ajax控制器基类
 */
abstract class ajax extends web
{
    use response;
}