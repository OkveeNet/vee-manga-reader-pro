<?php include("inc_header.php"); ?>
			<div class="grid_12">
				<?php echo (isset($page_content) ? $page_content : ""); ?> 
			</div>
			<div class="grid_4">
				<?php echo (isset($page_sidebar) ? $page_sidebar : ""); ?>
				<?php 
				$this->load->module("mostview");
				echo $this->mostview->index();
				?>
			</div>
			<div class="clear"></div>
<?php include("inc_footer.php"); ?>