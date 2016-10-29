<?php
namespace Admin\Controller;
use Think\Controller;

class webController extends Controller {

    protected function _initialize(){
        define('UID',is_login());
        if(!UID){
            $this->redirect('public/login');
        }
        $config =   S('DB_CONFIG_DATA');
        if(!$config){
            $config =  config_lists();
            S('DB_CONFIG_DATA',$config);
        }
        C($config);
        if(!C('WEB_SITE_CLOSE')){
            $this->error('站点已经关闭，请稍后访问~');
        }
		$this->assign('__MENU__', $this->getMenus());
	}
	
    final public function getMenus($controller=CONTROLLER_NAME){
        $menus  =   session('WEB_MENU_LIST'.$controller);
        if(empty($menus)){
            $where['pid']   =   0;
            $where['hide']  =   0;
            $menus['main']  =   M('WebMenu')->where($where)->order('sort asc')->select();
            $menus['child'] = array();
            $current = M('WebMenu')->where("url like '%{$controller}/".ACTION_NAME."%'")->field('id')->find();
            if($current){
                $nav = D('WebMenu')->getPath($current['id']);
                $nav_first_title = $nav[0]['title'];
                foreach ($menus['main'] as $key => $item) {
                    if (!is_array($item) || empty($item['title']) || empty($item['url']) ) {
                        $this->error('控制器基类$menus属性元素配置有误');
                    }
                    if( stripos($item['url'],MODULE_NAME)!==0 ){
                        $item['url'] = MODULE_NAME.'/'.$item['url'];
                    }
                    if($item['title'] == $nav_first_title){
                        $menus['main'][$key]['class']='active';
                        $groups = M('WebMenu')->where("pid = {$item['id']}")->distinct(true)->field("`group`")->order('sort asc')->select();
                        if($groups){
                            $groups = array_column($groups, 'group');
                        }else{
                            $groups =   array();
                        }
                        $where          =   array();
                        $where['pid']   =   $item['id'];
                        $where['hide']  =   0;
                        $second_urls = M('WebMenu')->where($where)->getField('id,url');
                        foreach ($groups as $g) {
                            $map = array('group'=>$g);
                            if(isset($second_urls)){
                                    $map['url'] = array('in', $second_urls);
                            }
                            $map['pid'] =   $item['id'];
                            $map['hide']    =   0;
                            $menuList = M('WebMenu')->where($map)->field('id,pid,title,url,tip,icon,group')->order('sort asc')->select();
                            $menus['child'][$g] = list_to_tree($menuList, 'id', 'pid', 'operater', $item['id']);
                        }
                    }
                }
            }
            session('WEB_MENU_LIST'.$controller,$menus);
        }
        return $menus;
    }
	
    protected function lists ($model,$where=array(),$order='',$rows=0,$base = array('status'=>array('egt',0)),$field=true){
        $options    =   array();
        $REQUEST    =   (array)I('request.');
        if(is_string($model)){
            $model  =   M($model);
        }
        $OPT        =   new \ReflectionProperty($model,'options');
        $OPT->setAccessible(true);

        $pk         =   $model->getPk();
        if($order===null){
        }else if ( isset($REQUEST['_order']) && isset($REQUEST['_field']) && in_array(strtolower($REQUEST['_order']),array('desc','asc')) ) {
            $options['order'] = '`'.$REQUEST['_field'].'` '.$REQUEST['_order'];
        }elseif( $order==='' && empty($options['order']) && !empty($pk) ){
            $options['order'] = $pk.' desc';
        }elseif($order){
            $options['order'] = $order;
        }
        unset($REQUEST['_order'],$REQUEST['_field']);
        $options['where'] = array_filter(array_merge( (array)$base, /*$REQUEST,*/ (array)$where ),function($val){
            if($val===''||$val===null){
                return false;
            }else{
                return true;
            }
        });
        if( empty($options['where'])){
            unset($options['where']);
        }
        $options      =   array_merge( (array)$OPT->getValue($model), $options );
        $total        =   $model->where($options['where'])->count();

        if( isset($REQUEST['r']) ){
            $listRows = (int)$REQUEST['r'];
        }else{
            $listRows = $rows > 0 ? $rows : 20;
        }
        $page = new \Think\Page($total, $listRows, $REQUEST);
        if($total>$listRows){
            $page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%');
        }
        $p =$page->show();
        $this->assign('_page', $p? $p: '');
        $this->assign('_total',$total);
        $options['limit'] = $page->firstRow.','.$page->listRows;
        $model->setProperty('options',$options);
        return $model->field($field)->select();
    }

    final protected function editRow ( $model ,$data, $where , $msg ){
        $id    = array_unique((array)I('id',0));
        $id    = is_array($id) ? implode(',',$id) : $id;
        $fields = M($model)->getDbFields();
        if(in_array('id',$fields) && !empty($id)){
            $where = array_merge( array('id' => array('in', $id )) ,(array)$where );
        }
        $msg   = array_merge( array( 'success'=>'操作成功！', 'error'=>'操作失败！', 'url'=>'' ,'ajax'=>IS_AJAX) , (array)$msg );
        if( M($model)->where($where)->save($data)!==false ) {
            $this->success($msg['success'],$msg['url'],$msg['ajax']);
        }else{
            $this->error($msg['error'],$msg['url'],$msg['ajax']);
        }
    }
	
    protected function forbid ( $model , $where = array() , $msg = array( 'success'=>'状态禁用成功！', 'error'=>'状态禁用失败！')){
        $data    =  array('status' => 0);
        $this->editRow( $model , $data, $where, $msg);
    }

    protected function resume (  $model , $where = array() , $msg = array( 'success'=>'状态恢复成功！', 'error'=>'状态恢复失败！')){
        $data    =  array('status' => 1);
        $this->editRow(   $model , $data, $where, $msg);
    }

    public function setStatus($Model=CONTROLLER_NAME){

        $id    =   I('request.id');
        $status =   I('request.status');
        if(empty($id)){
            $this->error('请选择要操作的数据');
        }

        $map['id'] = array('in',$id);
        switch ($status){
            case 0  :
                $this->forbid($Model, $map, array('success'=>'禁用成功','error'=>'禁用失败'));
                break;
            case 1  :
                $this->resume($Model, $map, array('success'=>'启用成功','error'=>'启用失败'));
                break;
            default :
                $this->error('参数错误');
                break;
        }
    }
}
