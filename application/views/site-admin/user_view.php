
<div class="in_users">
	<h1><?php echo lang("admin_users"); ?></h1>

	<span><?php if ( isset($user_list['total_users']) ) {echo $user_list['total_users'];} ?> <?php echo lang("admin_items"); ?></span>
	<input type="button" name="btn" value="<?php echo lang("admin_add"); ?>" onclick="window.location='<?php echo base_url().$this->uri->segment(1)."/".$this->uri->segment(2); ?>/add'" />

	<?php echo form_open($this->uri->segment(1)."/".$this->uri->segment(2)."/process_bulk"); ?>
		<?php if ( isset($form_status) ) {echo $form_status;} ?>
		<table class="item_list" width="100%">
			<tr>
				<th><input type="checkbox" name="id_all" value="" onclick="checkAll(this.form,'id[]',this.checked)" /></th>
				<th><?php echo lang("admin_id"); ?></th>
				<th><?php echo lang("admin_username"); ?></th>
				<th><?php echo lang("admin_email"); ?></th>
				<th><?php echo lang("admin_birth_date"); ?></th>
				<th><?php echo lang("admin_add_date"); ?></th>
				<th><?php echo lang("admin_last_login"); ?></th>
				<th><?php echo lang("admin_level"); ?></th>
				<th><?php echo lang("admin_status"); ?></th>
				<!--th></th-->
				<th></th>
			</tr>
			<?php
			if ( isset($user_list) && is_array($user_list) ) {
				$i = 1;
				foreach ( $user_list as $key => $item ) {
					if ( is_numeric($key) ) {
			?>
			<tr>
				<td><input type="checkbox" name="id[]" value="<?php echo $key; ?>" /></td>
				<td><?php echo $key; ?></td>
				<td><?php echo $item['account_username']; ?></td>
				<td><?php echo $item['account_email']; ?></td>
				<td><?php echo $item['account_birth_date']; ?></td>
				<td><?php echo date("Y-m-d", strtotime($item['account_create'])); ?></td>
				<td><?php echo $item['account_last_login']; ?></td>
				<td><?php echo lang("admin_level".$item['account_level']); ?></td>
				<td><img src="<?php echo base_url(); ?>client/images/<?php echo ($item['account_status'] === '1' ? "yes" : "no"); ?>.gif" alt="<?php echo ($item['account_status'] === '1' ? "yes" : "no"); ?>" /></td>
				<!--td></td-->
				<td>
					<?php echo anchor($this->uri->segment(1)."/users/edit?id=".$key, lang("admin_edit")); ?>
				</td>
			</tr>
			<?php
						$i++;
					}// endif is_numeric $key
				}// endfoerach
				if ( $i >= 10 ) {
			?>
			<tr>
				<th><input type="checkbox" name="id_all" value="" onclick="checkAll(this.form,'id[]',this.checked)" /></th>
				<th><?php echo lang("admin_id"); ?></th>
				<th><?php echo lang("admin_username"); ?></th>
				<th><?php echo lang("admin_email"); ?></th>
				<th><?php echo lang("admin_birth_date"); ?></th>
				<th><?php echo lang("admin_add_date"); ?></th>
				<th><?php echo lang("admin_last_login"); ?></th>
				<th><?php echo lang("admin_level"); ?></th>
				<th><?php echo lang("admin_status"); ?></th>
				<!--th></th-->
				<th></th>
			</tr>
			<?php
				}// endif $i >= 10
			}// endif isset & is_array $user_list
			?>
		</table>
		<select name="cmd" size="1">
			<option value=""></option>
			<option value="delete"><?php echo lang("admin_delete"); ?></option>
		</select>
		<input type="submit" name="btn" value="<?php echo lang("admin_submit"); ?>" />
	<?php echo form_close("\n"); ?>
	<div><?php echo $pagination; ?></div><!--pagination-->
</div><!--.in_users-->
