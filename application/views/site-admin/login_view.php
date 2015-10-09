<?php include(dirname(__FILE__)."/inc_header_html.php"); ?>
		<div class="page_container">
			<div class="page_login">
				<h1 class="site_name"><?php echo $this->config_model->load("site_name"); ?></h1>
				
				
				<?php echo form_open($this->uri->segment(1) . "/login", array("id" => "form_login")); ?>
					<div class="login_result"><?php echo (isset($form_status) ? $form_status : ""); ?></div>
					<dl>
						<dt><?php echo lang("account_username"); ?>:</dt>
						<dd><?php echo form_input("username", (isset($username) ? $username : "")); ?></dd>
						<dt><?php echo lang("account_password"); ?>:</dt>
						<dd><?php echo form_password("password"); ?></dd>
						<?php //if ( isset($show_captcha) && $show_captcha == true ): ?>
						<dt class="captcha_fieldset">&nbsp;</dt>
						<dd class="captcha_fieldset"><img src="<?php echo base_url(); ?>client/images/securimage_show.php" alt="securimage" id="captcha" />
							<a href="#" onclick="document.getElementById('captcha').src = '<?php echo base_url(); ?>client/images/securimage_show.php?' + Math.random(); return false"><img src="<?php echo base_url(); ?>client/images/reload.gif" alt="" /></a>
						</dd>
						<dt class="captcha_fieldset"><?php echo lang("account_captcha"); ?>:</dt>
						<dd class="captcha_fieldset"><?php echo form_input("captcha", (isset($captcha) ? $captcha : "")); ?></dd>
						<?php //endif; ?>
						<dt>&nbsp;</dt>
						<dd><?php echo form_submit("btn", lang("account_login")); ?></dd>
					</dl>
					<div class="clear"></div>
				<?php echo form_close("\n"); ?>
				
				
				<div class="requirement_check">
					<span><img src="<?php echo base_url(); ?>client/images/<?php echo(isset($browser_check) ? $browser_check : ""); ?>.gif" alt="<?php echo(isset($browser_check) ? $browser_check : ""); ?>" /><?php echo lang("account_webbrowser"); ?></span>
					<span><img src="<?php echo base_url(); ?>client/images/no.gif" alt="<?php echo lang("account_javascript"); ?>" id="javascript_check" /><?php echo lang("account_javascript"); ?></span>
					<span>
						<object width="16" height="16" data="<?php echo base_url(); ?>client/images/yes.swf" type="application/x-shockwave-flash" style="margin-left:2px; position:absolute; z-index:2;">
						<param name="data" value="<?php echo base_url(); ?>client/images/yes.swf" />
						<param name="src" value="<?php echo base_url(); ?>client/images/yes.swf" />
						</object>
						<img src="<?php echo base_url(); ?>client/images/no.gif" alt="<?php echo lang("account_flash"); ?>" style="z-index: 1;" /><?php echo lang("account_flash"); ?>
					</span>
				</div>
				
				
				<span class="forgetpw_toggle"><?php echo lang("account_forget_userpass"); ?></span>
				<div class="forgetpw_form<?php if ( isset($show_resetpw_form) && $show_resetpw_form == true ): ?> show<?php endif; ?>">
					<?php echo form_open($this->uri->segment(1) . "/login/forgetpw", array("id" => "form_forgetpw")); ?>
					<div class="forgetpw_result"><?php echo (isset($formforget_status) ? $formforget_status : ""); ?></div>
					<p><?php echo lang("account_enter_email_to_forgetpw1"); ?></p>
					<dl>
						<dt><?php echo lang("account_email"); ?>:</dt>
						<dd><?php echo form_input("email", (isset($email) ? $email : "")); ?></dd>
						<dt class="captcha_fieldset">&nbsp;</dt>
						<dd class="captcha_fieldset"><img src="<?php echo base_url(); ?>client/images/securimage_show.php" alt="securimage" id="captcha2" />
							<a href="#" onclick="document.getElementById('captcha2').src = '<?php echo base_url(); ?>client/images/securimage_show.php?' + Math.random(); return false"><img src="<?php echo base_url(); ?>client/images/reload.gif" alt="" /></a>
						</dd>
						<dt class="captcha_fieldset"><?php echo lang("account_captcha"); ?>:</dt>
						<dd class="captcha_fieldset"><?php echo form_input("captcha2", (isset($captcha2) ? $captcha2 : "")); ?></dd>
						<dt>&nbsp;</dt>
						<dd><?php echo form_submit("btn", lang("account_submit")); ?></dd>
					</dl>
					<div class="clear"></div>
					<?php echo form_close("\n"); ?>
				</div>
				
				
				<script type="text/javascript">
				$(document).ready(function() {
					$("#javascript_check").attr("src", "<?php echo base_url(); ?>client/images/yes.gif");
					$(".forgetpw_toggle").click(function() {
						$(".forgetpw_form").toggle('fade');
					});// toggle forget username/password
					
					// get show captcha value without post method
					$.ajax({
						type:"GET",
						url: $("#form_login").attr("action"),
						dataType: "json",
						success: function(getdata) {
							if ( getdata.show_captcha == true ) {
								$(".captcha_fieldset").show("fade", {}, "fast");
							} else if ( getdata.show_captcha == false ) {
								$(".captcha_fieldset").hide("fade", {}, "fast");
							}
						}
					});// ajax get options without post method
					
					$("#form_login").submit(function() {
						$.ajax({
							type: "POST",
							data: $(this).serialize(),
							url: $(this).attr("action"),
							dataType: "json",
							success: function(getdata) {
								$(".login_result").html(getdata.form_status);
								if ( getdata.show_captcha == true ) {
									$(".captcha_fieldset").show("fade", {}, "fast");
								} else if ( getdata.show_captcha == false ) {
									$(".captcha_fieldset").hide("fade", {}, "fast");
								}
								// log in success?
								if ( getdata.form_status == true ) {
									window.location = '<?php echo site_url($this->uri->segment(1)); ?>';
								}
							},
							error: function(getdata) {
								$(".login_result").html("Request log in failed. Please contact administrator.");
							}
						});
						return false;
					});// log in form ajax
					
					$("#form_forgetpw").submit(function() {
						$.ajax({
							type: "POST",
							data: $(this).serialize(),
							url: $(this).attr("action"),
							dataType: "json",
							success: function(getdata) {
								$(".forgetpw_result").html(getdata.formforget_status);
							},
							error: function(getdata) {
								$(".forgetpw_result").html("Request log in failed. Please contact administrator.");
							}
						});
						return false;
					});// forget user/pass form ajax
				});// jquery document.ready
				</script>
			</div><!--.page_login-->
		</div><!--.page_container-->
<?php include(dirname(__FILE__)."/inc_footer_html.php"); ?>