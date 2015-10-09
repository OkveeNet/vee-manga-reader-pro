<h1><?php echo lang("comment_config"); ?></h1>

<?php echo form_open(); ?>
	<?php echo ( isset($form_status) ? $form_status : ""); ?>

	<dl class="form_item">
		<dt><?php echo lang("comment_enable"); ?>:</dt>
		<dd><input type="checkbox" name="comment_enable" value="1"<?php if ( isset($comment_enable) && $comment_enable == '1' ): ?> checked="checked"<?php endif; ?> /></dd>
		
		<dt><?php echo lang("comment_member_only"); ?>:</dt>
		<dd><input type="checkbox" name="comment_needs_member" value="1"<?php if ( isset($comment_needs_member) && $comment_needs_member == '1' ): ?> checked="checked"<?php endif; ?> /></dd>
		
		<dt><?php echo lang("comment_guest_is"); ?>:</dt>
		<dd>
			<label><input type="radio" name="comment_guest_is" value="0"<?php if ( isset($comment_guest_is) && $comment_guest_is == '0' ): ?> checked="checked"<?php endif; ?> /><?php echo lang("comment_waiting_moderate"); ?></label><br />
			<label><input type="radio" name="comment_guest_is" value="1"<?php if ( isset($comment_guest_is) && $comment_guest_is == '1' ): ?> checked="checked"<?php endif; ?> /><?php echo lang("comment_auto_approve"); ?></label>
		</dd>
		
		<dt><?php echo lang("comment_per_page"); ?>:</dt>
		<dd><input type="text" name="comment_per_page" value="<?php echo ( isset($comment_per_page) ? $comment_per_page : ""); ?>" /></dd>
		
		<dt><dt>&nbsp;</dt>
		<dd><input type="submit" name="btn" value="<?php echo lang("admin_save"); ?>" /></dd>
	</dl>

	<div class="clear"></div>
<?php echo form_close("\n"); ?>