<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta charset="utf-8" />
		<title><?php if ( isset($page_title) ) {echo $page_title;} else {echo "vmr pro";} ?></title>
		<meta name="generator" content="Vee's manga reader" />
		<?php if ( isset($meta_tags) && is_array($meta_tags) ): ?>
		<!--additional meta tag-->
		<?php foreach ( $meta_tags as $item ) {
			echo $item;
		}// endforeach; ?>
		<!--end additional meta tag-->
		<?php endif; ?>

		<link href="<?php echo base_url(); ?>client/themes/default/style.css" media="screen" rel="stylesheet" type="text/css" />
		<link href="<?php echo base_url(); ?>favicon.ico" rel="shortcut icon" type="image/ico" />
		<?php if ( isset($link_files) && is_array($link_files) ): ?>
		<!--additional link-->
		<?php foreach ( $link_files as $item ) {
			echo $item."\n";
		}// endforeach; ?>
		<!--end additional link-->
		<?php endif; ?>

		<script src="<?php echo base_url(); ?>client/js/jquery.js" type="text/javascript"></script>
		<?php if ( isset($js_files) && is_array($js_files) ) : ?>
		<!--additional js-->
		<?php foreach ( $js_files as $item ): ?>
		<script src="<?php echo $item; ?>" type="text/javascript"></script>
		<?php endforeach; ?>
		<!--end additional js-->
		<?php endif; ?>

		<?php /** you may put your javascript here eg. analytic, what ever. */ ?>

	</head>
	<body>
