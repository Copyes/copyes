<?php 
class stringClass{
	
	function __construct(){

	}
	/*
	生成唯一的字符串的方法
	*/	
	public function  getUniqueName(){
		return md5(uniqid(microtime(true),true));
	}
	/*
	参数是文件名
	返回文件后缀名
	*/
	public function getFileExt($fileName){
		return strtolower(end(explode('.', $fileName)));		
	}
	/*
	生成验证码的的方法
	参数：验证码类型，验证码长度
	1:全数字，2，全字母，3：数字字母随机组合
	*/
	public function getVerify($type,$length){
		if (1 == $type) {
			$str  =  join('',range(0, 9));
		}elseif (2 == $type) {
			$str = join('',array_merge(range('a', 'z'),range('A', 'Z')));
		}elseif(3 == $type){
			$str = join('',array_merge(range(0, 9),range('A', 'Z'),range('a', 'z')));
		}
		if ($length > strlen($str)) {
			echo '验证码的字符串太短了';
			return false;
		}
		$str = str_shuffle($str);
		return substr($str, 0, $length);
	}
}




 ?>