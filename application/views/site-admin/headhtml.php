<?php
//load helper (some helper no need to load because it's already load in controller)
$this->load->helper('html');
// start document
echo doctype('html5');
?>

<html lang="en">
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<title><?php if ( isset($page_title) ) {echo $page_title;} ?></title>
		<meta charset="utf-8" />
		<link href="<?php echo base_url(); ?>client/themes/admin/style.css" media="all" rel="stylesheet" type="text/css" />
		<link href="<?php echo base_url(); ?>client/themes/admin/admin-jquerytools.css" rel="stylesheet" type="text/css" media="screen" />
		<link href="<?php echo base_url(); ?>client/js/jquery.ui/css/smoothness/jquery-ui.css" rel="stylesheet" type="text/css" media="screen" />
		<link href="<?php echo base_url(); ?>client/js/superfish/css/admin-superfish.css" rel="stylesheet" type="text/css" media="screen" />
		<link href="<?php echo base_url(); ?>favicon.ico" rel="shortcut icon" />
		
		<script src="<?php echo base_url(); ?>client/js/jquery.js" type="text/javascript"></script>
		<script src="<?php echo base_url(); ?>client/js/jquery.ui/jquery-ui.js" type="text/javascript"></script>
		<script src="<?php echo base_url(); ?>client/js/superfish/hoverIntent.js" type="text/javascript"></script>
		<script src="<?php echo base_url(); ?>client/js/superfish/superfish.js" type="text/javascript"></script>
		<script src="<?php echo base_url(); ?>client/js/superfish/supersubs.js" type="text/javascript"></script>
		<script src="<?php echo base_url(); ?>client/js/jquery.tools.min.js" type="text/javascript"></script>
		<script src="<?php echo base_url(); ?>client/js/tiny_mce/tiny_mce.js" type="text/javascript"></script>
		<script src="<?php echo base_url(); ?>client/js/admin.js" type="text/javascript"></script>
	</head>

	<body>

