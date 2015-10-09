		<div class="page_container">
			<div class="header">
				<div class="website"><a href="<?php echo site_url("site-admin"); ?>"><span class="logo"><?php echo $this->config_model->load("site_name"); ?></span></a></div>
				<div class="menu"><?php echo sprintf(lang("account_hello"), account_show_info()); ?> | <?php echo anchor("site-admin/logout", lang("account_logout")); ?></div>
				<div class="clear"></div>
				<div class="nav">
					<ul class="sf-menu">
						<li><?php echo anchor("", lang("admin_site")); ?>
							<ul>
								<li><?php echo anchor("site-admin", lang("admin_dashboard")); ?></li>
								<li><?php echo anchor("site-admin/config", lang("admin_global_config")); ?></li>
							</ul>
						</li>
						<li><?php echo anchor("site-admin/account", lang("account_account")); ?>
							<ul>
								<li><?php echo anchor("site-admin/account/add", lang("account_add")); ?></li>
								<li><?php echo anchor("site-admin/account/edit", lang("account_edit_yours")); ?></li>
								<li><a><?php echo lang("account_level_n_permissions"); ?></a>
									<ul>
										<li><?php echo anchor("site-admin/account-level", lang("account_level")); ?></li>
										<li><?php echo anchor("site-admin/account-permission", lang("account_permissions")); ?></li>
									</ul>
								</li>
							</ul>
						</li>
						<li><?php echo anchor("site-admin/genres", lang("genre_genre")); ?>
							<ul>
								<li><?php echo anchor("site-admin/genres", lang("genre_add")); ?></li>
							</ul>
						</li>
						<li><?php echo anchor($this->uri->segment(1)."/manga", lang("manga_manga")); ?>
							<ul>
								<li><?php echo anchor($this->uri->segment(1)."/manga/add", lang("manga_add")); ?></li>
								<li><?php echo anchor($this->uri->segment(1)."/chapter", lang("chapter_chapter")); ?>
									<ul>
										<li><?php echo anchor($this->uri->segment(1)."/chapter/add", lang("chapter_add")); ?></li>
									</ul>
								</li>
							</ul>
						</li>
						<li><?php echo anchor("site-admin", lang("admin_component"), array("onclick" => "return false;")); ?>
							<?php echo $this->modules_model->load_admin_nav(); ?>
						</li>
					</ul>
					<div class="clear"></div>
				</div><!--.nav-->
			</div><!--.header-->
			<script type="text/javascript">
			$(document).ready(function() {
				$("ul.sf-menu").supersubs({
					minWidth:    12,   // minimum width of sub-menus in em units
					maxWidth:    27,   // maximum width of sub-menus in em units
					extraWidth:  1     // extra width can ensure lines don't sometimes turn over
				}).superfish({
					delay:         300
				});
			});// jquery document.ready
			</script>



