
<div class="in_chapter">
	<h1><?php if ( isset($manga_name) && $manga_name != null ) {echo $manga_name." : ";} ?><?php echo lang("admin_chapters"); ?></h1>

	<span><?php if ( isset($list_chapters['total_chapter']) ) {echo $list_chapters['total_chapter']." ".lang("admin_items");} ?></span>
	<input type="button" name="btn" value="<?php echo lang("admin_add"); ?>" onclick="window.location='<?php echo base_url().$this->uri->segment(1)."/".$this->uri->segment(2); ?>/add?manga_id=<?php echo trim(strip_tags($this->input->get("manga_id", true))); ?>'" />

	<?php if ( $manga_name == null ):// select manga before add ?>
		<?php echo lang("admin_select_manga"); ?>
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
			<?php foreach ( $list_manga as $key => $item ): ?>
				<?php if ( is_numeric($key) ): ?>
				<option value="<?php echo $key; ?>"><?php echo $item['story_name']; ?></option>
				<?php endif;// is_numeric($key) ?>
			<?php endforeach; ?>
			</select>
		<?php endif;// if list_manga ?>
	<?php endif;// end check if manga was selected ?>

	<?php echo form_open($this->uri->segment(1)."/".$this->uri->segment(2)."/process_bulk"); ?>
		<?php if ( isset($form_status) ) {echo $form_status;} ?>
		<table class="item_list" width="100%">
			<tr>
				<th><input type="checkbox" name="id_all" value="" onclick="checkAll(this.form,'id[]',this.checked)" /></th>
				<th><?php echo lang("admin_order"); ?></th>
				<th><?php echo lang("admin_chapter_name"); ?></th>
				<th><?php echo lang("admin_scanlator"); ?></th>
				<th><?php echo lang("admin_uri"); ?></th>
				<th><?php echo lang("admin_add_date"); ?></th>
				<th><?php echo lang("admin_enable"); ?></th>
				<th></th>
			</tr>
			<?php
			if ( isset($list_chapters) && is_array($list_chapters) ) {
				$i = 1;
				foreach ( $list_chapters as $key => $item ) {
					if ( is_numeric($key) ) {
			?>
			<tr>
				<td><input type="checkbox" name="id[]" value="<?php echo $key; ?>" /></td>
				<td><?php echo $item['chapter_order']; ?></td>
				<td>
					<strong><?php echo anchor($this->uri->segment(1)."/manga/edit/?id=".$item['story_id'], $item['manga_name']); ?></strong> : <?php echo $item['chapter_name']; ?>
					<div>
					<?php 
					if ( is_array($item['chapter_images']) ) {
						$imgs = 1;
						foreach ( $item['chapter_images'] as $ki => $img ) {
							if ( $imgs <= 5 ) {
					?>
						<img src="<?php echo base_url().$img; ?>" alt="img" style="max-height: 50px; max-width: 50px;" />
					<?php
							}
							$imgs++;
						}
					}
					?> ... <?php echo $item['total_chapter_image']." ".lang("admin_pages"); ?>
					</div><!--preview images-->
				</td>
				<td><?php echo $item['scanlator']; ?></td>
				<td><?php echo $item['chapter_uri']; ?></td>
				<td><?php echo date("Y-m-d", strtotime($item['chapter_add'])); ?></td>
				<td><img src="<?php echo base_url()."client/images/".($item['chapter_enable'] === '1' ? "yes" : "no").".gif"; ?>" alt="<?php echo ($item['chapter_enable'] === '1' ? "yes" : "no"); ?>" /></td>
				<td>
					<?php echo anchor($this->uri->segment(1)."/".$this->uri->segment(2)."/edit?manga_id=".$item['story_id']."&chapter_id=".$key, lang("admin_edit")); ?>
				</td>
			</tr>
			<?php
						$i++;
					}
				}
			} else {
			?>
			<tr>
				<td colspan="7"><?php echo lang("admin_nodata"); ?></td>
			</tr>
			<?php
			}
			?>
		</table>
		<select name="cmd" size="1">
			<option value=""></option>
			<option value="delete"><?php echo lang("admin_delete"); ?></option>
		</select>
		<input type="submit" name="btn" value="<?php echo lang("admin_submit"); ?>" />
	<?php echo form_close("\n"); ?>
	<div><?php echo $pagination; ?></div><!--pagination-->
</div><!--.in_chapter-->
