<?php
namespace app\index\controller;
use think\Controller;
class Fft extends Controller
{
	public function index()
	{
		return $this->fetch();
	}
	public function imgupload()
	{
		$data['info'] = uploadfile(
			((string)time()).'_'.(string)rand(),
			"fft/userfile/"
		);
		ob_clean();   //清空输出缓存，避免干扰json
		return $data;
	}
	public function imgfft()
	{
		$imgpath = rtrim(input('imgpath'), '\\/');
		$imgname = trim(input('imgname', '\\/'));
		$script = str_replace('\\', '/', (
			"/mnt/softwares/anaconda/bin/python ".
			ROOTPATH.'/../application/index/tools/fft.py '.
			UPLOAD_ROOT.$imgpath.'/ '.
			$imgname
		));
		exec($script, $res);
		if($res[0] == 'successful')
		{
			return [
				'res' => $res[0],
				'input_img_fft' => $res[1],
				'output_img_fft' => $res[2],
			];
		}
		else
			return ['res' => $res[0]];
	}
}
