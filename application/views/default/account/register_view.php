<div class="account_one_column">
	<h1><?php echo lang("account_register"); ?></h1>

	<?php echo form_open(); ?>
		<div class="form_result"><?php echo (isset($form_status) ? $form_status : ""); ?></div>
		<dl>
			<dt><?php echo lang("account_username"); ?>:</dt>
			<dd><input type="text" name="username" value="<?php echo (isset($username) ? $username : ""); ?>" /></dd>
			<dd class="comment"></dd>
			<dt><?php echo lang("account_email"); ?>:</dt>
			<dd><input type="text" name="email" value="<?php echo (isset($email) ? $email : ""); ?>" /></dd>
			<dd class="comment"></dd>
			<dt class="captcha_fieldset">&nbsp;</dt>
			<dd class="captcha_fieldset"><img src="<?php echo base_url(); ?>client/images/securimage_show.php" alt="securimage" id="captcha" />
				<a href="#" onclick="document.getElementById('captcha').src = '<?php echo base_url(); ?>client/images/securimage_show.php?' + Math.random(); return false"><img src="<?php echo base_url(); ?>client/images/reload.gif" alt="" /></a>
			</dd>
			<dt class="captcha_fieldset"><?php echo lang("account_captcha"); ?>:</dt>
			<dd class="captcha_fieldset"><?php echo form_input("captcha", (isset($captcha) ? $captcha : "")); ?></dd>

			<dt>&nbsp;</dt>
			<dd><?php echo form_submit("btn", lang("account_submit")); ?></dd>
		</dl>
	<?php echo form_close("\n"); ?>
	<div class="clear"></div>
</div>