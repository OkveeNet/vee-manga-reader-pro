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

		
		<h1>okvee web starter kit.</h1>
		<p>ไฟล์ที่แสดงผลนี้คือไฟล์ของส่วน views ซึ่งอยู่ใน codeigniter views. <br />
		คุณสามารถใช้ในรูปแบบ theme ได้โดยลบหรือย้ายไฟล์นี้ใน CI views/default ไปไว้ใน client/themes/default.</p>
		
		<div style="border: 1px dashed burlywood; float:left; margin: 20px 20px 20px 0; min-height: 400px; width:400px;">
			<p>ลิ้งค์ตัวอย่างที่จำเป็นสำหรับการเข้าหน้า admin, สมัครสมาชิก, บันทึกเข้า, บันทึกออก, แก้ไขข้อมูลส่วนตัว และตัวอย่างลิ้งค์ไปโมดูลบล็อก</p>

			<?php echo anchor("site-admin", "Site admin"); ?> | 
			<?php
			if ( isset($is_member_login) && $is_member_login == true ) {
				echo anchor("account/profile", "Edit profile") . " | ";
				echo anchor("account/logout", "Logout") . " ";
			} else {
				echo anchor("account/register", "Register") . " | ";
				echo anchor("account/login", "Login") . " ";
			}
			?>
			 | <strong><?php echo anchor("blog", "View blog"); ?></strong>
		</div>
		
		 <div style="float: left; margin: 20px 0; width:300px;">
			 <?php echo (isset($blog_quicklist) ? $blog_quicklist : ""); ?>
		 </div>
		
		<div style="clear: both;"></div>
		
		
		<!-- end page -->


	</body>
</html>