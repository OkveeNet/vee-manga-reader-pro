<h1><?php echo lang("manga_manga"); ?> : <?php echo ( $this->uri->segment($this->uri->total_segments()) == "edit" ? lang("manga_edit") : lang("manga_add") ); ?></h1>

<?php echo form_open_multipart( $this->uri->uri_string().($this->uri->segment($this->uri->total_segments()) == "edit" ? "?id=".$this->input->get("id", true) : "") ); ?>
	<?php echo (isset($form_status) ? $form_status : ""); ?> 

	<dl class="form_item">
		
		<dt><?php echo lang("genre_genre"); ?>:</dt>
		<dd>
			<?php 
			if ( isset($genre_list['list']) && is_array($genre_list['list']) ) {
				foreach ( $genre_list['list'] as $key ) {
					?>
			<label><input type="checkbox" name="story_genre[]" value="<?php echo $key->genre_id; ?>"<?php if ( isset($story_genre) && is_array($story_genre) && in_array($key->genre_id, $story_genre) ) {echo " checked=\"checked\"";} ?> /><?php echo $key->genre_name; ?></label>
					<?php
				}// endforeach
			}// endif genre_list
			?>
		</dd>
		
		<dt><?php echo lang("manga_name"); ?>:</dt>
		<dd><?php echo form_input("story_name", (isset($story_name) ? $story_name : ""), "maxlength=\"255\""); ?></dd>
		<dd class="comment"><span class="txt_require">*</span></dd>
		
		<dt><?php echo lang("manga_uri"); ?>:</dt>
		<dd><?php echo form_input("story_uri", (isset($story_uri) ? $story_uri : ""), "maxlength=\"255\" onblur=\"manga_uri();\" id=\"story_uri\"".($this->uri->segment($this->uri->total_segments()) == "edit" ? " disabled=\"disabled\"" : "")); ?></dd>
		<dd class="comment"><span class="txt_require">*</span> <?php echo lang("manga_uri_comment"); ?></dd>
		
		<dd class="full"><?php echo lang("manga_stat_info"); ?>:<br />
		<textarea name="story_statinfo" cols="60" rows="7"><?php if ( isset($story_statinfo) ) {echo $story_statinfo;} ?></textarea>
		<br /><span class="txt_comment"><?php echo lang("manga_stat_info_comment"); ?></span>
		</dd>
		
		<dd class="full"><?php echo lang("manga_summary"); ?>:<br />
		<textarea name="story_summary" cols="60" rows="7"><?php if ( isset($story_summary) ) {echo $story_summary;} ?></textarea>
		</dd>
		
		<dt><?php echo lang("manga_author"); ?>:</dt>
		<dd><?php echo form_input("story_author", (isset($story_author) ? $story_author : ""), "maxlength=\"255\""); ?></dd>
		
		<dt><?php echo lang("manga_artist"); ?>:</dt>
		<dd><?php echo form_input("story_artist", (isset($story_artist) ? $story_artist : ""), "maxlength=\"255\""); ?></dd>
		
		<dt><?php echo lang("manga_cover_image"); ?>:</dt>
		<dd>
			<?php if ( $this->uri->segment($this->uri->total_segments()) == "edit" ) : ?><div><a href="<?php echo base_url().$story_cover; ?>"><img src="<?php echo base_url().$cover_small; ?>" alt="<?php if ( isset($story_name) ) {echo $story_name;} ?>" /></a></div><?php endif; ?>
			<input type="file" name="story_cover" value="" />
		</dd>
		<dd class="comment"><span class="txt_require">*</span> ( < <?php echo ini_get("upload_max_filesize"); ?> )</dd>
		
		<dt><?php echo lang("manga_enable"); ?>:</dt>
		<dd><input type="checkbox" name="story_enable" value="1"<?php if ( isset($story_enable) && $story_enable === '1' ) {echo " checked=\"checked\"";} ?> id="manga_enable" /></dd>
		
		<dt>&nbsp;</dt>
		<dd><input type="submit" name="btn" value="<?php echo lang("admin_submit"); ?>" /></dd>
		
	</dl>
	<div class="clear"></div>

<?php echo form_close("\n"); ?>

<script type="text/javascript">
$(document).ready(function() {
	// tinymce to textareas
	$('textarea').tinymce({
		script_url: '<?php echo base_url(); ?>client/js/tiny_mce/tiny_mce.js',
		apply_source_formatting : true,
		convert_urls : false,
		document_base_url : "<?php echo base_url(); ?>",
		inline_styles : true,
		plugins : "contextmenu, directionality, emotions, media, paste, fullscreen",
		preformatted : false,
		relative_urls : false,
		theme : "advanced",
		theme_advanced_buttons1 : "bold, italic, underline, strikethrough, blockquote, fontselect, fontsizeselect,removeformat, charmap, media, image, emotions",
		theme_advanced_buttons2 : "forecolor, backcolor, separator, justifyleft, justifycenter, justifyright, justifyfull, separator, bullist, numlist, separator, outdent, indent, undo, redo, separator, link, unlink, separator, code, fullscreen",
		theme_advanced_buttons3 : "",
		theme_advanced_toolbar_align : "left",
		theme_advanced_toolbar_location : "top",
		theme_advanced_statusbar_location: "bottom",
		theme_advanced_resizing : true,
		theme_advanced_resize_horizontal : true
	});// tinymce content
});// jquery document ready
</script>