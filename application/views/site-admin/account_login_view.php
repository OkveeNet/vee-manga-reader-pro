<h1><?php echo lang("account_view_logins"); ?>: <?php echo (isset($account_username) ? $account_username : ""); ?></h1>

<?php echo form_open($this->uri->segment(1)."/".$this->uri->segment(2)."/deletelog?id=$id"); ?>
	<?php echo (isset($form_status) ? $form_status : ""); ?>
	
	<table class="list_item">
		<thead>
			<tr>
				<th><?php echo lang("account_useragent"); ?></th>
				<th><?php echo lang("account_os"); ?></th>
				<th><?php echo lang("account_browser"); ?></th>
				<th><?php echo lang("account_ip"); ?></th>
				<th><?php echo lang("account_time"); ?></th>
				<th><?php echo lang("account_attempt"); ?></th>
			</tr>
		</thead>
		<tbody>
			<?php if ( isset($list_logins) && is_array($list_logins) ): ?>
			<?php foreach ( $list_logins as $key => $item ): ?>
			<?php if ( is_numeric($key) ): ?>
			
			<tr>
				<td><?php echo $item['login_ua']; ?></td>
				<td><?php echo $item['login_os']; ?></td>
				<td><?php echo $item['login_browser']; ?></td>
				<td><?php echo $item['login_ip']; ?></td>
				<td><?php echo $item['login_time']; ?></td>
				<td><img src="<?php echo base_url(); ?>client/images/<?php echo ($item['login_attempt'] == '1' ? 'yes' : 'no'); ?>.gif" alt="<?php echo $item['login_attempt']; ?>" /> <?php echo $item['login_attempt_text']; ?></td>
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
			<option value="del"><?php echo lang("account_delete"); ?></option>
			<?php if ( $this->account_model->show_account_level_info() === '1' ): ?><option value="truncate"><?php echo lang("account_delete_all_allofusers"); ?></option><?php endif; ?>
		</select>
		<?php echo form_submit("btn", lang("admin_submit")); ?>
	</div><!--.list_item_cmdleft-->
	<div class="list_item_cmdright">
		<?php echo (isset($pagination) ? $pagination : ""); ?>
	</div><!--.list_item_cmdright-->
	<div class="clear"></div>
<?php echo form_close("\n"); ?>