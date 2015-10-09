<div class="list_comment">
	
	<h3><?php echo lang("comment_comment"); ?></h3>

	<?php if ( isset($list_comment) && is_array($list_comment) ): ?>
	<?php foreach ( $list_comment as $key => $item ): ?>
		<?php if ( is_numeric($key) ): ?>

	<div class="a_comment">
		<ul>
			<li class="comment_name"><?php echo $item['comment_name']; ?></li>
			<li class="comment_text">
				<p class="comment_date"><?php echo $item['comment_date']; ?></p>
				<p><?php echo nl2br($item['comment']); ?></p>
			</li>
		</ul>
		<div class="clear"></div>
	</div>

		<?php endif; ?>
	<?php endforeach; ?>
	<div class="comment_pagination"><?php echo $pagination; ?></div>
	<?php else: ?>
	<p><?php echo lang("comment_no_comment"); ?></p>
	<?php endif; ?>

</div><!--.list_comment-->