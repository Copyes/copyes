<?php 
class verifyClass{

	function __construct(){

	}

	public function getVerify($str,$pixel,$line){
		//创建画布和基本颜色
		$width = 80;
		$height = 20;
		$fontsize = mt_rand(14,18);
		$angle = mt_rand(-15,15);
		//$fontFiles = array('STSONG.TTF');
		$fontFile = "C:Windows/Fonts/Segoe Print/segoeprb.ttf";
		$image = imagecreatetruecolor($width, $height);
		$white = imagecolorallocate($image, 255, 255, 255);
		$black = imagecolorallocate($image, 0, 0, 0);
		//生成一个对应白色的图
		imagefilledrectangle($image, 1, 1, $width-2, $height-2, $white);
		$len = strlen($str);

		for ($i=0; $i < $len; ++$i) { 
			$x = $i*$fontsize + 5;
			$y = mt_rand(20,26);
			$fontColor = imagecolorallocate($image, mt_rand(50,90), mt_rand(90,180), mt_rand(200,255));
			$text = substr($str, $i,1);
			imagettftext($image, $fontsize, $angle, $x, $y, $fontColor, $fontFile, $text);
		}

		if ($pixel) {
			for ($i=1; $i < $pixel; ++$i) { 
				imagesetpixel($image, mt_rand ( 0, $width - 1 ), mt_rand ( 0, $height - 1 ), $black);
			}
		}

		if ($line) {
			for($i = 1; $i < $line; $i ++) {
			$color = imagecolorallocate ( $image, mt_rand ( 50, 90 ), mt_rand ( 80, 200 ), mt_rand ( 90, 180 ) );
			imageline ( $image, mt_rand ( 0, $width - 1 ), mt_rand ( 0, $height - 1 ), mt_rand ( 0, $width - 1 ), mt_rand ( 0, $height - 1 ), $color );
			}
		}
	}
}



 ?>