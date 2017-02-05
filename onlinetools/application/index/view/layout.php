<!DOCTYPE html>
<html>
{include file="public/header" /}
<body>
<nav class="navbar navbar-inverse navbar-fixed-top">
	<div class="container-fluid">
		{include file="public/headerbar" /}
	</div>
</nav>
<div class="container-fluid">
<div class="row">
	<div class="col-sm-2 col-md-2 sidebar">
		<ul class="nav nav-sidebar">
			{include file="public/sidebar" /}
		</ul>
	</div>
	<div class="col-sm-10 col-sm-offset-2 col-md-10 col-md-offset-2 main">
		{__CONTENT__}
	</div>
</div>
</div>
</body>
{include file="public/footer" /}
</html>