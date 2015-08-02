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
 * @name 系统配置模块
 */
class ConfigAction extends AdminAction{
    public function _initialize() {
        parent::_initialize();  //RBAC 验证接口初始化
    }

	// 基本信息设置
    public function conf(){
		$id           = $this->_get('id','trim','web');
		$config       = require CONF_PATH .'web.php';	//网站配置
		$config_admin = require CONF_PATH .'Admin/config.php';	//后台分组配置
		$config_home  = require CONF_PATH .'Home/config.php';	//前台分组配置
		$tpl          = TMPL_PATH . '/Home/*';		//前台模板
		$list         = glob($tpl);
		foreach($list as $i => $file){
			if (!is_file($file) && $file != "." && $file != ".." )
			$temp[$i]['filename']=basename($file);
		}
		$this->assign('temp',$temp);
		$this->assign('con',$config);
		$this->assign('con_admin',$config_admin);
		$this->assign('con_home',$config_home);
	    $this->display($id);
    }
    
	// 配置信息保存
    private function updateconfig($config){
    	foreach ($config as $k => $c) {
    		$config_old = array();
    		$config_new = array();
    		switch ($k) {
    			case 'con':
					$config_old = require CONF_PATH . 'web.php';
					if(is_array($c)) $config_new = array_merge($config_old,$c);
					arr2file(CONF_PATH . 'web.php',$config_new);
    				break;

    			case 'con_admin':
					$config_old = require CONF_PATH.'Admin/config.php';
					if(is_array($c)) $config_new = array_merge($config_old,$c);
					arr2file(CONF_PATH.'Admin/config.php',$config_new);
    				break;
    			
    			case 'con_home':
					$config_old = require CONF_PATH.'Home/config.php';
					if(is_array($c)) $config_new = array_merge($config_old,$c);
					arr2file(CONF_PATH.'Home/config.php',$config_new);
    				break;
    		}

    	}
    	@unlink( TEMP_PATH . '~app.php');
		$this->success('更新成功！');
	}
	
	//更新web相关配置
    public function updateweb(){
		$con                      = $_POST["con"];
		if(isset($_POST["con_home"]))
		$con_home                 = $_POST["con_home"];
		if(isset($con['WEB_URL']))
		$con['WEB_URL']           = getaddxie($con['WEB_URL']);
		if(isset($con['WEB_PATH']))
		$con['WEB_PATH']          = getaddxie($con['WEB_PATH']);
		if(isset($con['WEB_ADSENSEPATH']))
		$con['WEB_ADSENSEPATH']   = getrexie($con['WEB_ADSENSEPATH']);
		if(isset($con['web_copyright']))
		$con['WEB_COPYRIGHT']     = stripslashes($con['WEB_COPYRIGHT']);
		if(isset($con['WEB_TONGJI']))
		$con['WEB_TONGJI']        = stripslashes($con['WEB_TONGJI']);
		if(isset($con['WEB_ADMIN_PAGENUM']))
		$con['WEB_ADMIN_PAGENUM'] = abs(intval($con['WEB_ADMIN_PAGENUM']));
		if(isset($con['WEB_HOME_PAGENUM']))
		$con['WEB_HOME_PAGENUM']  = abs(intval($con['WEB_HOME_PAGENUM']));
		if(isset($con['WEB_ADSENSEPATH'])){
			$dir                      = './'.$con['WEB_ADSENSEPATH'];	//广告保存目录
			if(!is_dir($dir)){
				mkdirss($dir);
			}
		}
		if(isset($con_home)){
			$config = array('con'=>$con,'con_home'=>$con_home);
		}else{
			$config = array('con'=>$con);
		}
		$this->updateconfig($config);
	}

	//更新邮件服务器配置
    public function updatemail(){
		$con = $_POST["con"];
		$con['SMTP_PORT'] = abs(intval($con['SMTP_PORT']));
		$con['SMTP_TIME_OUT'] = abs(intval($con['SMTP_TIME_OUT']));
		$con['SMTP_AUTH'] = abs(intval($con['SMTP_AUTH']));
		$config = array('con'=>$con);
		$this->updateconfig($config);
	}

	//更新数据库链接配置
    public function updatedb(){
		$con = $_POST["con"];
		$con['DB_PORT'] = abs(intval($con['DB_PORT']));
		$config = array('con'=>$con);
		$this->updateconfig($config);
	}
	
}