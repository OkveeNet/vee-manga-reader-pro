<div class="account_edit_profile">
	<h1><?php echo lang("account_edit_profile"); ?></h1>

	<?php echo form_open_multipart(current_url()); ?>
		<div class="form_result"><?php echo (isset($form_status) ? $form_status : ""); ?></div>

		<dl class="form_item">
			<dt><?php echo lang("account_username"); ?>:</dt>
			<dd><input type="text" name="username" value="<?php echo (isset($username) ? $username : ""); ?>" maxlength="255" disabled="disabled" /></dd>
			<dd class="comment"><span class="txt_require">*</span></dd>

			<dt><?php echo lang("account_email"); ?>:</dt>
			<dd><input type="text" name="email" value="<?php echo (isset($email) ? $email : ""); ?>" maxlength="255" /></dd>
			<dd class="comment"><span class="txt_require">*</span></dd>

			<dt><?php echo lang("account_password"); ?>:</dt>
			<dd><input type="password" name="password" value="" maxlength="255" /></dd>
			<dd class="comment"><?php echo lang("account_enter_current_if_change_password"); ?></dd>

			<dt><?php echo lang("account_new_password"); ?>:</dt>
			<dd><input type="password" name="new_password" value="" maxlength="255" /></dd>
			<dd class="comment"><?php echo lang("account_enter_if_change_password"); ?></dd>

			<dt><?php echo lang("account_fullname"); ?>:</dt>
			<dd><?php echo form_input("fullname", (isset($fullname) ? $fullname : "")); ?></dd>
			<dd class="comment"></dd>

			<dt><?php echo lang("account_birthdate"); ?>:</dt>
			<dd><?php echo form_input("birthdate", (isset($birthdate) ? $birthdate : "")); ?></dd>
			<dd class="comment"><?php echo lang("account_birthdate_format"); ?></dd>

			<?php if ( $this->config_model->load("allow_avatar") == '1' ): ?>
			<dt><?php echo lang("account_avatar"); ?>:</dt>
			<?php if ( isset($avatar) && $avatar != null ): ?><dd class="display_avatar"><img src="<?php echo base_url().$avatar; ?>" alt="<?php echo lang("account_avatar"); ?>" /> <?php echo anchor($this->uri->segment(1)."/".$this->uri->segment(2)."/removeavatar", lang("account_remove_avatar")); ?></dd><?php endif; ?>
			<?php if ( isset($avatar) && $avatar != null ): ?><dt></dt><?php endif; ?>
			<dd><?php echo form_upload("avatar"); ?></dd>
			<dd class="comment"><?php echo sprintf(lang("account_avatar_desc"), $this->config_model->load("avatar_size")); ?></dd>
			<?php endif; ?>

			<dt><?php echo lang("account_signature"); ?>:</dt>
			<dd><?php echo form_textarea("signature", (isset($signature) ? $signature : "")); ?></dd>
			<dd class="comment"></dd>

			<dt>&nbsp;</dt>
			<dd><?php echo form_submit("btn", lang("account_save")); ?></dd>

		</dl>
	<?php echo form_close("\n"); ?>
	<div class="clear"></div>
	
	<script type="text/javascript">
	$(document).ready(function() {
		$("input[name=birthdate]").datepicker({ 
			dateFormat: 'yy-mm-dd',
			changeMonth: true,
			changeYear: true,
			yearRange: '1900:'+(new Date).getFullYear()
		});
	});
	</script>
</div>