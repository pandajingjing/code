<?php
/**
 * 404 page
 * @package system_kernel_page_error
 */
load_page('/kernelframe');
/**
 * 404 page
 * @author jxu
 * @package system_kernel_page_error
 */
class error_404page extends kernelframe{

	/**
	 * 使用的样式
	 * @return array
	 */
	static function useOStyle(){
		return array_merge(parent::useOStyle(), array(
				'static://common/base.css',
				'static://common/soon.css'
		));
	}

	/**
	 * 使用的组件
	 * @return array
	 */
	static function useComponent(){
		return array_merge(parent::useComponent(), array( 
				'/error/top',
				'/error/bottom' 
		));
	}

	/**
	 * 获取页面顶部组件
	 * @return string
	 */
	protected function getTop(){
		return '/error/top';
	}

	/**
	 * 获取页面底部组件
	 * @return string
	 */
	protected function getBottom(){
		return '/error/bottom';
	}

	/**
	 * 获取UI
	 */
	function getView(){
		return '404';
	}

	/**
	 * 获取页面标题
	 */
	protected function getTitle(){
		return '资源未找到';
	}
}