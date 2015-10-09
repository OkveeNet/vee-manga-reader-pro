<?php include("inc_header_html.php"); ?>
		<div class="top-header">
			<div class=" container_16 top-inner-header">
				<div class="grid_2"><a href="<?php echo base_url(); ?>"><img src="<?php echo base_url(); ?>client/images/logo.png" alt="logo" class="logo" /></a></div>
				<div class="grid_14 nav">
					<nav>
						<ul>
							<li class="first<?php if ( current_url() == site_url() || current_url() == base_url() ) {echo " current";} ?>"><?php echo anchor(base_url(), lang("front_home")); ?></li>
							<li<?php if ( current_url() == site_url("directory") ) {echo " class=\"current\"";} ?>><?php echo anchor("directory", lang("front_directory")); ?> 
								<?php
								$this->load->model("genre_model");
								$genres = $this->genre_model->list_item();
								if ( isset($genres['list']) && is_array($genres['list']) ) {
									echo "<ul class=\"dir-genre\">\n";
									foreach ( $genres['list'] as $key ) {
										echo "<li".( current_url() == site_url("directory/".$key->genre_uri) ? " class=\"current\"" : "").">" . anchor("directory/".$key->genre_uri, $key->genre_name) . "</li>\n";
									}
									echo "</ul>\n";
								}
								?> 
							</li>
							<?php if ( $this->account_model->is_member_login() == true ): ?>
							<li><?php echo anchor("account/profile", lang("account_edit_profile")); ?></li>
							<li><?php echo anchor("account/logout", lang("account_logout")); ?></li>
							<?php
							// get account level
							$level_id = $this->account_model->show_account_level_info();
							$level_priority = $this->account_model->show_account_level_group_info($level_id, "level_priority");
							if ( $level_priority != null && $level_priority <= 3 ) {
								// not just member
								echo "<li class=\"admin\">" . anchor("site-admin", "Site admin") . "</li>\n";
							}
							?>
							<?php else: ?>
							<li><?php echo anchor("account/register", lang("account_register")); ?></li>
							<li><?php echo anchor("account/login", lang("account_login")); ?></li>
							<?php endif; ?>
						</ul>
						<div class="clear"></div>
					</nav>
				</div>
				<div class="clear"></div>
			</div><!--.top-inner-header-->
		</div><!--.top-header-->
		<div class="container_16 page_container">
			
			
