<?php
/**
 * Admin分组基类.
 * 
 * @version 0.1.0     增加若干基类方法。by GenialX
 * 
 * @author  水木清华   <admin@4u4v.net>
 * @author  GenialX   <admin@echenxin.com>
 */
abstract class AdminAction extends CmsAction{
    
    // 过滤条件数组
    protected $_filterMap = array();
    
    public function _initialize(){
        parent::_initialize();
        // 后台用户权限检查
        if (C('USER_AUTH_ON') && !in_array(MODULE_NAME, explode(',', C('NOT_AUTH_MODULE')))) {
            import('ORG.Util.RBAC');
            if (!RBAC::AccessDecision()) {
                //检查认证识别号
                if (!$_SESSION [C('USER_AUTH_KEY')]) {
                    //跳转到认证网关
                    redirect(PHP_FILE . C('USER_AUTH_GATEWAY'));
                }
                // 没有权限 抛出错误
                if (C('RBAC_ERROR_PAGE')) {
                    // 定义权限错误页面
                    redirect(C('RBAC_ERROR_PAGE'));
                } else {
                    if (C('GUEST_AUTH_ON')) {
                        $this->assign('jumpUrl', PHP_FILE . C('USER_AUTH_GATEWAY'));
                    }
                    // 提示错误信息
                    $this->error(L('_VALID_ACCESS_'));
                }
            }
        }
    }
    
    /**
     * 列表页前处理函数.
     *
     * @version 0.0.1
     * @since   0.1.0
     *
     * @author  GenialX
     */
    protected function _before_index() {}
    
    /**
     * 管理页面.
     * 
     * @version 0.0.1
     * @since   0.1.0
     * 
     * @author  GenialX
     */
    public function index() {
        $this->_before_index();
        import('ORG.Util.Page');// 导入分页类
        $map        = self::_getFilterMap();
        $model      = self::_getModel();
        ($model instanceof RelationModel) ? $model->relation(true) : null;
        $count      = $model->where($map)->count();
        $Page       = new Page($count);// 实例化分页类 传入总记录数
        // 进行分页数据查询 注意page方法的参数的前面部分是当前的页数使用 $_GET[p]获取
        $nowPage    = isset($_GET['p']) ? $_GET['p'] : 1;
        $show       = $Page->show();// 分页显示输出
        $list       = $model->where($map)->order('id ASC')->page($nowPage.','.C('web_admin_pagenum'))->select();
        $this->_after_index($list, $map);
        $this->assign('list',$list);
        $this->assign('page',$show);// 赋值分页输出
        $this->display();
    }
    
    /**
     * 列表页后处理函数.
     *
     * @version 0.0.1
     * @since   0.1.0
     *
     * @author  GenialX
     */
    protected function _after_index(& $data, $option) {}
    
    /**
     * 编辑页前处理函数.
     *
     * @version 0.0.1
     * @since   0.1.0
     *
     * @author  GenialX
     */
    protected function _before_edit() {}
    
    /**
     * 编辑页面.
     *
     * @version 0.0.1
     * @since   0.1.0
     *
     * @author  GenialX
     */
    public function edit() {
        $this->_before_edit();
        $model      = $this->_getModel();
        $id         = $this->_get('id','intval',0);
        if(!$id) $this->error(L('PARAM') . L('ERROR'));
        $info       = $model->where(array('id' => $id))->find();
        $this->assign('tpltitle',L('EDIT'));
        $this->_after_edit($info, array('id' => $id));
        $this->assign('info',$info);
        $this->display('edit');
    }
    
    /**
     * 编辑页后处理函数.
     *
     * @version 0.0.1
     * @since   0.1.0
     *
     * @author  GenialX
     */
    protected function _after_edit(& $data, $option) {}
    
    protected function _before_add() {}
    
    public function add() {
        $this->display();
    }
    
    protected function _after_add(& $data, $option) {}
    
    /**
     * 更新操作前处理函数.
     *
     * @version 0.0.1
     * @since   0.1.0
     *
     * @author  GenialX
     */
    protected function _before_update() {}
    
    /**
     * 更新操作处理函数.
     *
     * @version 0.0.1
     * @since   0.1.0
     *
     * @author  GenialX
     */
    public function update() {
        $this->_before_update();
        $model = $this->_getModel();
        if ($model->create()) {
            $list = $model->save();
            $this->_after_update();
            if ($list == true) {
                $this->success(L('DATA') . L('UPDATE') . L('SUCCESS'), U('/Admin/' . MODULE_NAME . '/index'));
            } else {
                $this->error(L('NO') . L('ANY') . L('DATA') . L('UPDATE'));
            }
        } else {
            $this->error($model->getError());
        }
    }
    
    /**
     * 更新操作后处理函数.
     *
     * @version 0.0.1
     * @since   0.1.0
     *
     * @author  GenialX
     */
    protected function _after_update() {}
    
    protected function _before_insert() {}
    
    public function insert() {}
    
    protected function _after_insert() {}
    
    protected function _before_delete() {}
    
    public function delete() {}
    
    protected function _after_delete() {}
    
    protected function _getModel($modelName = '') {
        if($modelName == "") {
            $modelName = MODULE_NAME;
        }
        return D($modelName);
    }
    
    protected function _getFilterMap() {
        return $this->_filterMap;
    }
    
    protected function _filter() {}
    
}