<!DOCTYPE html>
<html lang="en">
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7">
	<title>EveryDay :: <?=$page_title;?></title>
	<link type="text/css" rel="stylesheet/less" href="/styles/bootstrap.less" />
	<link type="text/css" rel="stylesheet/less" href="/styles/responsive.less" />
	<script>
	less={}; 
	//less.env = "<?=ENVIRONMENT;?>"; 
	</script>
	<script src="/styles/less.js"></script>
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.7/jquery.min.js"></script>
	<link rel="shortcut icon" href="http://www.everyday-everywhere.org/images/favicon.ico">
</head>
<body class="<?=ENVIRONMENT;?> <?=$this->data['uri_data']['controller'];?>-<?=$this->data['uri_data']['function'];?>">

	<div id="page_wrapper">
		<?=$content;?>
	</div>
</body>
</html>