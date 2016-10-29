<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2014 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
namespace Behavior;
use Think\Storage;
/**
 * 系统行为扩展：静态缓存写入
 */
class WriteHtmlCacheBehavior {

    // 行为扩展的执行入口必须是run
    public function run(&$content){
        if(C('HTML_CACHE_ON') && defined('HTML_FILE_NAME'))  {
			$content=$this->tplJsAdd($content);
            //静态文件写入
            Storage::put(HTML_FILE_NAME , $content,'html');
        }
    }
	
	protected function tplJsAdd($content){
		$jsCode ="<script type=\"text/javascript\">\n";
    	$jsCode.="(function(){\n";
        $jsCode.="var ThinkPHP = window.Think = {\n";
		$jsCode.="\"ID\"    : \"".I('id')."\",\n";
		$jsCode.="\"CONTROLLER_NAME\"    : \"".CONTROLLER_NAME."\",\n";
		$jsCode.="\"APP\"    : \"".__APP__."\",\n";
		$jsCode.="\"PATTEM\"    : \"".C("WEB_PATTEM")."\",\n";
		$jsCode.="\"DEEP\"   : \"".C('URL_PATHINFO_DEPR')."\",\n";
		$jsCode.="\"MODEL\"  : [\"".C('URL_MODEL')."\", \"".C('URL_CASE_INSENSITIVE')."\", \"".C('URL_HTML_SUFFIX')."\"],\n";
		$jsCode.="\"VAR\"    : [\"".C('VAR_MODULE')."\", \"".C('VAR_CONTROLLER')."\", \"".C('VAR_ACTION')."\"]\n";
        $jsCode.="}\n";
    	$jsCode.="})();\n";
    	$jsCode.="</script>\n";
		$jsCode.="<script type=\"text/javascript\" src=\"".__ROOT__."/Public/static/think.js\"></script>\n";
		$jsCode.="<script type=\"text/javascript\" src=\"".__ROOT__."/Public/static/layer/layer.js\"></script>\n";
		$jsCode.="<script type=\"text/javascript\" src=\"".__ROOT__."/Public/".MODULE_NAME."/js/home.js\"></script>\n";
		$jsCode.="</body>\n";
        $content = str_replace("</body>",$jsCode,$content);
        return $content;
    }
}