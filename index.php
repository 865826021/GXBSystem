<?php
/**
 * GXBSystem - 基于ThinkPHP3.1.3的后台基础框架
 *
 * WEB层入口文件。
 *
 * @author      GenialX <admin@ihuxu.com>
 * @copyright   Copyright 2014-2015 沈阳晨信网络科技有限公司
 * @license     GNU Library General Public License 3.0
 */
require './Module/Web/Go.class.php';
if(!defined("GXBS_MODE")) define("GXBS_MODE", "WEB");
new Go();