<?php
namespace Admin\Model;
use Think\Model;
use Think\Storage;

class ShopModel extends Model{

    protected $_validate = array(
        array('name', 'require', '商品名称不能为空', self::MUST_VALIDATE , 'regex', self::MODEL_BOTH),
        array('category', 'checkCategory', '该分类下还有分类请选择下属分类', self::MUST_VALIDATE , 'callback', self::MODEL_BOTH),
		array('cover_id', 'require', '请上传商品图片', self::MUST_VALIDATE , 'regex', self::MODEL_BOTH),
		array('content', 'require', '商品介绍不能为空', self::MUST_VALIDATE , 'regex', self::MODEL_BOTH),
		array('price', 'require', '请填写商品价格', self::MUST_VALIDATE , 'regex', self::MODEL_INSERT),
        array('edit_price', 'require', '请填写商品价格', self::MUST_VALIDATE , 'regex', self::MODEL_UPDATE),
        array('buy_price', 'require', '请填写商品购买价格', self::MUST_VALIDATE , 'regex', self::MODEL_BOTH),
    );

    protected $_auto = array(
		array('name', 'htmlspecialchars', self::MODEL_BOTH, 'function'),
		array('create_time', 'getCreateTime', self::MODEL_BOTH,'callback'),
        array('update_time', NOW_TIME, self::MODEL_BOTH),
		array('position', 'getPosition', self::MODEL_BOTH, 'callback'),
    );
	
    public function info($id, $field = true){
        $map = array();
        if(is_numeric($id)){
            $map['id'] = $id;
        }
		$info=$this->field($field)->where($map)->find();
		$info["picurl"]=get_cover($info["cover_id"],"path");
        return $info;
    }
	
    public function update(){
        $data = $this->create();
        if(!$data){
            return false;
        }
        if($data['ten']!=0){
            if($data['price']%get_ten_unit($data['ten'])!=0 || $data['edit_price']%get_ten_unit($data['ten'])!=0){
                $this->error = '价格必须要能够被专区单位整除!';
                return false;
            }
        }
        if(empty($data['id'])){
            $this->edit_price=$data['price'];
            $res = $this->add();
            $period['jiang_num']=jiang_num($data['price']-1);
            $period['sid']=$res;
            $period['create_time']=NOW_TIME;
            $period['state']=0;
            $period['no']=100001;
            M('shop_period')->data($period)->add();
        }else{
            $res = $this->save();
        }
        return $res;
    }

    public function adddate($id){
        $data=D('shop')->info($id,'price,status');
        $no=M('shop_period')->where('sid='.$id)->max('no');
        $period['jiang_num']=jiang_num($data['price']-1);
        $period['sid']=$id;
        $period['create_time']=NOW_TIME;
        $period['state']=0;
        $period['no']=$no?$no+1:100001;
        if($data['status']>0){
            M('shop_period')->data($period)->add();
        }
    }
		
    public function remove($id = null){
		$map = array('id' => array('in', $id) );
		$movie_list = $this->where($map)->field('cover_id,content')->select();
		foreach ($movie_list as $key => $value) {
			$picture[$key] = $value['cover_id'];
            $content[$key] = $value['content'];
		}
		$map_cover = array('id' => array('in',$picture));
		$cover_list = M("picture")->where($map_cover)->field('path')->select();
		foreach ($cover_list as $value) {
			Storage::unlink('.'.$value['path']);
		}
        foreach($content as $v){
            preg_match_all('/<\s*img\s+[^>]*?src\s*=\s*(\'|\")(.*?)\\1[^>]*?\/?\s*>/i',$v,$match);
            foreach($match[2] as $a){
               Storage::unlink('.'.$a); 
            }
        }
		$res=$this->where($map)->delete();
        M("shop_period")->where(array('sid' => array('in', $id) ))->delete();
		M("picture")->where($map_cover)->delete();
		return $res;
	}
	
    protected function getPosition(){
        $position = I('post.position');
        if(!is_array($position)){
            return 0;
        }else{
            $pos = 0;
            foreach ($position as $key=>$value){
                $pos += $value;
            }
            return $pos;
        }
	}
	
    protected function getCreateTime(){
        $create_time    =   I('post.create_time');
        return $create_time?strtotime($create_time):NOW_TIME;
    }


    public function getTree(){
        $list = M("Category")->where(array('status'=>1))->field('id,pid,title')->order('pid asc,sort asc')->select();
        $Tree = new \Org\Tree;
        $Tree::$treeList = array();
        return $Tree->tree($list);
    }

    public function getId($id){
        $map["status"] = 1;
        $map["display"] = 1;
        if($id){
            $map["pid"] = $id;
            $info = M("Category")->field("id")->where($map)->order('sort')->select();
            if($info){
                foreach ($info as $key=>$val){
                    $ids[]=$val["id"];
                }
            }else{
                $ids[]=$id;
            }
            return $ids;
        }
    }

    protected function checkCategory($cate_id){
         $child = M('Category')->where(array('pid'=>$cate_id))->field('id')->select();
        if(!empty($child)){
            return false;
        }
        return true;
    }
}
