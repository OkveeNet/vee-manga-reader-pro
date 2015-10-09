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

<!--div class="read"-->
<table class="read" width="100%">
	<tr><td>
	<h1><?php echo anchor($this->uri->segment(1)."/".$this->uri->segment(2), $story_name);?> &raquo; <?php echo anchor($this->uri->segment(1)."/".$this->uri->segment(2)."/".$this->uri->segment(3), $chapter_name); ?></h1>

	<div class="page_nav">
		<div class="selector">
			<?php echo lang("front_chapter"); ?>:
			<select name="chapters" class="change_chapter">
				<?php
				if ( isset($list_chapter) && is_array($list_chapter) ) {
					foreach ( $list_chapter as $key => $item ) {
						echo "<option value=\"".$item['chapter_uri']."\"".( $chapter_uri == $item['chapter_uri'] ? " selected=\"selected\"" : "" ).">".$item['chapter_name']."</option>\n";
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
				if ( isset($list_chapter) && is_array($list_chapter) ) {
					foreach ( $list_chapter as $key => $item ) {
						echo "<option value=\"".$item['chapter_uri']."\"".( $chapter_uri == $item['chapter_uri'] ? " selected=\"selected\"" : "" ).">".$item['chapter_name']."</option>\n";
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

	</td></tr>
</table>
<!--/div--> <!--.read-->