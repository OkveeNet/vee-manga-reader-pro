
<div class="in_category">
	<h1><?php echo lang("admin_genre"); ?></h1>

	<div class="left_column">
		<h2><?php if ( $this->input->get("act") == "edit" ) {echo lang("admin_edit_genre");} else {echo lang("admin_add_genre");} ?></h2>
		<?php echo form_open($this->uri->segment(1)."/".$this->uri->segment(2)."?act=".($this->input->get("act", true) == null ? "add" : $this->input->get("act", true)."&id=".$this->input->get("id", true))); ?>
		<?php if ( isset($form_status) ) {echo $form_status;} ?>
		<table class="item_form" width="100%">
			<tr>
				<th><?php echo lang("admin_name"); ?> <span class="txt_required">*</span></th>
				<td><input type="text" name="genre_name" value="<?php if ( isset($genre_name) ) {echo $genre_name;} ?>" maxlength="255" /></td>
			</tr>
			<tr>
				<th><?php echo lang("admin_uri"); ?> <span class="txt_required">*</span></th>
				<td><input type="text" name="genre_uri" value="<?php if ( isset($genre_uri) ) {echo $genre_uri;} ?>" maxlength="255" /> 
					<span class="txt_comment">(<?php echo lang("admin_uri_comment"); ?>)</span>
				</td>
			</tr>
			<tr>
				<th><?php echo lang("admin_description"); ?></th>
				<td><input type="text" name="genre_description" value="<?php if ( isset($genre_description) ) {echo $genre_description;} ?>" maxlength="500" /></td>
			</tr>
			<tr>
				<th><label for="genre_enable"><?php echo lang("admin_enable"); ?></label></th>
				<td><input type="checkbox" name="genre_enable" value="yes"<?php if ( isset($genre_enable) && $genre_enable == "yes" ) {echo " checked=\"checked\"";} ?> id="genre_enable" /></td>
			</tr>
			<tr>
				<th></th>
				<td><input type="submit" name="btn" value="<?php echo lang("admin_submit"); ?>" /></td>
			</tr>
		</table>
		<?php echo form_close("\n"); ?>
	</div><!--.left_column-->
	
	<div class="right_column">
		<span><?php echo $genre_total; ?> <?php echo lang("admin_items"); ?></span>
		<input type="button" name="btn" value="<?php echo lang("admin_add"); ?>" onclick="window.location='<?php echo base_url().$this->uri->segment(1)."/".$this->uri->segment(2); ?>'" />
		<?php echo form_open(base_url().$this->uri->segment(1)."/".$this->uri->segment(2)."/process_bulk")."\n"; ?>
			<table class="item_list" width="100%">
				<tr>
					<th><input type="checkbox" name="id_all" value="" onclick="checkAll(this.form,'id[]',this.checked)" /></th>
					<th><?php echo lang("admin_id"); ?></th>
					<th><?php echo lang("admin_name"); ?></th>
					<th><?php echo lang("admin_uri"); ?></th>
					<th><?php echo lang("admin_add_date"); ?></th>
					<th><?php echo lang("admin_enable"); ?></th>
					<th></th>
				</tr>
				<?php
				if ( is_array($genre_list) ) {
					$i = 1;
					foreach ( $genre_list as $key => $item ) {
				?>
				<tr>
					<td><input type="checkbox" name="id[]" value="<?php echo $key; ?>" /></td>
					<td><?php echo $key; ?></td>
					<td><?php echo $item['genre_name']; ?>
						<?php if ( $item['genre_description'] != null ) {echo "<br />\n<span class=\"detail\">".truncate_string($item['genre_description'], 100)."</span>";} ?>
					</td>
					<td><?php echo $item['genre_uri']; ?></td>
					<td><?php echo date("Y-m-d", strtotime($item['genre_add'])); ?></td>
					<td><img src="<?php echo base_url(); ?>client/images/<?php echo ($item['genre_enable'] === '1' ? "yes" : "no"); ?>.gif" alt="<?php echo ($item['genre_enable'] === '1' ? "yes" : "no"); ?>" /></td>
					<td>
						<?php echo anchor($this->uri->segment(1)."/genres?act=edit&id=".$key, lang("admin_edit")); ?>
					</td>
				</tr>
				<?php
						$i++;
					}// endforeach
					if ( $i >= 5 ) {
				?>
				<tr>
					<th><input type="checkbox" name="id_all" value="" onclick="checkAll(this.form,'id[]',this.checked)" /></th>
					<th><?php echo lang("admin_id"); ?></th>
					<th><?php echo lang("admin_name"); ?></th>
					<th><?php echo lang("admin_uri"); ?></th>
					<th><?php echo lang("admin_add_date"); ?></th>
					<th><?php echo lang("admin_enable"); ?></th>
					<th></th>
				</tr>
				<?php
					}// endif $i >= 5
				} else {
				?>
				<tr>
					<td colspan="7"><?php echo lang("admin_nodata"); ?></td>
				</tr>
				<?php
				}// endif is_array
				?>
			</table>
			<select name="cmd" size="1">
				<option value=""></option>
				<option value="delete"><?php echo lang("admin_delete"); ?></option>
			</select>
			<input type="submit" name="btn" value="<?php echo lang("admin_submit"); ?>" />
		<?php echo form_close("\n"); ?>
	</div><!--.right_column-->
	<div class="clear"></div>
	
</div>
