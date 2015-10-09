<?php echo doctype("html5") . "\n"; ?>
<html>
	<head>
		<?php echo meta('Content-type', 'text/html; charset=utf-8', 'equiv'); ?>
		<title><?php echo (isset($page_title) ? $page_title : ""); ?></title>
		<?php
		if ( isset($page_metatag) && is_array($page_metatag) ) {
			echo "<!-- additional meta tag -->\n";
			foreach ( $page_metatag as $key => $item ) {
				echo $item;
			}
			echo "<!-- end additional meta tag -->\n";
		}
		?>
		
		<?php echo link_tag(base_url()."client/themes/admin/style.css"); ?>
		<?php echo link_tag(base_url()."client/js/jquery.ui/css/smoothness/jquery-ui.css"); ?>
		<?php echo link_tag(base_url()."client/themes/admin/superfish.css"); ?>
		<?php
		if ( isset($page_linktag) && is_array($page_linktag) ) {
			echo "<!-- additional link tag -->\n";
			foreach ( $page_linktag as $key => $item ) {
				echo $item . "\n";
			}
			echo "<!-- end additional link tag -->";
		}
		?>
		
		
		<script src="<?php echo base_url(); ?>client/js/jquery.js" type="text/javascript"></script>
		<script src="<?php echo base_url(); ?>client/js/jquery.ui/jquery-ui.js" type="text/javascript"></script>
		<script src="<?php echo base_url(); ?>client/js/admin.js" type="text/javascript"></script>
		<script src="<?php echo base_url(); ?>client/js/superfish/hoverIntent.js" type="text/javascript"></script>
		<script src="<?php echo base_url(); ?>client/js/superfish/superfish.js" type="text/javascript"></script>
		<script src="<?php echo base_url(); ?>client/js/superfish/supersubs.js" type="text/javascript"></script>
		<?php
		if ( isset($page_scripttag) && is_array($page_scripttag) ) {
			echo "<!-- additional script tag -->\n";
			foreach ( $page_scripttag as $key => $item ) {
				echo $item;
			}
			echo "<!-- end additional script tag -->\n";
		}
		?>
		
	</head>
	
	<body>


		<!-- start page -->
