
<div class="in_manga">
	<h1><?php echo lang("admin_manga"); ?></h1>

	<span><?php if ( isset($list_manga['total_manga']) ) {echo $list_manga['total_manga'];} ?> <?php echo lang("admin_items"); ?></span>
	<input type="button" name="btn" value="<?php echo lang("admin_add"); ?>" onclick="window.location='<?php echo base_url().$this->uri->segment(1)."/".$this->uri->segment(2); ?>/add'" />

	<?php echo form_open($this->uri->segment(1)."/".$this->uri->segment(2)."/process_bulk")."\n"; ?>
		<?php if ( isset($form_status) ) {echo $form_status;} ?>
		<table class="item_list" width="100%">
			<tr>
				<th><input type="checkbox" name="id_all" value="" onclick="checkAll(this.form,'id[]',this.checked)" /></th>
				<th><?php echo lang("admin_manga_name"); ?></th>
				<th><?php echo lang("admin_cover_image"); ?></th>
				<th><?php echo lang("admin_author"); ?></th>
				<th><?php echo lang("admin_artist"); ?></th>
				<th><?php echo lang("admin_uri"); ?></th>
				<th><?php echo lang("admin_last_update"); ?></th>
				<th><?php echo lang("admin_enable"); ?></th>
				<th></th>
			</tr>
			<?php
			if ( isset($list_manga) && is_array($list_manga) ) {
				$i = 1;
				foreach ( $list_manga as $key => $item ) {
					if ( is_numeric($key) ) {
			?>
			<tr>
				<td><input type="checkbox" name="id[]" value="<?php echo $key; ?>" /></td>
				<td>
					<?php echo  anchor($this->uri->segment(1)."/".$this->uri->segment(2)."/edit?id=".$key, $item['story_name']); ?>
					&nbsp; [<?php $this->db->where("story_id", $key); echo anchor($this->uri->segment(1)."/manga_chapters?manga_id=".$key, $this->db->count_all_results($this->db->dbprefix("chapters"))." ". lang("admin_chapters")); ?>]
				</td>
				<td><a href="<?php echo site_url($this->uri->segment(1)."/".$this->uri->segment(2)."/edit?id=".$key); ?>"><img src="<?php echo base_url().$item['cover_small']; ?>" alt="cover" width="100" /></a></td>
				<td><?php echo $item['story_author']; ?></td>
				<td><?php echo $item['story_artist']; ?></td>
				<td><?php echo $item['story_uri']; ?></td>
				<td><?php echo $item['story_update']; ?></td>
				<td><img src="<?php echo base_url()."client/images/".($item['story_enable'] === '1' ? "yes" : "no").".gif"; ?>" alt="<?php echo ($item['story_enable'] === '1' ? "yes" : "no"); ?>" /></td>
				<td>
					[<?php echo anchor($this->uri->segment(1)."/".$this->uri->segment(2)."/edit?id=".$key, lang("admin_edit")); ?>]
					[<?php echo anchor($this->uri->segment(1)."/manga_chapters?manga_id=".$key, lang("admin_chapters")); ?>]
				</td>
			</tr>
			<?php
					$i++;
					}
				}// endforeach
				if ( $i >= 10 ) {
			?>
			<tr>
				<th><input type="checkbox" name="id_all" value="" onclick="checkAll(this.form,'id[]',this.checked)" /></th>
				<th><?php echo lang("admin_manga_name"); ?></th>
				<th><?php echo lang("admin_cover_image"); ?></th>
				<th><?php echo lang("admin_author"); ?></th>
				<th><?php echo lang("admin_artist"); ?></th>
				<th><?php echo lang("admin_uri"); ?></th>
				<th><?php echo lang("admin_last_update"); ?></th>
				<th><?php echo lang("admin_enable"); ?></th>
				<th></th>
			</tr>
			<?php
				}// endif $i >= 10
			} else {
			?>
			<tr>
				<td colspan="9"><?php echo lang("admin_nodata"); ?></td>
			</tr>
			<?php
			}// endif;
			?>
		</table>
		<select name="cmd" size="1">
			<option value=""></option>
			<option value="delete"><?php echo lang("admin_delete"); ?></option>
		</select>
		<input type="submit" name="btn" value="<?php echo lang("admin_submit"); ?>" />
	<?php echo form_close("\n"); ?>
	<div><?php echo $pagination; ?></div><!--pagination-->
</div><!--.in_manga-->
