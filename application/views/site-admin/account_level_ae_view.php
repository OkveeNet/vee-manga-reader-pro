<h1><?php echo ($this->uri->segment(3) == "add" ? lang("account_add_level") : lang("account_edit_level")); ?></h1>

<?php echo form_open(current_url().($this->uri->segment(3) == "edit" ? "?id=$id" : "")); ?>
	<?php echo (isset($form_status) ? $form_status : ""); ?>

	<dl class="form_item">
		<dt><?php echo lang("account_level"); ?>:</dt>
		<dd><input type="text" name="level_name" value="<?php echo (isset($level_name) ? $level_name : ""); ?>" maxlength="255" /></dd>
		<dd class="comment"><span class="txt_require">*</span></dd>
		<dt><?php echo lang("account_level_description"); ?>:</dt>
		<dd><input type="text" name="level_description" value="<?php echo (isset($level_description) ? $level_description : ""); ?>" maxlength="255" /></dd>
		<dt>&nbsp;</dt>
		<dd><?php echo form_submit("btn", lang("account_save")); ?></dd>
	</dl>

<?php echo form_close("\n"); ?>