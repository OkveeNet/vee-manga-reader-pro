<div class="post_comment">
	
	<h3><?php echo lang("comment_post_comment"); ?></h3>

	<?php if ( $allow_comment == true ): ?>

	<?php echo form_open("comment/comment_form"); ?>
		<?php echo ( isset($form_status) ? $form_status : ""); ?>

		<input type="hidden" name="url" value="<?php echo current_url(); ?>" />
		<dl class="form_item">
			<dt><?php echo lang("comment_name"); ?>:</dt>
			<dd>
				<?php if ( $is_member_login ): ?>
				<?php 
				$cm_account = $this->account_model->get_account_cookie("member");
				if ( isset($cm_account['id']) || isset($cm_account['username']) ) {
					echo $cm_account['username'];
				} else {
					echo " - ";
				}
				?>
				<?php else: ?>
				<input type="text" name="name" value="<?php echo (isset($name) ? $name : ""); ?>" />
				<?php endif; ?>
			</dd>

			<dt><?php echo lang("comment_text"); ?>:</dt>
			<dd><textarea name="comment" cols="50" rows="10"><?php echo (isset($comment) ? $comment : ""); ?></textarea></dd>

			<dt>&nbsp;</dt>
			<dd><input type="submit" name="btn" value="<?php echo lang("comment_send"); ?>" /></dd>
		</dl>
		<div class="clear"></div>
	<?php echo form_close("\n"); ?>
	<?php if ( !$is_member_login && $this->config_model->load("comment_guest_is") == '0' ): ?>
		<span class="txt_comment"><?php echo lang("comment_needs_moderate"); ?></span>
	<?php endif; ?>

	<?php else: ?>

		<p><?php echo lang("comment_needs_member"); ?></p>

	<?php endif; ?>

</div><!--.post_comment-->