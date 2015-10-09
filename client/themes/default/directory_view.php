<?php include("inc_header_html.php"); ?>


		<!-- start page -->

<?php include("inc_header_sized.php"); ?>


		<div class="container page_width">
			<div class="article_full">
				<h1><?php echo $genre_name; ?></h1>
				<?php if ( isset($genre_description) && $genre_description != null ):?><p><?php echo $genre_description; ?></p><?php endif; ?>
				
				<?php
				if ( isset($list_manga) && is_array($list_manga) ) {
				?>
				<table class="manga_list_frame" width="100%">
					<tr>
						<th><?php echo lang("front_manga_name"); ?></th>
						<th><?php echo lang("front_total_chapters"); ?></th>
						<th><?php echo lang("front_last_update"); ?></th>
					</tr>
				<?php
					foreach ( $list_manga as $key => $item ) {
						if ( is_numeric($key) ) {
				?>
					<tr>
						<td width="50%">
							<?php echo anchor("manga/".urlencode($item['story_uri']), $item['story_name']); ?>
						</td>
						<td><?php echo $item['total_chapter']; ?></td>
						<td>
							<?php if ( isset($item['last_chapter']) ):?>
							<?php echo anchor("manga/".urlencode($item['story_uri'])."/".$item['last_chapter'], $item['last_chapter']); ?> <?php echo lang("front_on_when"); ?> <?php echo date("Y-m-d", strtotime($item['story_update'])); ?>
							<?php endif; ?>
						</td>
					</tr>
				<?php
						}// endif is_numeric $key
					}// end foreach
				?>
				</table>
				<div><?php echo $pagination; ?></div>
				<?php
				} else {
					echo "<p class=\"nodata_msg\">".lang("front_nodata")."</p>";
				}// endif is_array $list_manga
				?>
			</div><!--.article-->
		</div><!--.container-->


<?php include("inc_footer_sized.php"); ?>

		<!-- end page -->


<?php include("inc_footer_html.php"); ?>