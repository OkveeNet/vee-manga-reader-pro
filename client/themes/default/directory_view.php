<div class="directory">
	<h1><?php echo lang("front_directory"); ?><?php if ( $genre_name != null ) {echo " : " . $genre_name;} ?></h1>
	<?php if ( $genre_description != null ): ?><p class="dir_desc"><?php echo $genre_description; ?></p><?php endif; ?>
	
	<?php if ( isset($list_item) && is_array($list_item) ): ?>
	<table class="manga_list_frame">
		<tr>
			<th class="name"><?php echo lang("front_manga_name"); ?></th>
			<th><?php echo lang("front_total_chapters"); ?></th>
			<th><?php echo lang("front_views"); ?></th>
			<th><?php echo lang("front_last_update"); ?></th>
		</tr>
	<?php
		foreach ( $list_item as $key => $item ) {
			if ( is_numeric($key) ) {
	?>
		<tr>
			<td>
				<a href="<?php echo site_url("manga/".$item['story_uri']); ?>"><img src="<?php echo base_url().$item['cover_tiny']; ?>" alt="" /></a>
				<?php echo anchor("manga/".$item['story_uri'], $item['story_name']); ?>
			</td>
			<td><?php echo $item['total_chapter']; ?></td>
			<td><?php echo $item['story_views']; ?></td>
			<td>
				<?php if ( isset($item['last_chapter']) ):?>
				<?php echo anchor("manga/".$item['story_uri']."/".$item['last_chapter_uri'], $item['last_chapter']); ?> <?php echo lang("front_on_when"); ?> <?php echo date("Y-m-d", strtotime($item['story_update'])); ?>
				<?php endif; ?>
			</td>
		</tr>
	<?php
			}// endif is_numeric $key
		}// end foreach
	?>
	</table>
	<?php echo (isset($pagination) ? $pagination : ""); ?>
	<?php else: ?>
	<p><?php echo lang("front_nodata"); ?></p>
	<?php endif; ?>
</div>