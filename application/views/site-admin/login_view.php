<?php require(dirname(__FILE__).'/headhtml.php'); ?>

		<div class="page_login">
			<div id="logo"><img src="<?php echo base_url(); ?>client/images/logo.png" alt="logo" title="<?php echo lang('admin_administrator_log_in'); ?>" /></div>

			<?php if ( isset($frm_status) ) {echo $frm_status;} ?>
			<?php echo form_open($this->uri->segment(1)."/login/dologin"); ?>
				<?php /*<input type="hidden" name="token" value="<?php echo @$token; ?>" />*/ ?>
				<?php echo lang('admin_username'); ?>:
				<input type="text" name="username" value="<?php if ( isset($username) ) {echo $username;} ?>" />
				<?php echo lang('admin_password'); ?>:
				<input type="password" name="password" value="<?php if ( isset($password) ) {echo $password;} ?>" />
				<?php if ( isset($show_captcha) && $show_captcha == true ): ?>
				<div class="form_input">
					<img id="captcha" src="<?php echo base_url(); ?>client/images/securimage_show.php" alt="" />
					<a href="#" onclick="document.getElementById('captcha').src = '<?php echo base_url(); ?>client/images/securimage_show.php?' + Math.random(); return false"><img src="<?php echo base_url(); ?>client/images/reload.gif" alt="" /></a><br />
					<?php echo lang("admin_please_enter_text_see_above"); ?>:<br />
					<input type="text" name="captcha" value="" autocomplete="off" />
				</div>
				<?php endif; ?>
				<input type="submit" name="btnact" value="<?php echo lang('admin_log_in'); ?>" />
			<?php echo form_close("\n"); ?>

			<div class="standard_check">
				<script language="javascript" type="text/javascript">
				// <![CDATA[
				$(document).ready(function() {
					$("#login_js_check img").attr("src", "<?php echo base_url(); ?>client/images/yes.gif");
				});// jquery ready
				// ]]>
				</script>
				<span><?php echo lang('admin_web_browser'); ?><img src="<?php echo base_url(); ?>client/images/<?php if ( isset($browser_check) ) {echo $browser_check;} ?>.gif" alt="<?php if ( isset($browser_check) ) {echo $browser_check;} ?>" /></span>
				<span id="login_js_check"><?php echo lang('admin_javascript'); ?><img src="<?php echo base_url(); ?>client/images/no.gif" alt="javascript check" /></span>
				<span><?php echo lang('admin_flash'); ?><object width="16" height="16" data="<?php echo base_url(); ?>client/images/yes.swf" type="application/x-shockwave-flash" style="margin-left:2px; position:absolute; z-index:2;">
					<param name="data" value="<?php echo base_url(); ?>client/images/yes.swf" />
					<param name="src" value="<?php echo base_url(); ?>client/images/yes.swf" />
					</object>
					<img src="<?php echo base_url(); ?>client/images/no.gif" alt="flash check" style="z-index:1;" />
				</span>
			</div><!--.standard_check-->

			<?php /*<div class="reset_password">
				<p class="pointer link" onclick="show_resetpw()"><?php echo lang('admin_forget_password'); ?></p>
				<?php
				$attributes['id'] = "reset_password_form";
				if ( $this->uri->segment(2) != 'reset_password' ) {
					$attributes['class'] = "hide";
				}
				echo form_open("admin/reset_password", $attributes);
				?>
					<?php if ( isset($resetpw1_status) ) {echo $resetpw1_status;} ?>
					<p><?php echo lang("admin_please_enter_email_to_reset_password"); ?></p>
					<?php echo lang('admin_email'); ?>:
					<input type="text" name="email" value="<?php if ( isset($email) ) {echo $email;} ?>" />
					<input type="submit" name="btnact" value="<?php echo lang('admin_go_to_nextstep'); ?>" />
				<?php echo form_close("\n"); ?>
			</div><!--.reset_password-->*/ ?>

		</div><!--.page_login-->

<?php require(dirname(__FILE__).'/foothtml.php'); ?>