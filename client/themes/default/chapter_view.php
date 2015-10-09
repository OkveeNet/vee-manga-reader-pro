<script type="text/javascript" language="javascript">
	$(document).ready(function() {
		// change chapter
		$(".change_chapter").change(function() {
			var chapter_uri = $(this).val();
			window.location='<?php echo site_url($this->uri->segment(1)."/".$this->uri->segment(2))."/";?>'+chapter_uri;
		});
		// change page from selector
		$(".change_page").change(function() {
			var page_uri = $(this).val();
			window.location='<?php echo site_url($this->uri->segment(1)."/".$this->uri->segment(2)."/".$this->uri->segment(3))."/";?>'+page_uri;
		});
	});
</script>

<div class="read">
	<h1><?php echo anchor($this->uri->segment(1)."/".$this->uri->segment(2), $story_name);?> &raquo; <?php echo anchor($this->uri->segment(1)."/".$this->uri->segment(2)."/".$this->uri->segment(3), $chapter_name); ?></h1>

	<div class="page_nav">
		<div class="selector">
			<?php echo lang("front_chapter"); ?>:
			<select name="chapters" class="change_chapter">
				<?php
				if ( isset($list_chapter['list']) && is_array($list_chapter['list']) ) {
					foreach ( $list_chapter['list'] as $key ) {
						echo "<option value=\"".$key->chapter_uri."\"".( $chapter_uri == $key->chapter_uri ? " selected=\"selected\"" : "" ).">".$key->chapter_name."</option>\n";
					}
				}
				?>
			</select><!--select chapter-->
			<?php echo lang("front_page"); ?>:
			<select name="chapters" class="change_page">
				<?php
				if ( isset($list_chapter_page) && is_array($list_chapter_page) ) {
					foreach ( $list_chapter_page as $key => $item ) {
						echo "<option value=\"".$item['image_order']."\"".( $chapter_page == $item['image_order'] ? " selected=\"selected\"" : "" ).">".$item['image_order']."</option>\n";
					}
				}
				?>
			</select><!--select page-->
		</div><!--.selector-->
		<div class="nextprev">
			<input type="button" name="btn" value="<?php echo lang("front_previous"); ?>" onclick="window.location='<?php echo $chapter_page_prev; ?>';" />
			<input type="button" name="btn" value="<?php echo lang("front_next"); ?>" onclick="window.location='<?php echo $chapter_page_next; ?>';" />
		</div><!--.nextprev-->
		<div class="clear"></div>
	</div><!--.page_nav-->

	<div class="img">
		<a href="<?php echo $chapter_page_next; ?>"><img src="<?php if ( isset($chapter_page_img) ) {echo $chapter_page_img;} ?>" alt="<?php echo $chapter_name; ?>" /></a>
	</div><!--.img-->

	<div class="page_nav">
		<div class="selector">
			<?php echo lang("front_chapter"); ?>:
			<select name="chapters" class="change_chapter">
				<?php
				if ( isset($list_chapter['list']) && is_array($list_chapter['list']) ) {
					foreach ( $list_chapter['list'] as $key ) {
						echo "<option value=\"".$key->chapter_uri."\"".( $chapter_uri == $key->chapter_uri ? " selected=\"selected\"" : "" ).">".$key->chapter_name."</option>\n";
					}
				}
				?>
			</select><!--select chapter-->
			<?php echo lang("front_page"); ?>:
			<select name="chapters" class="change_page">
				<?php
				if ( isset($list_chapter_page) && is_array($list_chapter_page) ) {
					foreach ( $list_chapter_page as $key => $item ) {
						echo "<option value=\"".$item['image_order']."\"".( $chapter_page == $item['image_order'] ? " selected=\"selected\"" : "" ).">".$item['image_order']."</option>\n";
					}
				}
				?>
			</select><!--select page-->
		</div><!--.selector-->
		<div class="nextprev">
			<input type="button" name="btn" value="<?php echo lang("front_previous"); ?>" onclick="window.location='<?php echo $chapter_page_prev; ?>';" />
			<input type="button" name="btn" value="<?php echo lang("front_next"); ?>" onclick="window.location='<?php echo $chapter_page_next; ?>';" />
		</div><!--.nextprev-->
		<div class="clear"></div>
	</div><!--.page_nav-->
	
	<?php
	// list comment
	$this->load->module("comment");
	if ( method_exists($this->comment, "list_comment") ) {
		echo $this->comment->list_comment();
	}
	// post comment
	$this->load->module("comment");
	if ( method_exists($this->comment, "comment_form") ) {
		echo $this->comment->comment_form();
	}
	?>

</div> <!--.read-->