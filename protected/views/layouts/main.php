<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="en" />

	<link rel="shortcut icon" href="/images/favicon.ico"></link>

	<script type="text/javascript" src=" /js/jquery-2.1.1.min.js"></script>

	<script src="/js/jquery.lazyload.js" type="text/javascript"></script>


	<link href="/3rd/bootstrap-3.2.0-dist/css/bootstrap.min.css" rel="stylesheet">

	<!--link href="/bootstrap-3.2.0-dist/css/bootstrap-theme.min.css" rel="stylesheet"-->
	<link href="/css/gaga.css" rel="stylesheet">

	<script src="/3rd/bootstrap-3.2.0-dist/js/bootstrap.min.js"></script>

	<Meta name="Keywords" Content="最新音乐、热门音乐">
	<Meta name="Description" Content="显示当前最新音乐">
<title>
	funmusic - listen easy</title>	

</head>

<body>
<div class="content">
	<header class="topbar">
		<?php if($this->g_guest) {?>
		
			<a class="login" href="/Admin/adminuser/login">login</a>
		<?php } else {?>
			<a class="logout" href="/Admin/adminuser/logout">logout</a>
		<?php }?>

	</header> 
 
        <?php echo $content; ?>

</div>
	<footer class="footer">
		<div class="source">

                	<a href="http://y.qq.com/#type=index" target="_blank">QQ音乐</a>
			<a href="http://www.xiami.com/" target="_blank">虾米音乐</a>
        	        <a href="http://music.163.com/#" target="_blank">网易音乐</a>

		</div>

		<div class="contact">
			contact: techang2009@126.com
		</div>
	</footer>

</body>

</html>
