<?php include("inc_header_html.php"); ?>


		<!-- start page -->

<?php include("inc_header_sized.php"); ?>


		<div class="container page_width">
			<div class="article_full">
				<h1><?php echo $story_name; ?></h1>
				<div class="manga_info">
					<?php if ( isset($cover_medium) && $cover_medium != null ):?><img src="<?php echo base_url().$cover_medium; ?>" alt="<?php echo $story_name; ?>" id="cover" /><?php endif; ?>
					<?php echo $story_statinfo; ?>
					<?php if ( $story_author != null ): ?>
					<p><strong><?php echo lang("front_author"); ?></strong>: <?php echo $story_author; ?></p>
					<?php endif; //author ?>
					<?php if ( $story_artist != null ): ?>
					<p><strong><?php echo lang("front_artist"); ?></strong>: <?php echo $story_artist; ?></p>
					<?php endif; //artist ?>
					<?php if ( $story_summary != null ): ?>
					<h2><?php echo lang("front_summary"); ?></h2>
					<?php echo $story_summary; ?>
					<?php endif; // summary ?>
					<div class="clear"></div>
				</div><!--.manga_info-->
				
				<?php
				if ( isset($list_chapters) && is_array($list_chapters) ) {
				?>
				<table class="manga_list_frame" width="100%">
					<tr>
						<th><?php echo lang("front_chapter_name"); ?></th>
						<th><?php echo lang("front_scanlator"); ?></th>
						<th><?php echo lang("front_add_date"); ?></th>
					</tr>
				<?php
					foreach ( $list_chapters as $key => $item ) {
						if ( is_numeric($key) ) {
				?>
					<tr>
						<td width="50%">
							<?php echo anchor("manga/".urlencode($story_uri)."/".urlencode($item['chapter_uri']), $item['chapter_name']); ?>
						</td>
						<td><?php echo $item['scanlator']; ?></td>
						<td><?php echo date("Y-m-d", strtotime($item['chapter_add'])); ?></td>
					</tr>
				<?php
						}// endif is_numeric $key
					}// end foreach
				?>
				</table>
				<?php
				} else {
					echo "<p class=\"nodata_msg\">".lang("front_nodata")."</p>";
				}// endif is_array $list_chapters
				?>
				
			</div><!--.article_full-->
		</div><!--.container-->


<?php include("inc_footer_sized.php"); ?>

		<!-- end page -->


<?php include("inc_footer_html.php"); ?>