<?php include("inc_header_html.php"); ?>

		
		<!-- start page -->
		
<?php include("inc_header_sized.php"); ?>

		
		<div class="container page_width">
			
			<div class="article">
				<div class="front-page-banner">
					<img src="<?php echo base_url();?>client/images/front_promo.jpg" alt="banner" />
					<p>แก้ไขส่วน banner และใส่ข้อความตรงนี้ได้ที่ไฟล์ client/themes/default/index_view.php</p>
				</div><!--.front-page-banner-->
				
				<h1><?php echo lang("front_manga_new"); ?></h1>
				<?php
				if ( isset($list_manga) && is_array($list_manga) ) {
				?>
				<table class="manga_list" width="100%">
				<?php
					foreach ( $list_manga as $key => $item ) {
						if ( is_numeric($key) ) {
				?>
					<tr>
						<td>
							<?php echo anchor("manga/".urlencode($item['story_uri']), $item['story_name']); ?>
							<?php
							if ( isset($item['chapter_list']) && is_array($item['chapter_list']) ) {
								echo "<ul>\n";
								$ichapter = 1;
								foreach ( $item['chapter_list'] as $kc => $ic ) {
									if ( $ichapter <= '3' ) {// limit จำนวนตอนที่จะลิสต์ในหน้าแรก
										echo "<li>" . anchor("manga/".urlencode($item['story_uri'])."/".urlencode($ic['chapter_uri']), $ic['chapter_name']) . "</li>\n";
									}
									$ichapter++;
								}
								echo "</ul>\n";
							}// endif is_array($chapter_list)
							?>
						</td>
						<td><?php echo date("Y-m-d", strtotime($item['story_update'])); ?></td>
					</tr>
				<?php
						}// endif is_numeric $key
					}// end foreach
				?>
				</table>
				<?php
				} else {
					echo "<p>".lang("front_nodata")."</p>";
				}// endif is_array $list_manga
				?>
			</div><!--.article-->

			<div class="aside">
				<div class="box genre">
					<h3><?php echo lang("front_genre"); ?></h3>
					<?php
					if ( isset($list_genre) && is_array($list_genre) ) {
						echo "<ul>\n";
						foreach ( $list_genre as $key => $item ) {
							echo "<li>" . anchor(site_url("directory/".urlencode($item['genre_uri'])), $item['genre_name']) . "</li>\n";
						}
						echo "</ul>\n";
					} else {
						echo "<p class=\"nodata_msg\">".lang("front_nodata")."</p>";
					}// endif is_array $list_genre
					?>
				</div><!--.box genre-->
			</div><!--.aside-->
			
			<div class="clear"></div>
		</div><!--.container-->
		
		
<?php include("inc_footer_sized.php"); ?>
		
		<!-- end page -->


<?php include("inc_footer_html.php"); ?>