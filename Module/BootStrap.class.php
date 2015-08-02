<?php
abstract class BootStrap {
    
    protected $config = array();
    
    /**
     * 构造函数.
     */
    public function __construct() {
        // [超全局常量]定义WEB系统根路径
        define('WEB_PATH', dirname(dirname(__FILE__)) . '/');
        /** 获取配置 **/
        $this->_iniConfig();
        /** 调用子类处理 **/
        $this->_go();
        /** 启动TP **/
        $this->_startTP();
    }
    
    /**
     * 启动ThinkPHP.
     */
    private function _startTP() {
        /********************/
        /** 自定义项目结构规范 **/
        /********************/
        // 定义系统框架的路径
        define('THINK_PATH', dirname(dirname(__FILE__)) . '/System/');
        // 开启调试模式 建议开发阶段开启 部署阶段注释或者设为false
        define('APP_DEBUG', $this->config['APP_DEBUG']);
        // 定义应用[WEB]目录路径
        define('APP_PATH', dirname(__FILE__) . "/" . ucwords(strtolower(GXBS_MODE)) . "/");
        // 定义配置路径
        define('CONF_PATH', WEB_PATH . 'Conf/' . ucwords(strtolower(GXBS_MODE)) . '/');
        // 定义语言包路径
        define('LANG_PATH', WEB_PATH . 'I18N/' . ucwords(strtolower(GXBS_MODE)) . '/');
        // 定义RUNTIME路径
        define('RUNTIME_PATH', WEB_PATH . 'Runtime/' . ucwords(strtolower(GXBS_MODE)) . '/');
        // 项目模板目录
        define('TMPL_PATH', WEB_PATH . 'Tpl/' . ucwords(strtolower(GXBS_MODE)) . '/');
        // 引入ThinkPHP入口文件
        require THINK_PATH . '/ThinkPHP.php';
    }
     
    /**
     * 获取配置文件.
     * 
     * @return void
     */
    private function _iniConfig() {
        $filename     = strtolower(GXBS_MODE);
        $filepath     = dirname(dirname(__FILE__)) . "/Conf/" . ucwords($filename) . "/config.php";
        $this->config = require $filepath;
    }
    
    /** Methods need extending **/
    
    abstract protected function _go();
    
}