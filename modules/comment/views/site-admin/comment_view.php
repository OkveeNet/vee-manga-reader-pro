<h1><?php echo lang("comment_comment"); ?></h1>

<div class="list_item_cmdleft">
	<?php echo sprintf(lang("comment_total"), (isset($list_comment['total_comment']) ? $list_comment['total_comment'] : "0")); ?>
</div><!--.list_item_cmdleft-->
<div class="clear"></div>

<?php echo form_open(current_url()."/process_bulk"); ?>
	<?php echo (isset($form_status) ? $form_status : ""); ?>

	<table class="list_item">
		<thead>
			<tr>
				<th><input type="checkbox" name="id_all" value="" onclick="checkAll(this.form,'id[]',this.checked)" /> ID</th>
				<th><?php echo lang("comment_url"); ?></th>
				<th><?php echo lang("comment_text"); ?></th>
				<th><?php echo lang("comment_by"); ?></th>
				<th><?php echo lang("comment_date"); ?></th>
				<th><?php echo lang("comment_approve"); ?></th>
			</tr>
		</thead>
		<tbody>
			<?php if ( isset($list_comment) && is_array($list_comment) ): ?>
			<?php foreach ( $list_comment as $key => $item ): ?>
				<?php if ( is_numeric($key) ): ?>
			<tr>
				<td><?php echo form_checkbox("id[]", $key); ?> <?php echo $key; ?></td>
				<td><?php echo anchor($item['article_url'], $item['article_url']); ?></td>
				<td><?php echo $item['comment']; ?></td>
				<td><?php echo nl2br(mb_strimwidth($item['comment_name'], 0, 50, "...")); ?></td>
				<td><?php echo $item['comment_date']; ?></td>
				<td><img src="<?php echo base_url(); ?>client/images/<?php echo ($item['comment_approved'] == '1' ? "yes" : "no"); ?>.gif" alt="<?php echo ($item['comment_approved'] == '1' ? lang("comment_yes") : lang("comment_no")); ?>" /></td>
			</tr>
				<?php endif; ?>
			<?php endforeach; ?>
			<?php else: ?>
			<tr>
				<td colspan="6"><?php echo lang("admin_nodata"); ?></td>
			</tr>
			<?php endif; ?>
		</tbody>
	</table>

	<div class="list_item_cmdleft">
		<select name="cmd">
			<option></option>
			<option value="approve"><?php echo lang("comment_approve"); ?></option>
			<option value="unapprove"><?php echo lang("comment_unapprove"); ?></option>
			<option value="del"><?php echo lang("admin_delete"); ?></option>
		</select>
		<?php echo form_submit("btn", lang("admin_submit")); ?>
	</div><!--.list_item_cmdleft-->
	<div class="list_item_cmdright">
		<?php echo (isset($pagination) ? $pagination : ""); ?>
	</div><!--.list_item_cmdright-->
	<div class="clear"></div>
<?php echo form_close("\n"); ?>