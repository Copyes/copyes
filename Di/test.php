<?php 
header("Content-type:text/html;charset=utf8");
class A{
	public $name;
	public $age;
	public function __construct($name=''){
		$this->name = $name;
	}
}
include "Di.class.php";
$di = new Di();
//匿名函数方式注册一个a1服务
$di->setShared('a1',function($name=''){
	return new A($name);
});
//直接以类名的方式注册
$di->set('a2','A');
//直接传入实例化的对象
$di->set('a3',new A('小超'));

$a1 = $di->get('a1',array('小李'));
echo $a1->name,"<br/>";
$a1_1 = $di->get('a1',array('小王'));
echo $a1->name,"<br/>";
echo $a1_1->name,"<br/>";

$a2 = $di->get('a2',array("小张"));
echo $a2->name."<br/>";//小张
$a2_1 = $di->get('a2',array("小徐"));
echo $a2->name."<br/>";//小张
echo $a2_1->name."<br/>";//小徐

$a3 = $di['a3'];//可以直接通过数组方式获取服务对象
echo $a3->name."<br/>";//小超
 ?>