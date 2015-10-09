
<div class="in_chapter">
	<h1><?php if ( isset($manga_name) && $manga_name != null ) {echo $manga_name." : ";} ?><?php echo lang("admin_chapters"); ?> : <?php if ( $this->uri->segment(3) == "add" ) {echo lang("admin_add");} else {echo lang("admin_edit");} ?></h1>

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
	<?php else:// manga selected, add ?>

		<?php echo form_open_multipart( $this->uri->segment(1)."/".$this->uri->segment(2)."/".$this->uri->segment(3)."?manga_id=".$manga_id.($this->uri->segment(3) == "edit" ? "&chapter_id=".$chapter_id : "") ); ?>
			<?php if ( isset($form_status) ) {echo $form_status;} ?>
			<table class="item_form" width="100%">
				<tr>
					<th><?php echo lang("admin_order"); ?></th>
					<td><input type="text" name="chapter_order" value="<?php if ( isset($chapter_order) ) {echo $chapter_order;} ?>" maxlength="200" /> <span class="txt_required">*</span></td>
					<td><span class="txt_comment"><?php echo lang("admin_order_comment"); ?></span></td>
				</tr>
				<tr>
					<th><?php echo lang("admin_chapter_name"); ?></th>
					<td><input type="text" name="chapter_name" value="<?php if ( isset($chapter_name) ) {echo $chapter_name;} ?>" maxlength="255" /> <span class="txt_required">*</span></td>
					<td></td>
				</tr>
				<tr>
					<th><?php echo lang("admin_uri"); ?></th>
					<td><input type="text" name="chapter_uri" value="<?php if ( isset($chapter_uri) ) {echo $chapter_uri;} ?>" maxlength="255" onblur="get_chapter_uri()" id="chapter_uri"<?php if ( $this->uri->segment(3) == "edit" ) {echo " disabled=\"disabled\"";} ?> /> <span class="txt_required">*</span></td>
					<td></td>
				</tr>
				<tr>
					<th><?php echo lang("admin_scanlator"); ?></th>
					<td><input type="text" name="scanlator" value="<?php if ( isset($scanlator) ) {echo $scanlator;} ?>" maxlength="255" /></td>
					<td></td>
				</tr>
				<tr>
					<th><label for="enable_chapter"><?php echo lang("admin_enable"); ?></label></th>
					<td><input type="checkbox" name="chapter_enable" value="1"<?php if ( isset($chapter_enable) && $chapter_enable === '1' ) {echo " checked=\"checked\"";} ?> id="enable_chapter" /></td>
					<td></td>
				</tr>
				<tr>
					<th><?php echo lang("admin_files"); ?></th>
					<td><input type="file" name="image_file" value=""<?php if ( $this->uri->segment(3) == "edit" ) {echo " disabled=\"disabled\"";} ?> /> <span class="txt_required">*</span></td>
					<td><span class="txt_comment"><?php echo ini_get("upload_max_filesize"); ?><br /><br /><?php echo lang("admin_chapter_image_comment"); ?></span></td>
				</tr>
				<tr>
					<th></th>
					<td colspan="2"><input type="submit" name="btn" value="<?php echo lang("admin_submit"); ?>" /></td>
				</tr>
			</table>
		<?php echo form_close("\n"); ?>

	<?php endif;// end check if manga was selected? ?>
</div><!--.in_chapter-->
