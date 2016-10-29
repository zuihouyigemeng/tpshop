<?php
namespace Admin\Controller;


class ShopController extends WebController {

    public function index(){
        $map = array();
        if(isset($_GET['keyword'])){
            $where['name']  = array('like', '%'.I('keyword').'%');
            $where['_logic'] = 'or';
            $map['_complex'] = $where;
        }
        if(isset($_GET['category'])){
            $cid=D('Shop')->getId(I('category'));
            if(is_numeric($cid)){
                $map['category']  = $cid;
            } else {
                $map['category'] = array('in', $cid);
            }
        }
        if(isset($_GET['ten'])){
            $map['ten']  = I('ten');
        }
        $list = $this->lists('Shop', $map);
        $this->assign('shoplist', $list);
        $this->assign('category',   D('Shop')->getTree());
        $this->assign('ten',   D('ten')->getTree());
        $this->meta_title = '商品管理';
        $this->display();
    }

    public function edit($id = null){
        $Shop = D('Shop');
        if(IS_POST){
            if(false !== $Shop->update()){
                $this->success('编辑成功！', U('index'));
            } else {
                $error = $Shop->getError();
                $this->error(empty($error) ? '未知错误！' : $error);
            }
        } else {
			$info=$Shop->info($id);
            $this->assign('info',$info);
            $this->assign('category', D('Shop')->getTree());
            $this->assign('ten',   D('ten')->getTree());
            $this->meta_title = '编辑商品';
            $this->display();
        }
    }

    public function add(){
        $Shop = D('Shop');
        if(IS_POST){
            if(false !== $Shop->update()){
                $this->success('新增成功！', U('index'));
            } else {
                $error = $Shop->getError();
                $this->error(empty($error) ? '未知错误！' : $error);
            }
        } else {
            $this->meta_title = '添加商品';
            $this->assign('category', D('Shop')->getTree());
            $this->display('edit');
        }
    }
	
	public function status($id){
		$status=M('Shop')->where('id='.$id)->getField('status');
		if($status==0){
			M('Shop')->where('id='.$id)->setField('status',1);
            D('Shop')->adddate($id);
			$this->success('上架！');
		}else{
			$pid=M('shop_period')->where(array('sid'=>$id,'state'=>0))->getField('id');
			$record=M('shop_record')->field('uid,number')->where(array('pid'=>$pid))->select();
			foreach ($record as $key => $value) {
				M('user')->where(array('id'=>$value['uid']))->setInc('black',$value['number']);
			}
			M('shop_period')->where(array('id'=>$pid))->setField('state',3);
			M('Shop')->where('id='.$id)->setField('status',0);
			$this->error('下架！');
		}
		
	}

    public function del(){
		$id = array_unique((array)I('id',0));
        if ( empty($id) ) {
            $this->error('请选择要操作的数据!');
        }
        $res = D('Shop')->remove($id);
        if($res !== false){
            $this->success('删除商品成功！');
        }else{
            $this->error('删除商品失败！');
        }
    }

    public function auto(){
        $id = array_unique((array)I('id',0));
        if ( empty($id) ) {
            $this->error('请选择要操作的数据!');
        }
        $map = array('id' => array('in', $id));
        $list =  M('Shop')->where($map)->field('id,auto')->select();
        foreach ($list as $value){
           if($value['auto']==0){
                M('Shop')->where('id='.$value['id'])->setField('auto',1);
            }else{
                M('Shop')->where('id='.$value['id'])->setField('auto',0);
            }
        }
        $this->success('商品操作成功！');
    }
	
	public function period($id){
		$map['sid']=$id;
		$list = $this->lists('shop_period', $map ,'state asc',0,'');
		$this->assign('price', I('price'));
        $this->assign('periodlist', $list);
        $this->meta_title = '商品管理';
        $this->display();
	}
}
