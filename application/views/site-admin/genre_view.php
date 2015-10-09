<div class="genre_page">
	<h1><?php echo lang("genre_genre"); ?></h1>


	<div class="genre_ae">
		<h2><?php echo ( $this->input->get("act") == "edit" ? lang("genre_edit") : lang("genre_add")); ?></h2>
		<?php echo form_open_multipart($this->uri->uri_string."?act=".($this->input->get("act", true) == null ? "add" : $this->input->get("act", true)."&id=".$this->input->get("id", true))); ?>
			<?php echo (isset($form_ae_status) ? $form_ae_status : ""); ?>
			<table class="form_item" width="100%">
				<tr>
					<th><?php echo lang("genre_name"); ?> <span class="txt_require">*</span></th>
					<td><input type="text" name="genre_name" value="<?php if ( isset($genre_name) ) {echo $genre_name;} ?>" maxlength="255" /></td>
				</tr>
				<tr>
					<th><?php echo lang("genre_uri"); ?> <span class="txt_require">*</span></th>
					<td><input type="text" name="genre_uri" value="<?php if ( isset($genre_uri) ) {echo $genre_uri;} ?>" maxlength="255" /> 
						<span class="txt_comment">(<?php echo lang("genre_uri_comment"); ?>)</span>
					</td>
				</tr>
				<tr>
					<th><?php echo lang("genre_description"); ?></th>
					<td><input type="text" name="genre_description" value="<?php if ( isset($genre_description) ) {echo $genre_description;} ?>" maxlength="500" /></td>
				</tr>
				<tr>
					<th><label for="genre_enable"><?php echo lang("genre_enable"); ?></label></th>
					<td><input type="checkbox" name="genre_enable" value="yes"<?php if ( isset($genre_enable) && $genre_enable == "yes" ) {echo " checked=\"checked\"";} ?> id="genre_enable" /></td>
				</tr>
				<tr>
					<th></th>
					<td><input type="submit" name="btn" value="<?php echo lang("admin_submit"); ?>" /></td>
				</tr>
			</table>
		<?php echo form_close("\n"); ?>
	</div><!--.genre_ae-->

	<div class="genre_list">
		<?php echo sprintf(lang("admin_total"), (isset($list_item['total_item']) ? $list_item['total_item'] : "0")); ?>
		<input type="button" name="btn" value="<?php echo lang("genre_add"); ?>" onclick="window.location='<?php echo site_url($this->uri->segment(1)."/".$this->uri->segment(2)); ?>'" />
		
		<?php echo form_open($this->uri->segment(1)."/".$this->uri->segment(2)."/process_bulk"); ?>
			<?php echo (isset($form_status) ? $form_status : ""); ?>
			<table class="list_item" width="100%">
				<thead>
					<tr>
						<th><input type="checkbox" name="id_all" value="" onclick="checkAll(this.form,'id[]',this.checked)" /></th>
						<th>ID</th>
						<th><?php echo lang("genre_name"); ?></th>
						<th><?php echo lang("genre_uri"); ?></th>
						<th><?php echo lang("genre_add_date"); ?></th>
						<th><?php echo lang("genre_enable"); ?></th>
						<th></th>
					</tr>
				</thead>
				<tbody>
					<?php if ( isset($list_item) && isset($list_item['list']) && is_array($list_item['list']) && !empty($list_item) ): ?>
					<?php foreach ( $list_item['list'] as $key ): ?>
					<tr>
						<td><?php echo form_checkbox("id[]", $key->genre_id); ?></td>
						<td><?php echo $key->genre_id; ?></td>
						<td title="<?php echo $key->genre_description; ?>"><?php echo $key->genre_name; ?></td>
						<td><?php echo urldecode($key->genre_uri); ?></td>
						<td><?php echo $key->genre_add; ?></td>
						<td><img src="<?php echo base_url(); ?>client/images/<?php echo ($key->genre_enable === '1' ? "yes" : "no"); ?>.gif" alt="<?php echo ($key->genre_enable === '1' ? "yes" : "no"); ?>" /></td>
						<td><?php echo anchor($this->uri->segment(1)."/genres?act=edit&id=".$key->genre_id, lang("admin_edit")); ?></td>
					</tr>
					<?php endforeach; ?>
					<?php else: ?>
					<tr>
						<td colspan="7"><?php echo lang("admin_nodata"); ?></td>
					</tr>
					<?php endif; ?>
				</tbody>
			</table>
			<select name="cmd" size="1">
				<option value=""></option>
				<option value="delete"><?php echo lang("admin_delete"); ?></option>
			</select>
			<input type="submit" name="btn" value="<?php echo lang("admin_submit"); ?>" />
		<?php echo form_close("\n"); ?>
	</div><!--.genre_list-->
	<div class="clear"></div>
</div><!--.genre_page-->