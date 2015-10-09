
<div class="in_settings">
	<h1><?php echo lang("admin_configuration"); ?></h1>

	<?php echo form_open(); ?>
	<?php if ( isset($form_status) ) {echo $form_status;} ?>
	<table class="item_form" width="100%">
		<tr>
			<th><?php echo lang("admin_site_name"); ?></th>
			<td><input type="text" name="site_name" value="<?php if ( isset($site_name) ) {echo $site_name;} ?>" /></td>
		</tr>
		<tr>
			<th><?php echo lang("admin_title_separater"); ?></th>
			<td><input type="text" name="page_title_separater" value="<?php if ( isset($page_title_separater) ) {echo $page_title_separater;} ?>" /></td>
		</tr>
		<tr>
			<th><?php echo lang("admin_items_perpage"); ?></th>
			<td><input type="text" name="admin_items_per_page" value="<?php if ( isset($admin_items_per_page) ) {echo $admin_items_per_page;} ?>" /></td>
		</tr>
		<tr>
			<th><?php echo lang("admin_manga_items_perpage"); ?></th>
			<td><input type="text" name="front_manga_per_page" value="<?php if ( isset($front_manga_per_page) ) {echo $front_manga_per_page;} ?>" /></td>
		</tr>
		<tr>
			<th><?php echo lang("admin_manga_directory"); ?></th>
			<td><input type="text" name="manga_dir" value="<?php if ( isset($manga_dir) ) {echo $manga_dir;} ?>" /> <span class="txt_comment">(<?php echo lang("admin_manga_dir_comment"); ?>)</span></td>
		</tr>
		<tr>
			<th><?php echo lang("admin_duplicate_login"); ?></th>
			<td><input type="text" name="duplicate_login" value="<?php if ( isset($duplicate_login) ) {echo $duplicate_login;} ?>" /> <span class="txt_comment">(<?php echo lang("admin_duplicate_login_comment"); ?>)</span></td>
		</tr>
		<tr>
			<th><?php echo lang("admin_cache"); ?></th>
			<td><input type="text" name="cache" value="<?php if ( isset($cache) ) {echo $cache;} ?>" /> <span class="txt_comment">(<?php echo lang("admin_cache_comment"); ?>)</span></td>
		</tr>
		<tr>
			<th></th>
			<td><input type="submit" name="btnact" value="<?php echo lang('admin_submit'); ?>" /></td>
		</tr>
	</table>
	<?php echo form_close("\n"); ?>
</div><!--.in_settings-->
