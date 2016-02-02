<?php 
class CacheComponent{

	private static $mem = null;

	private function __construct(){
		
	}
	//单例模式
	public static function getInstance(){
		if(!self::$mem instanceof Memcache){
			self::$mem = new Memcache();
		}
		self::$mem->connect('localhost',11211);
		return self::$mem;
	}

	public static function getMemcache($class,$func,$params,$key,$config = array('expire' => null,'isMap'=>null )){
		//初始化缓存设置标志
		$setCacheFlag = false;

		if (empty($class) || empty($func) || empty($params) || empty($key)) {
			
			return false;
		}
		//如果是刷新则取缓存
		if (self::refreshCache()) {
			$result = call_user_func_array(array($class,$func), $params);
			$setCacheFlag = true;
		}else{
			$result  = self::getInstance()->get($key);
		}
		//结果不存在就直接取缓存
		if (!$result && !self::refreshCache()) {
			$result = call_user_func_array(array($class,$func), $params);
			$setCacheFlag = true;
		}

		//设置缓存，两种方式。缓存多条数据和缓存整块数据
		if ($setCacheFlag && $config['expire'] && $config['isMap']) {
			foreach ($result as $memKey => $resultObj) {
				self::getInstance()->set($memKey,$resultObj,$config['expire']);
			}
		}else if($setCacheFlag && $config['expire']){
			self::getInstance()->set($key,$result,$config['expire']);
		}

		return $result;

	}
	//刷新缓存
	public static function refreshCache(){
		if (isset($_REQUEST['refreshCache']) && $_REQUEST['refreshCache'] == 1) {
			//这里可以考虑封装一个获取刷新缓存的人的ip，并且记录。还可以增加记录刷新缓存时间
			return true;
		}
		return false;
	}
	
}

class DataDao{
	//此处应该是封装好的数据接口
	public function getDataFromMySQL($id){



		return array(1,2,3,4,5,$id);
	}
}


$result = CacheComponent::getMemcache('DataDao','getDataFromMySQL',array(10),'fanchao',array('expire'=>60));

var_dump($result);
 ?>