
<div class="in_users">
	<h1><?php echo lang("admin_users"); ?> : <?php if ( $this->uri->segment(3) == "add" ) {echo lang("admin_add");} else {echo lang("admin_edit");} ?></h1>
	
	<?php echo form_open($this->uri->segment(1)."/".$this->uri->segment(2)."/".$this->uri->segment(3).($this->uri->segment(3) == "edit" ? "?id=".$this->input->get("id", true) : "")); ?>
		<?php if ( isset($form_status) ) {echo $form_status;} ?>
		<table class="item_form" width="100%">
			<tr>
				<th><?php echo lang("admin_username"); ?></th>
				<td><input type="text" name="account_username" value="<?php if ( isset($account_username) ) {echo $account_username;} ?>" maxlength="255"<?php if ( $this->uri->segment(3) == "edit" ) {echo " disabled=\"disabled\"";} ?> /></td>
			</tr>
			<tr>
				<th><?php echo lang("admin_password"); ?></th>
				<td>
					<input type="password" name="account_password" value="<?php if ( isset($account_password) ) {echo $account_password;} ?>" maxlength="255" />
					<?php if ( $this->uri->segment(3) == "edit" ): ?><span class="txt_comment">(<?php echo lang("admin_enter_password_if_change"); ?>)</span><?php endif; ?>
				</td>
			</tr>
			<tr>
				<th><?php echo lang("admin_email"); ?></th>
				<td><input type="text" name="account_email" value="<?php if ( isset($account_email) ) {echo $account_email;} ?>" maxlength="255" /></td>
			</tr>
			<tr>
				<th><?php echo lang("admin_level"); ?></th>
				<td>
					<select name="account_level">
						<option value="0"<?php if ( isset($account_level) && $account_level === "0" ) {echo " selected=\"selected\"";} ?>><?php echo lang("admin_level0"); ?></option>
						<option value="1"<?php if ( isset($account_level) && $account_level === "1" ) {echo " selected=\"selected\"";} ?>><?php echo lang("admin_level1"); ?></option>
						<option value="2"<?php if ( isset($account_level) && $account_level === "2" ) {echo " selected=\"selected\"";} ?>><?php echo lang("admin_level2"); ?></option>
						<option value="3"<?php if ( isset($account_level) && $account_level === "3" ) {echo " selected=\"selected\"";} ?>><?php echo lang("admin_level3"); ?></option>
						<option value="4"<?php if ( isset($account_level) && $account_level === "4" ) {echo " selected=\"selected\"";} ?>><?php echo lang("admin_level4"); ?></option>
					</select>
				</td>
			</tr>
			<tr>
				<th><?php echo lang("admin_status"); ?></th>
				<td><label><input type="checkbox" name="account_status" value="1"<?php if ( isset($account_status) && $account_status === "1" ) {echo " checked=\"checked\"";} ?> /><?php echo lang("admin_enable"); ?></label></td>
			</tr>
			<tr>
				<th></th>
				<td><input type="submit" name="btn" value="<?php echo lang("admin_submit"); ?>" /></td>
			</tr>
		</table>
	<?php echo form_close("\n"); ?>
</div><!--.in_users-->
