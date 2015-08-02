<?php

// +----------------------------------------------------------------------
// | ThinkPHP通用后台管理系统
// +----------------------------------------------------------------------
// | Copyright (c) 2013 www.4u4v.net All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 水木清华 <admin@4u4v.net>
// +----------------------------------------------------------------------

/**
 * @name    缓存模块
 * 
 * 
 */
class CacheAction extends AdminAction{
	public function _initialize() {
		parent::_initialize();	//RBAC 验证接口初始化
	}

	// 删除全部核心缓存
    public function delCore(){
		import("ORG.Io.Dir");
		$dir = new Dir;
		@unlink( TEMP_PATH . '~runtime.php');		//删除主编译缓存文件
		@unlink( TEMP_PATH . '~crons.php');		//删除计划任务缓存文件
		@unlink( TEMP_PATH . 'cron.lock');		//删除计划任务执行锁定文件
		if(is_dir( DATA_PATH)){$dir->delDir( DATA_PATH);}
		if(is_dir( TEMP_PATH)){$dir->delDir( TEMP_PATH );}
		if(is_dir( CACHE_PATH)){$dir->delDir( CACHE_PATH);}
		if(is_dir( LOG_PATH)){$dir->delDir( LOG_PATH);}
		echo('[清除成功]');
    }

}