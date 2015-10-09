<h1><?php echo lang("admin_global_config"); ?></h1>

<?php echo form_open(current_url(), array("class" => "config_form")); ?>
	<?php echo (isset($form_status) ? $form_status : ""); ?>

	<div id="tabs">
		<ul>
			<li><a href="#tabs-1"><?php echo lang("admin_website_config"); ?></a></li>
			<li><a href="#tabs-2"><?php echo lang("admin_account_config"); ?></a></li>
			<li><a href="#tabs-3"><?php echo lang("admin_manga_config"); ?></a></li>
		</ul>
		
		<div id="tabs-1">
			<h2><?php echo lang("admin_website_config"); ?></h2>
			<dl class="form_item">
				<dt><?php echo lang("admin_config_sitename"); ?>:</dt>
				<dd><?php echo form_input("site_name", (isset($site_name) ? $site_name : "")); ?></dd>
				
				<dt><?php echo lang("admin_config_title_separator"); ?>:</dt>
				<dd><?php echo form_input("page_title_separator", (isset($page_title_separator) ? $page_title_separator : "")); ?></dd>
				
				<dt><?php echo lang("admin_config_cache_enable"); ?>:</dt>
				<dd>
					<select name="cache_enable">
						<option value="no"<?php echo (isset($cache_enable) && $cache_enable == "no" ? " selected=\"selected\"" : ""); ?>><?php echo lang("admin_no"); ?></option>
						<option value="yes"<?php echo (isset($cache_enable) && $cache_enable == "yes" ? " selected=\"selected\"" : ""); ?>><?php echo lang("admin_yes"); ?></option>
					</select>
					<?php echo form_input("cache_minute", (isset($cache_minute) ? $cache_minute : ""), "class=\"cache_minute\""); ?> <?php echo lang("admin_minute"); ?>
				</dd>
			</dl>
			<div class="clear"></div>
		</div>
		
		<div id="tabs-2">
			<h2><?php echo lang("admin_account_config"); ?></h2>
			<dl class="form_item">
				<dt><?php echo lang("admin_config_duplicate_login"); ?>:</dt>
				<dd><input type="checkbox" name="duplicate_login" value="on"<?php if ( isset($duplicate_login) && $duplicate_login == 'on' ): ?> checked="checked"<?php endif; ?> /></dd>
				
				<dt><?php echo lang("admin_config_allow_avatar"); ?>:</dt>
				<dd><input type="checkbox" name="allow_avatar" value="1"<?php if ( isset($allow_avatar) && $allow_avatar == '1' ): ?> checked="checked"<?php endif; ?> /></dd>
				
				<dt><?php echo lang("admin_config_avatar_size"); ?>:</dt>
				<dd><?php echo form_input("avatar_size", (isset($avatar_size) ? $avatar_size : "")); ?></dd>
				<dd class="comment"><?php echo lang("admin_size_in_kb"); ?></dd>
				
				<dt><?php echo lang("admin_config_avatar_type"); ?>:</dt>
				<dd><?php echo form_input("avatar_allowed_types", (isset($avatar_allowed_types) ? $avatar_allowed_types : "")); ?></dd>
				<dd class="comment">gif|jpg|png</dd>
			</dl>
			<div class="clear"></div>
		</div>
		
		<div id="tabs-3">
			<h2><?php echo lang("admin_manga_config"); ?></h2>
			<dl class="form_item">
				<dt><?php echo lang("admin_item_perpage"); ?>:</dt>
				<dd><?php echo form_input("admin_items_per_page", (isset($admin_items_per_page) ? $admin_items_per_page : "")); ?></dd>
				
				<dt><?php echo lang("admin_web_item_perpage"); ?>:</dt>
				<dd><?php echo form_input("web_items_per_page", (isset($web_items_per_page) ? $web_items_per_page : "")); ?></dd>
				
				<dt><?php echo lang("admin_manga_directory"); ?>:</dt>
				<dd><?php echo form_input("manga_dir", (isset($manga_dir) ? $manga_dir : "")); ?></dd>
				<dd class="comment"><?php echo lang("admin_manga_dir_comment"); ?></dd>
			</dl>
			<div class="clear"></div>
		</div>
		
	</div>

	<?php echo form_submit("btn", lang("admin_save")); ?>

<?php echo form_close("\n"); ?>

<script type="text/javascript">
$(document).ready(function() {
	$("#tabs").tabs();
});// jquery document ready
</script>