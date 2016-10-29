<?php
namespace Admin\Controller;

class UeditorController extends WebController{

	public function index(){
		$CONFIG = json_decode(preg_replace("/\/\*[\s\S]+?\*\//", "", file_get_contents(CONF_PATH."config.json")), true);
		$action = htmlspecialchars($_GET['action']);
		switch ($action) {
			 case 'config':
		        $result = json_encode($CONFIG);
		        break;
		    case 'uploadimage':
		       	$result = $this->uploadPicture();
		        break;
		    case 'uploadvideo':
				$result = $this->uploadvideo();
		        break;
		    case 'listimage':
				$result = $this->listimg();
		    	break;
		}
		if (isset($_GET["callback"])) {
		    if (preg_match("/^[\w_]+$/", $_GET["callback"])) {
		        echo htmlspecialchars($_GET["callback"]) . '(' . $result . ')';
		    } else {
		        echo json_encode(array(
		            'state'=> 'callback参数不合法'
		        ));
		    }
		} else {
		    echo $result;
		}
	}
	
	 public function uploadPicture(){
        $Picture = D('Picture');
        $pic_driver = C('PICTURE_UPLOAD_DRIVER');
		$pic_upload = C('PICTURE_UPLOAD');
		$pic_upload['rootPath']="./Picture/Ueditor/";
        $info = $Picture->upload(
            $_FILES,
            $pic_upload,
            C('PICTURE_UPLOAD_DRIVER'),
            C("PICTURE_{$pic_driver}_CONFIG")
        );
		if($info){
			$data=array(
				'state'=>'SUCCESS',
				'url'=>$info['upfile']['path'],
				'title'=>$info['upfile']['savename'],
				'original'=>$info['upfile']['name'],
				'type'=>'.' . strtolower($info['upfile']['ext']),
				'size'=>$info['upfile']['size']
			);
		}else{
			$data=array('state'=>$Picture->getError());
		}
		return json_encode($data);
    }
	
	public function uploadvideo(){
        $Picture = D('File');
        $pic_driver = C('PICTURE_UPLOAD_DRIVER');
		$pic_upload = C('PICTURE_UPLOAD');
		$pic_upload['rootPath']="./Picture/Ueditor/";
        $info = $Picture->upload(
            $_FILES,
            $pic_upload,
            C('PICTURE_UPLOAD_DRIVER'),
            C("PICTURE_{$pic_driver}_CONFIG")
        );
		if($info){
			$data=array(
				'state'=>'SUCCESS',
				'url'=>$info['upfile']['path'],
				'title'=>$info['upfile']['savename'],
				'original'=>$info['upfile']['name'],
				'type'=>'.' . strtolower($info['upfile']['ext']),
				'size'=>$info['upfile']['size']
			);
		}else{
			$data=array('state'=>$Picture->getError());
		}
		return json_encode($data);
    }
	
	public function listimg($listSize=20){
		$pic_upload = C('PICTURE_UPLOAD');
		$path =substr($pic_upload['rootPath'],1)."Ueditor/";
		$allowFiles = str_replace(",", "|",$pic_upload['exts']);
		$size = isset($_GET['size']) ? htmlspecialchars($_GET['size']) : $listSize;
		$start = isset($_GET['start']) ? htmlspecialchars($_GET['start']) : 0;
		$end = $start + $size;
		$path = $_SERVER['DOCUMENT_ROOT'] . (substr($path, 0, 1) == "/" ? "":"/") . $path;
		$files = $this->getfiles($path, $allowFiles);
		if (!count($files)) {
			return json_encode(array(
				"state" => "no match file",
				"list" => array(),
				"start" => $start,
				"total" => count($files)
			));
		}
		$len = count($files);
		for ($i = min($end, $len) - 1, $list = array(); $i < $len && $i >= 0 && $i >= $start; $i--){
			$list[] = $files[$i];
		}
		$result = json_encode(array(
			"state" => "SUCCESS",
			"list" => $list,
			"start" => $start,
			"total" => count($files)
		));
		return $result;
	}
	public function getfiles($path, $allowFiles, &$files = array()){
		if (!is_dir($path)) return null;
		if(substr($path, strlen($path) - 1) != '/') $path .= '/';
		$handle = opendir($path);
		while (false !== ($file = readdir($handle))) {
			if ($file != '.' && $file != '..') {
				$path2 = $path . $file;
				if (is_dir($path2)) {
					getfiles($path2, $allowFiles, $files);
				} else {
					if (preg_match("/\.(".$allowFiles.")$/i", $file)) {
						$files[] = array(
							'url'=> substr($path2, strlen($_SERVER['DOCUMENT_ROOT'])),
							'mtime'=> filemtime($path2)
						);
					}
				}
			}
		}
		return $files;
	}
}
?>