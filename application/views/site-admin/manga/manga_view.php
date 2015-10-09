<h1><?php echo lang("manga_manga"); ?></h1>

<div class="list_item_cmdleft">
	<?php echo form_button("btn", lang("manga_add"), "onclick=\"window.location='" . site_url($this->uri->segment(1)."/".$this->uri->segment(2)."/add") . "'\""); ?>
	| <?php echo sprintf(lang("admin_total"), (isset($list_item['total_item']) ? $list_item['total_item'] : "0")); ?>
</div><!--.list_item_cmdleft-->
<div class="list_item_cmdright">
	<form method="get">
		<input type="text" name="q" value="<?php echo $q; ?>" />
		<input type="submit" value="<?php echo lang("admin_search"); ?>" />
	</form>
</div><!--.list_item_cmdright-->
<div class="clear"></div>

<?php echo form_open(current_url()."/process_bulk"); ?>
	<?php echo (isset($form_status) ? $form_status : ""); ?>

	<table class="list_item" width="100%">
		<thead>
			<tr>
				<th><input type="checkbox" name="id_all" value="" onclick="checkAll(this.form,'id[]',this.checked)" /></th>
				<th><?php echo anchor($this->uri->uri_string()."?orders=story_id&sort=$sort&q=$q", "ID"); ?></th>
				<th><?php echo anchor($this->uri->uri_string()."?orders=story_name&sort=$sort&q=$q", lang("manga_name")); ?></th>
				<th><?php echo lang("manga_cover_image"); ?></th>
				<th><?php echo anchor($this->uri->uri_string()."?orders=story_author&sort=$sort&q=$q", lang("manga_author")); ?></th>
				<th><?php echo anchor($this->uri->uri_string()."?orders=story_artist&sort=$sort&q=$q", lang("manga_artist")); ?></th>
				<th><?php echo anchor($this->uri->uri_string()."?orders=story_uri&sort=$sort&q=$q", lang("manga_uri")); ?></th>
				<th><?php echo anchor($this->uri->uri_string()."?orders=story_views&sort=$sort&q=$q", lang("manga_views")); ?></th>
				<th><?php echo anchor($this->uri->uri_string()."?orders=story_update&sort=$sort&q=$q", lang("manga_update")); ?></th>
				<th><?php echo anchor($this->uri->uri_string()."?orders=story_enable&sort=$sort&q=$q", lang("manga_enable")); ?></th>
				<th></th>
			</tr>
		</thead>
		<tbody>
			<?php if ( isset($list_item) && isset($list_item['list']) && is_array($list_item['list']) && !empty($list_item) ): ?>
			<?php foreach ( $list_item['list'] as $key ): ?>
			<tr>
				<td><?php echo form_checkbox("id[]", $key->story_id); ?></td>
				<td><?php echo $key->story_id; ?></td>
				<td>
					<?php echo anchor($this->uri->uri_string()."/edit?id=".$key->story_id, $key->story_name); ?>
					&nbsp; [<?php $this->db->where("story_id", $key->story_id); echo anchor($this->uri->segment(1)."/chapter?manga_id=".$key->story_id, $this->db->count_all_results($this->db->dbprefix("chapters"))." ". lang("manga_chapters")); ?>]
				</td>
				<td><a href="<?php echo base_url().$key->story_cover; ?>"><img src="<?php echo base_url().$this->manga_model->set_image_size($key->story_cover, "tiny"); ?>" alt="cover" width="100" /></a></td>
				<td><?php echo $key->story_author; ?></td>
				<td><?php echo $key->story_artist; ?></td>
				<td><?php echo urldecode($key->story_uri); ?></td>
				<td><?php echo urldecode($key->story_views); ?></td>
				<td><?php echo $key->story_update; ?></td>
				<td><img src="<?php echo base_url()."client/images/".($key->story_enable === '1' ? "yes" : "no").".gif"; ?>" alt="<?php echo ($key->story_enable === '1' ? "yes" : "no"); ?>" /></td>
				<td>
					[<?php echo anchor($this->uri->uri_string()."/edit?id=".$key->story_id, lang("admin_edit")); ?>]
					[<?php echo anchor($this->uri->segment(1)."/chapter?manga_id=".$key->story_id, lang("manga_chapters")); ?>]
				</td>
			</tr>
			<?php endforeach; ?>
			<?php else: ?>
			<tr>
				<td colspan="10"><?php echo lang("admin_nodata"); ?></td>
			</tr>
			<?php endif; ?>
		</tbody>
	</table>
	
	<div class="list_item_cmdleft">
		<select name="cmd">
			<option></option>
			<option value="del"><?php echo lang("admin_delete"); ?></option>
		</select>
		<?php echo form_submit("btn", lang("admin_submit")); ?>
	</div><!--.list_item_cmdleft-->
	<div class="list_item_cmdright">
		<?php echo (isset($pagination) ? $pagination : ""); ?>
	</div><!--.list_item_cmdright-->
	<div class="clear"></div>
<?php echo form_close("\n"); ?>