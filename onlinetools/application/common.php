<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

//用户自定义
define('ROOTPATH', __DIR__);
define('UPLOAD_ROOT', __DIR__ . '/../public/static/upload/');
define('WEB_ROOT', '/index/');
// 应用公共文件
function uploadfile($savename, $rpath, $extension='png', $maxsize = 5242880 )
{
	$config = [
		'maxSize'	=> $maxsize,
		'exts'		=> ['jpg', 'gif', 'png', 'jpeg', 'bmp', 'tif'],
		'autoSub'	=> true,
		'subName'	=> ['date', 'Y-m-d'],
		'rootPath'	=> UPLOAD_ROOT,
		'savePath'	=> $rpath,
//		'saveExt'	=> $extension,
		'hash'		=> true,
		'callback'	=> true,
		'driver'	=> 'Local',
		'saveName'	=> $savename
	];
	$uploader = new org\Upload($config);
	$info = $uploader->upload();
	$error = htmlspecialchars($uploader->getError());
	if(!$info)
		return ['info' => NULL, 'error'=>$error];
	else
		return ['info'=>current($info), 'error'=>$error];
}