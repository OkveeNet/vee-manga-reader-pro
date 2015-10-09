<div class="manga-page">
	<article>
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
	</article>
	
	<div class="list-chapters">
		<?php if ( isset($list_chapters['list']) && is_array($list_chapters['list']) ): ?>
		<table class="manga_list_frame">
			<tr>
				<th class="name"><?php echo lang("front_chapter_name"); ?></th>
				<th><?php echo lang("front_scanlator"); ?></th>
				<th><?php echo lang("front_add_date"); ?></th>
			</tr>
		<?php foreach ( $list_chapters['list'] as $key ): ?>
			<tr>
				<td>
					<?php echo anchor("manga/".$story_uri."/".$key->chapter_uri, $key->chapter_name); ?>
				</td>
				<td><?php echo $key->scanlator; ?></td>
				<td><?php echo date("Y-m-d", strtotime($key->chapter_add)); ?></td>
			</tr>
		<?php endforeach; ?>
		</table>
		<?php else: ?>
		<p><?php echo lang("front_nochapter"); ?></p>
		<?php endif; ?>
	</div><!--.list-chapters-->
	
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
</div><!--.manga-page-->