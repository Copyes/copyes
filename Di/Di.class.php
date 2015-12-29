<?php 
class Di implements ArrayAccess{
	//服务列表
	private $_bindings = array();
	//已经实例化的服务列表
	private $_instances = array();
	//获取服务
	public function get($name,$params = array()){
		//先判断是否是已经实例过的服务
		if(isset($this->_instances[$name])){
			return $this->_instances[$name];
		}
		//判断是否已经注册过改服务了
		if(!isset($this->_bindings[$name])){
			return null;
		}
		//对象的具体注册内容
		$concreate = $this->_bindings[$name]['class'];

		$obj = null;
		//匿名函数方式
		if ($concreate instanceof Closure) {
			$obj = call_user_func_array($concreate, $params);
		}elseif(is_string($concreate)){//字符串方式
			if (empty($params)) {
				$obj = new $concreate;
			}else{
				//带参数的类实例化，使用反射
				$class = new ReflectionClass($concreate);
				$obj = $class->newInstanceArgs($params);
			}
		}
		//如果是共享服务，则写入_instances中，下回直接取用
		if ($this->_bindings[$name]['shared'] == true && $obj) {
			$this->_instances[$name] = $obj;
		}

		return $obj;
	}
	//检测是否是已经绑定了
	public function hasBind($name){
		return isset($this->_bindings[$name]) || isset($this->_instances[$name]);
	}
	//卸载服务
	public function remove($name){
		unset($this->_bindings[$name],$this->_instances[$name]);
	}
	//设置服务
	public function set($name,$class){
		$this->_registerService($name,$class);
	}
	//设置共享服务
	public function setShared($name,$class){
		$this->_registerService($name,$class,true);
	}
	//注册服务
	public function _registerService($name,$class,$shared = false){
		$this->remove($name);
		if (!($class instanceof Closure) &&  is_object($class)) {
			$this->_instances[$name] = $class;
		}else{
			$this->_bindings[$name] = array('class'=>$class,'shared'=>$shared);
		}
	}
	//ArrayAccess接口，检测服务是否存在
	public function offsetExists($offset){
		return $this->hasBind($offset);
	}
	//ArrayAccess接口，以Di[$name]方式获取服务
	public function offsetGet($offset){
		return $this->get($offset);
	}
	//ArrayAccess接口，以di[$name]方式注册服务，非共享
	public function offsetSet($offset,$value){
		return $this->set($offset,$value);
	}
	//ArrayAccess接口，以unset（di[$name]）的方式卸载服务
	public function offsetUnset($offset){
		return $this->remove($offset);
	}
}
 ?>