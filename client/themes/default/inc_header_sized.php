		<div class="header">
			<div class="header_inner page_width">
				<div class="navigation">
					<ul>
						<li class="logo nobg"><a href="<?php echo site_url(); ?>"><img src="<?php echo base_url(); ?>client/images/logo.png" alt="logo" /></a></li>
						<li<?php if ( site_url() == current_url() ) {?> class="current"<?php } ?>><?php echo anchor(site_url(), lang("front_home")); ?></li>
						<li<?php if ( site_url("directory") == current_url() ) {?> class="current"<?php } ?>><?php echo anchor(site_url("directory"), lang("front_manga_directory")); ?></li>
						<li class="search nobg"></li>
					</ul>
				</div><!--.navigation-->
				<div class="clear"></div>
			</div><!--.page_width-->
		</div><!--.header-->
