<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<title><?php echo (isset($page_title) ? $page_title : ""); ?></title>
		<?php
		if ( isset($page_metatag) && is_array($page_metatag) ) {
			echo "<!-- + meta tag -->\n";
			foreach ( $page_metatag as $key => $item ) {
				echo $item;
			}
			echo "<!-- end + meta tag -->\n";
		}
		?>
		
		<?php echo link_tag(base_url()."client/js/jquery.ui/css/smoothness/jquery-ui.css"); ?> 
		<?php echo link_tag(base_url()."client/960/min/reset.css"); ?> 
		<?php echo link_tag(base_url()."client/960/min/text.css"); ?> 
		<?php echo link_tag(base_url()."client/960/min/960.css"); ?> 
		<?php echo link_tag(base_url()."client/themes/default/style.css"); ?> 
		<?php
		if ( isset($page_linktag) && is_array($page_linktag) ) {
			echo "<!-- + link tag -->\n";
			foreach ( $page_linktag as $key => $item ) {
				echo $item . "\n";
			}
			echo "<!-- end + link tag -->\n";
		}
		?>
		
		<script src="<?php echo base_url(); ?>client/js/jquery.js" type="text/javascript"></script>
		<script src="<?php echo base_url(); ?>client/js/jquery.ui/jquery-ui.js" type="text/javascript"></script>
		<script src="<?php echo base_url(); ?>client/js/html5.js" type="text/javascript"></script>
		<?php
		if ( isset($page_scripttag) && is_array($page_scripttag) ) {
			echo "<!-- + script tag -->\n";
			foreach ( $page_scripttag as $key => $item ) {
				echo $item;
			}
			echo "<!-- end + script tag -->\n";
		}
		?>
		
	</head>
	
	<body>
		
		
