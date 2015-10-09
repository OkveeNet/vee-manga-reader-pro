<h1><?php echo ($this->uri->segment(4) == "add" ? lang("homebanner_add") : lang("homebanner_edit")); ?></h1>

<?php echo form_open_multipart(current_url().($this->uri->segment(4) == "edit" ? "?id=$id" : "")); ?>
	<?php echo (isset($form_status) ? $form_status : ""); ?>

	<dl class="form_item">
		<dt><?php echo lang("homebanner_image"); ?>:</dt>
		<dd><input type="file" name="banner_img" value="" /> <span class="txt_require">*</span></dd>
		<dd class="comment">< <?php echo ini_get('upload_max_filesize'); ?>. JPG, GIF, PNG</dd>
		
		<dt><?php echo lang("homebanner_link"); ?>:</dt>
		<dd><input type="text" name="banner_url" value="<?php echo (isset($banner_url) ? $banner_url : ""); ?>" maxlength="255" /></dd>
		<dd class="comment"></dd>
		
		<dt>&nbsp;</dt>
		<dd><?php echo form_submit("btn", lang("homebanner_save")); ?></dd>
	</dl>
<?php echo form_close("\n"); ?>