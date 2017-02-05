<img id="input_img_fft" src="/static/upload/fft/input.png" alt="输入图像" class="img-rounded" >
<img id="output_img_fft" src="/static/upload/fft/output.png" alt="输出图像" class="img-rounded" >
<br/><br/>
<div class="container-fluid row">
	<button class="btn btn-success" id="imageupload_button">选择图像</button>
</div>
<form id="imageupload_form" method="post" action="/index/fft/imgupload" enctype="multipart/form-data" style="display:none">
	<input type="file" id="imageupload_input" name="user_file" style="display:none">
</form>
<script>
	$(document).ready(function(){
		if($.cookie('input_img_fft')) {
			$('#input_img_fft').attr('src', $.cookie('input_img_fft'));
		}
		if($.cookie('output_img_fft'))
		{
			$('#output_img_fft').attr('src', $.cookie('output_img_fft'));
		}
	});
	$(document).off('click',  '#imageupload_button');
	$(document).on('click',  '#imageupload_button', function(){
		var file_button = $('#imageupload_input');

		if(/msie/i.test(navigator.userAgent.toLowerCase()))
		{
			file_button.click();
		}
		else
		{
			var a=document.createEvent("MouseEvents");//FF的处理
			a.initEvent("click", true, true);
			file_button[0].dispatchEvent(a);
		}
		return false;
	});
	$(document).off('change',  '#imageupload_input');
	$(document).on('change',  '#imageupload_input', function(){
		var upload_file_input = $(this)
		var upload_file_form = $('#imageupload_form');
		var upload_file_button = $('#imageupload_button');

		var upload_filepath = $.trim(upload_file_input.val());
		if(upload_filepath == null || upload_filepath == '' || typeof(upload_filepath) == 'undefined')
			return;
		var file_verify = checkfile(upload_file_input[0], 3145728);
		if(file_verify[0] == true)
		{
			if(file_verify[1] != null)
			{
				alertify.confirm(file_verify[1], function(e)
				{
					if(e)
					{
						DoUploadFile(
							upload_file_input,
							upload_file_form,
							upload_file_button
						);
					}
					else
						upload_file_input.val('');

				})
			}
			else
			{
				DoUploadFile(
					upload_file_input,
					upload_file_form,
					upload_file_button
				);
			}
		}
		else
		{
			alertify.alert(file_verify[1]);
			upload_file_input.val('');
		}
	});

	function checkfile(upload_input, maxfilesize) {
		var maxsizeMB = Math.ceil(maxfilesize / 1024 / 1024);
		var errMsg = "上传的附件文件不能超过" + maxsizeMB + "MB";
		var tipMsg = "您的浏览器暂不支持上传前计算上传文件的大小，请确保上传文件不要超过" + maxsizeMB + "M，建议使用Chrome、FireFox等浏览器最新版本。<br/>确认后继续上传";
		var browserCfg = {};
		var ua = window.navigator.userAgent;
		if (ua.indexOf("MSIE") >= 1) {
			browserCfg.ie = true;
		} else if (ua.indexOf("Firefox") >= 1) {
			browserCfg.firefox = true;
		} else if (ua.indexOf("Chrome") >= 1) {
			browserCfg.chrome = true;
		}
		try {
			var obj_file = upload_input;
			if (obj_file.value == "") {
				return [false, "请先选择上传文件"];
			}
			var filesize = 0;
			if (browserCfg.firefox || browserCfg.chrome) {
				filesize = obj_file.files[0].size;
			} else if (browserCfg.ie) {
				var obj_img = document.getElementById('tempimg');
				obj_img.dynsrc = obj_file.value;
				filesize = obj_img.fileSize;
			} else {
				return [true, tipMsg];
			}
			if (filesize == -1) {
				return [true, tipMsg];
			} else if (filesize > maxfilesize) {
				return [false, errMsg];
			} else {
				return [true, null];
			}
		} catch (e) {
			return [true, null];
		}
	}

	function DoUploadFile(upload_file_input, upload_file_form, upload_file_button, downhtml)
	{
		upload_file_form.ajaxForm({
			beforeSend: function() {
				upload_file_button.attr('disabled', true);
				var percentVal = '0%';
				upload_file_button.text('正在上传'+percentVal);
			},
			uploadProgress: function(event, position, total, percentComplete) {
				var percentVal = percentComplete + '%';
				upload_file_button.text('正在上传'+percentVal);
			},
			success: function() {
				var percentVal = '100%';
				upload_file_button.text("上传完毕");
			},
			complete: function(e) {
				data = JSON.parse(e.responseText)['info'];
				console.log(data);
				if(data.hasOwnProperty('error') && data['error'].length > 0)
				{
					alertify.error(data['error']);
				}
				else
				{
					alertify.success("上传成功，正在处理。3秒后可再次选择其他图片。");
					$.get(
						'/index/fft/imgfft',
						{
							'imgpath': data['info']['savepath'],
							'imgname': data['info']['savename']
						},
						function(res)
						{
							if(res['res'] == 'successful')
							{
								$('#input_img_fft').attr('src', "/static/upload/" + data['info']['savepath'] + res['input_img_fft']);
								$('#output_img_fft').attr('src', "/static/upload/" + data['info']['savepath'] + res['output_img_fft']);
								$.cookie('input_img_fft', "/static/upload/" + data['info']['savepath'] + res['input_img_fft'], { expires: 7 });
								$.cookie('output_img_fft', "/static/upload/" + data['info']['savepath'] + res['output_img_fft'], { expires: 7 });
							}
							else
							{
								alertify.error("处理失败");
							}
							console.log(data);
						}
					)
				}
				button_delay(upload_file_button, 3, '选择图像', '选择图像');
			}
		});
		upload_file_form.submit();
		upload_file_input.val('');
	}

	function button_delay(button, delay, ori, tips) {
		button.attr('disabled', true);
		if (ori != null)
			button.text(tips ? (tips + "(" + delay + ")") : (delay + '秒后可再次提交'));
		var timer = setInterval(
			function() {
				delay--;
				if (delay <= 0) {
					if (ori != null)
						button.text(ori);
					button.removeAttr('disabled');
					clearInterval(timer);
					return;
				}
				if (ori != null)
					button.text(tips ? (tips + "(" + delay + ")") : (delay + '秒后可再次提交'));
			},
			1000
		);
	}
</script>
<style type="text/css">
	.progress { position:relative; width:100%; border: 1px solid #ddd; padding: 1px; border-radius: 3px; }
	.bar { background-color: #31b0d5; width:0%; height:20px; border-radius: 3px; }
	.percent { color: black; position:absolute; display:inline-block; top:3px; left:48%; }
	.img-rounded {max-width: 512px; max-height: 512px;}
</style>