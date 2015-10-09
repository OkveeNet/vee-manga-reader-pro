<h1><?php echo lang("account_account"); ?></h1>

<div class="list_item_cmdleft">
	<?php echo form_button("btn", lang("account_add"), "onclick=\"window.location='" . site_url($this->uri->segment(1)."/".$this->uri->segment(2)."/add") . "'\""); ?>
	<?php echo sprintf(lang("account_total"), (isset($list_account['total_account']) ? $list_account['total_account'] : "0")); ?>
</div><!--.list_item_cmdleft-->
<div class="list_item_cmdright">
	<form method="get" action="">
		<input type="text" name="q" value="<?php echo htmlentities($this->input->get("q", true), ENT_QUOTES, "UTF-8"); ?>" />
		<?php echo form_submit("btn", lang("admin_search")); ?>
	</form>
</div><!--.list_item_cmdright-->
<div class="clear"></div>


<?php echo form_open(current_url()."/process_bulk"); ?>
	<?php echo (isset($form_status) ? $form_status : ""); ?>

	<table class="list_item">
		<thead>
			<tr>
				<th><input type="checkbox" name="id_all" value="" onclick="checkAll(this.form,'id[]',this.checked)" /></th>
				<th><?php echo anchor(current_url()."?orders=account_id", lang("account_id")); ?></th>
				<th><?php echo anchor(current_url()."?orders=account_username", lang("account_username")); ?></th>
				<th><?php echo anchor(current_url()."?orders=account_email", lang("account_email")); ?></th>
				<th><?php echo anchor(current_url()."?orders=account_create", lang("account_registered_since")); ?></th>
				<th><?php echo anchor(current_url()."?orders=account_last_login", lang("account_last_login")); ?></th>
				<th><?php echo anchor(current_url()."?orders=account_status", lang("account_status")); ?></th>
				<th></th>
			</tr>
		</thead>
		<tbody>
			<?php if ( isset($list_account) && is_array($list_account) ): ?>
			<?php foreach ( $list_account as $key => $item ): ?>
				<?php if ( is_numeric($key) ): ?>
			<tr>
				<td><?php echo form_checkbox("id[]", $key); ?></td>
				<td><?php echo $key; ?> [<?php echo $item['level_name']; ?>]</td>
				<td><a title="<?php echo $item['account_fullname']; ?>" class="cursor_default"><?php echo $item['account_username']; ?></a></td>
				<td><?php echo $item['account_email']; ?></td>
				<td><?php echo $item['account_create']; ?></td>
				<td><?php echo $item['account_last_login']; ?></td>
				<td>
					<img src="<?php echo base_url(); ?>client/images/<?php echo ($item['account_status'] == '1' ? "yes" : "no"); ?>.gif" alt="<?php echo lang("account_status"); ?>" />
					<?php echo ($item['account_status'] == '0' ? $item['account_status_text'] : ""); ?>
				</td>
				<td>
					<?php echo anchor($this->uri->segment(1)."/".$this->uri->segment(2)."/edit?id=$key", lang("admin_edit")); ?>
					<?php echo anchor($this->uri->segment(1)."/".$this->uri->segment(2)."/viewlog?id=$key", lang("account_view_logins")); ?>
				</td>
			</tr>
				<?php endif; ?>
			<?php endforeach; ?>
			<?php else: ?>
			<tr>
				<td colspan="8"><?php echo lang("admin_nodata"); ?></td>
			</tr>
			<?php endif; ?>
		</tbody>
	</table>

	<div class="list_item_cmdleft">
		<select name="cmd">
			<option></option>
			<option value="del"><?php echo lang("admin_delete"); ?></option>
		</select>
		<?php echo form_submit("btn", lang("admin_submit")); ?>
	</div><!--.list_item_cmdleft-->
	<div class="list_item_cmdright">
		<?php echo (isset($pagination) ? $pagination : ""); ?>
	</div><!--.list_item_cmdright-->
	<div class="clear"></div>
<?php echo form_close("\n"); ?>

