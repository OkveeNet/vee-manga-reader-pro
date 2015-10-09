
<div class="in_manga">
	<h1><?php echo lang("admin_manga"); ?> : <?php if ( $this->uri->segment(3) == "add" ) {echo lang("admin_add");} else {echo lang("admin_edit");} ?></h1>

	<script language="javascript" type="text/javascript">
		tinyMCE.init({
			extended_valid_elements : "a[name|rel|href|target|title|onclick],img[class|src|border=0|alt|title|hspace|vspace|width|height|align|onmouseover|onmouseout|name|rel|style],hr[class|width|size|noshade],font[face|size|color|style],span[class|align|style]",
			mode : "textareas",
			apply_source_formatting : false,
			relative_urls : false,
			convert_urls : false,
			document_base_url : "<?php echo base_url(); ?>",
			plugins : "contextmenu, directionality, media, paste, safari, fullscreen",
			theme : "advanced",
			theme_advanced_buttons1 : "bold, italic, underline, fontselect, fontsizeselect,removeformat",
			theme_advanced_buttons2 : "forecolor, backcolor, separator, justifyleft, justifycenter, justifyright, justifyfull, separator, bullist, numlist, separator, undo, redo, separator, link, unlink, separator, code, fullscreen",
			theme_advanced_buttons3 : "",
			theme_advanced_buttons1_add : "media,image",
			theme_advanced_resizing : true,
			theme_advanced_statusbar_location : "bottom",
			theme_advanced_toolbar_align : "left",
			theme_advanced_toolbar_location : "top"
		});
	</script>

	<?php echo form_open_multipart(base_url().$this->uri->segment(1)."/".$this->uri->segment(2)."/".$this->uri->segment(3).($this->uri->segment(3) == "edit" ? "?id=".$this->input->get("id", true) : ""))."\n"; ?>
		<?php if ( isset($form_status) ) {echo $form_status;} ?>
		<table class="item_form" width="100%">
			<tr>
				<th><?php echo lang("admin_genre"); ?></th>
				<td>
					<?php 
					if ( isset($genre_list) && is_array($genre_list) ) {
						foreach ( $genre_list as $key=>$item ) {
							?>
					<label<?php if ( $item['genre_enable'] === '0' ) {echo " class=\"txt_disabled\"";} ?>><input type="checkbox" name="story_genre[]" value="<?php echo $key; ?>"<?php if ( isset($story_genre) && in_array($key, $story_genre) ) {echo " checked=\"checked\"";} ?> /><?php echo $item['genre_name']; ?></label>
							<?php
						}// endforeach
					}// endif genre_list
					?>
				</td>
			</tr>
			<tr>
				<th><?php echo lang("admin_manga_name"); ?></th>
				<td><input type="text" name="story_name" value="<?php if ( isset($story_name) ) {echo $story_name;} ?>" maxlength="255" /> <span class="txt_required">*</span></td>
			</tr>
			<tr>
				<th><?php echo lang("admin_uri"); ?></th>
				<td>
					<input type="text" name="story_uri" value="<?php if ( isset($story_uri) ) {echo $story_uri;} ?>" maxlength="255"<?php if ( $this->uri->segment(3) == "edit" ) {echo " disabled=\"disabled\"";} ?> onblur="manga_uri();" id="story_uri" />
					<span class="txt_required">*</span>
					<span class="txt_comment">(<?php echo lang("admin_uri_comment"); ?>)</span>
				</td>
			</tr>
			<tr>
				<th><?php echo lang("admin_manga_statinfo"); ?></th>
				<td><textarea name="story_statinfo" cols="60" rows="7"><?php if ( isset($story_statinfo) ) {echo $story_statinfo;} ?></textarea></td>
			</tr>
			<tr>
				<th><?php echo lang("admin_manga_summary"); ?></th>
				<td><textarea name="story_summary" cols="60" rows="7"><?php if ( isset($story_summary) ) {echo $story_summary;} ?></textarea></td>
			</tr>
			<tr>
				<th><?php echo lang("admin_author"); ?></th>
				<td><input type="text" name="story_author" value="<?php if ( isset($story_author) ) {echo $story_author;} ?>" maxlength="255" /></td>
			</tr>
			<tr>
				<th><?php echo lang("admin_artist"); ?></th>
				<td><input type="text" name="story_artist" value="<?php if ( isset($story_artist) ) {echo $story_artist;} ?>" maxlength="255" /></td>
			</tr>
			<tr>
				<th><?php echo lang("admin_cover_image"); ?></th>
				<td>
					<?php if ( $this->uri->segment(3) == "edit" ) : ?><div><img src="<?php echo base_url().$cover_small; ?>" alt="<?php if ( isset($story_name) ) {echo $story_name;} ?>" width="100" /></div><?php endif; ?>
					<input type="file" name="story_cover" value="" /> 
					<span class="txt_required">*</span>
					<span class="txt_comment">(<?php echo ini_get("upload_max_filesize"); ?>)</span>
				</td>
			</tr>
			<tr>
				<th><label for="manga_enable"><?php echo lang("admin_enable"); ?></label></th>
				<td><input type="checkbox" name="story_enable" value="1"<?php if ( isset($story_enable) && $story_enable === '1' ) {echo " checked=\"checked\"";} ?> id="manga_enable" /></td>
			</tr>
			<tr>
				<th></th>
				<td><input type="submit" name="btn" value="<?php echo lang("admin_submit"); ?>" /></td>
			</tr>
		</table>
	<?php echo form_close("\n"); ?>
</div><!--.in_manga-->
