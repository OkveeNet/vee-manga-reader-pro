<div class="mostviews">
	<h3><?php echo lang("mostview_mv"); ?></h3>
	<?php if ( isset($list_item['list']) && is_array($list_item['list']) ): ?>
	<?php $i = 1; ?>
	<ul>
		<?php foreach ( $list_item['list'] as $key ): ?>
		<li>
			<a href="<?php echo site_url("manga/".$key->story_uri); ?>"><img src="<?php echo $this->manga_model->set_image_size($key->story_cover, "tiny"); ?>" alt="<?php echo $key->story_name; ?>" /></a>
			<?php echo anchor("manga/".$key->story_uri, $key->story_name); ?><br />
			<?php
			$chapter = $this->mostview_model->get_last_chapter($key->story_id);
			if ( $chapter !== false ) {
				echo anchor("manga/".$key->story_uri."/".$chapter->chapter_uri, $chapter->chapter_name);
			}
			?>
			<div class="clear"></div>
		</li>
		<?php
		$i++;
		if ( $i > 10 ) {break;}
		?>
	<?php endforeach; ?>
	</ul>
	<div class="clear"></div>
	<?php else: ?>
	<p><?php echo lang("front_nodata"); ?></p>
	<?php endif; ?>
</div><!--.most-views-->