<h1><?php echo lang("account_account"); ?> : <?php echo ($this->uri->segment(3) == "add" ? lang("account_add") : lang("account_edit")); ?></h1>

<?php echo form_open(current_url().($this->uri->segment(3) == "edit" ? "?id=$id" : "")); ?>
	<?php echo (isset($form_status) ? $form_status : ""); ?>
	<dl class="form_item">
		<dt><?php echo lang("account_username"); ?>:</dt>
		<dd><input type="text" name="account_username" value="<?php echo (isset($account_username) ? $account_username : ""); ?>" maxlength="255"<?php if ( $this->uri->segment(3) == "edit" ): ?> disabled="disabled"<?php endif; ?> /></dd>
		<dd class="comment"><span class="txt_require">*</span></dd>
		
		<dt><?php echo lang("account_email"); ?>:</dt>
		<dd><input type="text" name="account_email" value="<?php echo (isset($account_email) ? $account_email : ""); ?>" maxlength="255" /></dd>
		<dd class="comment"><span class="txt_require">*</span></dd>
		
		<dt><?php echo lang("account_password"); ?>:</dt>
		<dd><input type="password" name="account_password" value="" maxlength="255" /></dd>
		<dd class="comment"><?php if ( $this->uri->segment(3) == "add" ): ?><span class="txt_require">*</span><?php endif; ?><?php if ( $this->uri->segment(3) == "edit" ): ?> <?php echo lang("account_enter_current_if_change_password"); ?><?php endif; ?></dd>
		
		<?php if ( $this->uri->segment(3) == "edit" ): ?>
		<dt><?php echo lang("account_new_password"); ?>:</dt>
		<dd><input type="password" name="account_new_password" value="" maxlength="255" /></dd>
		<dd class="comment"><?php echo lang("account_enter_if_change_password"); ?></dd>
		<?php endif; ?>
		
		<dt><?php echo lang("account_fullname"); ?>:</dt>
		<dd><?php echo form_input("account_fullname", (isset($account_fullname) ? $account_fullname : "")); ?></dd>
		<dd class="comment"></dd>
		
		<dt><?php echo lang("account_birthdate"); ?>:</dt>
		<dd><?php echo form_input("account_birthdate", (isset($account_birthdate) ? $account_birthdate : "")); ?></dd>
		<dd class="comment"><?php echo lang("account_birthdate_format"); ?></dd>
		
		<dt><?php echo lang("account_signature"); ?>:</dt>
		<dd><?php echo form_textarea("account_signature", (isset($account_signature) ? $account_signature : "")); ?></dd>
		<dd class="comment"></dd>
		
		<dt><?php echo lang("account_level"); ?>:</dt>
		<dd>
			<select name="level_group_id">
				<option></option>
				<?php if ( isset($list_level) && is_array($list_level) ): ?>
				<?php foreach ( $list_level as $id => $item ): ?>
				<option value="<?php echo $id; ?>"<?php if( isset($level_group_id) && $level_group_id == $id ): ?> selected="selected"<?php endif; ?>><?php echo $item['level_name']; ?></option>
				<?php endforeach; ?>
				<?php endif; ?>
			</select>
		</dd>
		<dd class="comment"><span class="txt_require">*</span></dd>
		<dt><?php echo lang("account_status"); ?>:</dt>
		<dd>
			<select name="account_status" id="account_status">
				<option value="1"<?php if ( isset($account_status) && $account_status == '1' ): ?> selected="selected"<?php endif; ?>><?php echo lang("account_enable"); ?></option>
				<option value="0"<?php if ( isset($account_status) && $account_status == '0' ): ?> selected="selected"<?php endif; ?>><?php echo lang("account_disable"); ?></option>
			</select>
		</dd>
		<dd class="comment"><span class="txt_require">*</span></dd>
		<?php if ( $this->uri->segment(3) == "edit" ): ?>
		<dt class="account_status_text"><?php echo lang("account_status_reason"); ?>:</dt>
		<dd class="account_status_text"><input type="text" name="account_status_text" value="<?php echo (isset($account_status_text) ? $account_status_text : ""); ?>" /></dd>
		<?php endif; ?>
		
		<dt>&nbsp;</dt>
		<dd><?php echo form_submit("btn", lang("account_save")); ?></dd>
		
	</dl>
<?php echo form_close("\n"); ?>

<script type="text/javascript">
$(document).ready(function() {
	$("input[name=account_birthdate]").datepicker({ 
		dateFormat: 'yy-mm-dd',
		changeMonth: true,
		changeYear: true,
		yearRange: '1900:'+(new Date).getFullYear()
	});
	$("#account_status").change(function() {
		if ( $(this).val() == '0' ) {
			$(".account_status_text").show();
		} else {
			$(".account_status_text").hide();
		}
	});
	if ( $("#account_status").val() == '0' ) {
		$(".account_status_text").show();
	}
});// jquery document ready
</script>