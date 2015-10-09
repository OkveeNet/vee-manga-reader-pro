<?php require(dirname(__FILE__).'/headhtml.php'); ?>

		<script type="text/javascript">
		$(document).ready(function() {
			$("ul.sf-menu").supersubs({
				minWidth: 15,
				maxWidth: 60
			}).superfish({
				delay: 100,
				speed: 'fast'
			});
		});
		</script>


		<div class="page_admin">
			<div class="header">
				<div class="logo"><a href="<?php echo base_url().$this->uri->segment(1); ?>"><img src="<?php echo base_url(); ?>client/images/short-logo.png" alt="logo" height="30" /></a> <?php echo anchor(base_url(), lang("admin_visit_site")); ?></div>
				<div class="bar">
					<div><?php echo lang('admin_hello'); ?>, <?php echo $this->account_model->show_account_info('account_username'); ?> | <a href="<?php echo base_url().$this->uri->segment(1); ?>/logout"><?php echo lang('admin_log_out'); ?></a></div>
				</div>
				<div class="clear"></div>
			</div><!--.header-->
			
			<div class="navigation_bar">
				<ul class="sf-menu">
					<li><?php echo anchor($this->uri->segment(1), lang("admin_home")); ?>
						<ul>
							<li><?php echo anchor($this->uri->segment(1)."/config", lang("admin_configuration")); ?></li>
						</ul>
					</li>
					<li><?php echo anchor($this->uri->segment(1)."/genres", lang("admin_genre")); ?>
						<ul>
							<li><?php echo anchor($this->uri->segment(1)."/genres", lang("admin_add_genre")); ?></li>
						</ul>
					</li>
					<li><?php echo anchor($this->uri->segment(1)."/manga", lang("admin_manga")); ?>
						<ul>
							<li><?php echo anchor($this->uri->segment(1)."/manga/add", lang("admin_add_manga")); ?></li>
							<li><?php echo anchor($this->uri->segment(1)."/manga_chapters", lang("admin_chapters")); ?>
								<ul>
									<li><?php echo anchor($this->uri->segment(1)."/manga_chapters/add", lang("admin_add_chapter")); ?></li>
								</ul>
							</li>
						</ul>
					</li>
					<li><?php echo anchor($this->uri->segment(1)."/users", lang("admin_users")); ?>
						<ul>
							<li><?php echo anchor($this->uri->segment(1)."/users/add", lang("admin_add_user")); ?></li>
							<li><?php echo anchor($this->uri->segment(1)."/users/edit", lang("admin_edit_my_profile")); ?></li>
						</ul>
					</li>
				</ul>
				<div class="clear"></div>
			</div><!--.navigation_bar-->
			<div class="clear"></div>
			
			<div class="body_wrapper_full">
				<div class="column_main">
				
				<?php if ( isset($page_content) ) {echo $page_content;} ?>
				
				</div><!--.column_main-->
				<div class="clear"></div>
			</div><!--.body_wrapper_full-->

		</div><!--.page_admin-->


		<div class="footer">
			current system time: <?php echo date('Y-m-d H:i:s', time()); ?>
		</div><!--.footer-->

<?php require(dirname(__FILE__).'/foothtml.php'); ?>