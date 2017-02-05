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
		$rpath = './fft/userfile/';
		$extension = ['jpg', 'gif', 'png', 'jpeg', 'bmp'];
		$savename = trim(input('filename'));
		$res = uploadfile($savename, $rpath, $extension);
		if(!$res['info'])
		{
			$data['wrongcode'] = 'file_error';
			$data['wrongmsg'] = $res['error'];
		}
		else
		{
			$data['src'] = '/static/upload' . $rpath . $res['info']['savename'];
		}
		ob_clean();   //清空输出缓存，避免干扰json

		return $data;
	}
}
