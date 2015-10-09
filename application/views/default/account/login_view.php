<div class="account_one_column">
	<h1><?php echo lang("account_login"); ?></h1>

	<?php echo form_open(); ?>
		<div class="form_result"><?php echo (isset($form_status) ? $form_status : ""); ?></div>

		<dl>
			<dt><?php echo lang("account_username"); ?>:</dt>
			<dd><input type="text" name="username" value="<?php echo (isset($username) ? $username : ""); ?>" /></dd>
			<dd class="comment"></dd>
			<dt><?php echo lang("account_password"); ?>:</dt>
			<dd><input type="password" name="password" value="<?php echo (isset($password) ? $password : ""); ?>" /></dd>
			<dd class="comment"></dd>
			<?php if ( isset($show_captcha) && $show_captcha == true ): ?>
			<dt class="captcha_fieldset">&nbsp;</dt>
			<dd class="captcha_fieldset"><img src="<?php echo base_url(); ?>client/images/securimage_show.php" alt="securimage" id="captcha" />
				<a href="#" onclick="document.getElementById('captcha').src = '<?php echo base_url(); ?>client/images/securimage_show.php?' + Math.random(); return false"><img src="<?php echo base_url(); ?>client/images/reload.gif" alt="" /></a>
			</dd>
			<dt class="captcha_fieldset"><?php echo lang("account_captcha"); ?>:</dt>
			<dd class="captcha_fieldset"><?php echo form_input("captcha", (isset($captcha) ? $captcha : "")); ?></dd>
			<?php endif; ?>
			<dt><label for="remember"><?php echo lang("account_remember_login"); ?>:</label></dt>
			<dd><input type="checkbox" name="remember" value="yes" id="remember" /></dd>

			<dt>&nbsp;</dt>
			<dd><?php echo form_submit("btn", lang("account_login")); ?></dd>
		</dl>
		<div class="clear"></div>

		<?php echo anchor("account/forgetpw", lang("account_forget_userpass")); ?>, <?php echo anchor("account/register", lang("account_register")); ?>
	<?php echo form_close("\n"); ?>
	<div class="clear"></div>
</div>