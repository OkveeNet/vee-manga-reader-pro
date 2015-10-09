<h1><?php if ( isset($manga_name) && $manga_name != null ) {echo $manga_name." : ";} ?><?php if ( $this->uri->segment(3) == "add" ) {echo lang("chapter_add");} else {echo lang("chapter_edit");} ?></h1>

<?php if ( $manga_name == null ):// select manga before add ?>
	<?php echo lang("chapter_select_manga"); ?>
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
<?php else:// manga selected, add ?>
	
	<?php echo form_open_multipart($this->uri->uri_string()."?manga_id=".$manga_id.($this->uri->segment(3) == "edit" ? "&chapter_id=$chapter_id" : "")); ?>
		<?php echo (isset($form_status) ? $form_status : ""); ?>
		
		<dl class="form_item">
			
			<dt><?php echo lang("chapter_order"); ?>:</dt>
			<dd><?php echo form_input("chapter_order", (isset($chapter_order) ? $chapter_order : ""), "maxlength=\"200\""); ?></dd>
			<dd class="comment"><span class="txt_require">*</span> <?php echo lang("chapter_order_comment"); ?></dd>
			
			<dt><?php echo lang("chapter_name"); ?>:</dt>
			<dd><?php echo form_input("chapter_name", (isset($chapter_name) ? $chapter_name : ""), "maxlength=\"255\""); ?></dd>
			<dd class="comment"><span class="txt_require">*</span></dd>
			
			<dt><?php echo lang("chapter_uri"); ?>:</dt>
			<dd><?php echo form_input("chapter_uri", (isset($chapter_uri) ? $chapter_uri : ""), "maxlength=\"255\" onblur=\"get_chapter_uri()\" id=\"chapter_uri\"".($this->uri->segment(3) == "edit" ? " disabled=\"disabled\"" : "")); ?></dd>
			<dd class="comment"><span class="txt_require">*</span></dd>
			
			<dt><?php echo lang("chapter_scanlator"); ?>:</dt>
			<dd><?php echo form_input("scanlator", (isset($scanlator) ? $scanlator : ""), "maxlength=\"255\""); ?></dd>
			
			<dt><label for="enable_chapter"><?php echo lang("chapter_enable"); ?>:</label></dt>
			<dd><input type="checkbox" name="chapter_enable" value="1"<?php if ( isset($chapter_enable) && $chapter_enable === '1' ) {echo " checked=\"checked\"";} ?> id="enable_chapter" /></dd>
			
			<dt><?php echo lang("chapter_file"); ?>:</dt>
			<dd><input type="file" name="image_file" value=""<?php if ( $this->uri->segment(3) == "edit" ) {echo " disabled=\"disabled\"";} ?> /></dd>
			<dd class="comment">
				<span class="txt_require">*</span>
				< <?php echo ini_get("upload_max_filesize"); ?><br /><br /><?php echo lang("chapter_image_comment"); ?>
			</dd>
			
			<dt>&nbsp;</dt>
			<dd><input type="submit" name="btn" value="<?php echo lang("admin_submit"); ?>" /></dd>
			
		</dl>
		
	<?php echo form_close("\n"); ?>
	
<?php endif; ?>
