<h1><?php if ( isset($manga_name) && $manga_name != null ) {echo $manga_name." : ";} ?><?php echo lang("chapter_chapter"); ?></h1>

<div class="list_item_cmdleft">
	<?php echo form_button("btn", lang("chapter_add"), "onclick=\"window.location='" . site_url($this->uri->segment(1)."/".$this->uri->segment(2)."/add?manga_id=".$this->input->get("manga_id", true)."") . "'\""); ?>
	| <?php echo sprintf(lang("admin_total"), (isset($list_item['total_item']) ? $list_item['total_item'] : "0")); ?>
	<?php if ( $manga_name == null ):// select manga before add ?>
		| <?php echo lang("chapter_select_manga"); ?>
		<script type="text/javascript">
			$(document).ready(function() {
				$("#select_manga").change(function () {
					var mangasel = $(this).val();
					window.location='<?php echo current_url()."?manga_id="; ?>'+mangasel;
				});
			});
		</script>
		<?php if ( isset($list_manga) && is_array($list_manga) ): ?>
			<select name="manga" id="select_manga">
				<option value=""></option>
			<?php foreach ( $list_manga['list'] as $key ): ?>
				<option value="<?php echo $key->story_id; ?>"><?php echo $key->story_name; ?></option>
			<?php endforeach; ?>
			</select>
		<?php endif;// if list_manga ?>
	<?php endif;// end check if manga was selected ?>
</div><!--.list_item_cmdleft-->
<div class="clear"></div>

<?php echo form_open(current_url()."/process_bulk"); ?>
	<?php echo (isset($form_status) ? $form_status : ""); ?>

	<table class="list_item" width="100%">
		<thead>
			<tr>
				<th><input type="checkbox" name="id_all" value="" onclick="checkAll(this.form,'id[]',this.checked)" /></th>
				<th><?php echo anchor($this->uri->uri_string()."?orders=chapter_order&sort=$sort&q=$q", lang("chapter_order")); ?></th>
				<th><?php echo anchor($this->uri->uri_string()."?orders=chapter_name&sort=$sort&q=$q", lang("chapter_name")); ?></th>
				<th><?php echo anchor($this->uri->uri_string()."?orders=scanlator&sort=$sort&q=$q", lang("chapter_scanlator")); ?></th>
				<th><?php echo anchor($this->uri->uri_string()."?orders=chapter_uri&sort=$sort&q=$q", lang("chapter_uri")); ?></th>
				<th><?php echo anchor($this->uri->uri_string()."?orders=chapter_add&sort=$sort&q=$q", lang("chapter_add_date")); ?></th>
				<th><?php echo anchor($this->uri->uri_string()."?orders=chapter_enable&sort=$sort&q=$q", lang("chapter_enable")); ?></th>
				<th></th>
			</tr>
		</thead>
		<tbody>
			<?php if ( isset($list_item) && isset($list_item['list']) && is_array($list_item['list']) && !empty($list_item) ): ?>
			<?php foreach ( $list_item['list'] as $key ): ?>
			<tr>
				<td><?php echo form_checkbox("id[]", $key->chapter_id); ?></td>
				<td><?php echo $key->chapter_order; ?></td>
				<td>
					<strong><?php echo anchor($this->uri->segment(1)."/manga/edit/?id=".$key->story_id, $this->manga_model->show_manga_info("story_name", $key->story_id)); ?></strong> :
					<?php echo $key->chapter_name; ?>
					<div>
					<?php 
					$this->db->where("chapter_id", $key->chapter_id);
					$this->db->limit("5");
					$query2 = $this->db->get("chapter_images");
					if ( $query2->num_rows() > 0 ) {
						$im = 0;
						foreach ( $query2->result() as $row2 ) {
							echo "<a href=\"".base_url().$row2->image_file."\"><img src=\"".base_url().$row2->image_file."\" alt=\"img\" style=\"max-height: 50px; max-width: 50px;\" /></a> ";
							$im++;
						}
						echo "... ";
					}
					$this->db->where("chapter_id", $key->chapter_id);
					echo $this->db->count_all_results("chapter_images") . " " . $this->lang->line("chapter_pages");
					?>
					</div><!--preview images-->
				</td>
				<td><?php echo $key->scanlator; ?></td>
				<td><?php echo urldecode($key->chapter_uri); ?></td>
				<td><?php echo $key->chapter_add; ?></td>
				<td><img src="<?php echo base_url()."client/images/".($key->chapter_enable === '1' ? "yes" : "no").".gif"; ?>" alt="<?php echo ($key->chapter_enable === '1' ? "yes" : "no"); ?>" /></td>
				<td><?php echo anchor($this->uri->uri_string()."/edit?manga_id=$key->story_id&chapter_id=".$key->chapter_id, lang("admin_edit")); ?></td>
			</tr>
			<?php endforeach; ?>
			<?php else: ?>
			<tr>
				<td colspan="7"><?php echo lang("admin_nodata"); ?></td>
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