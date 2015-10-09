<h1><?php echo lang("homebanner_homebanner"); ?></h1>

<div class="list_item_cmdleft">
	<?php echo form_button("btn", lang("homebanner_add_btn"), "onclick=\"window.location='" . site_url($this->uri->segment(1)."/".$this->uri->segment(2)."/".$this->uri->segment(3)."/add") . "'\""); ?>
	<?php echo sprintf(lang("homebanner_total"), (isset($list_genre['total_banner']) ? $list_genre['total_banner'] : "0")); ?>
</div><!--.list_item_cmdleft-->
<div class="clear"></div>

<?php echo form_open(current_url()."/process_bulk"); ?>
	<?php echo (isset($form_status) ? $form_status : ""); ?>

	<table class="list_item">
		<thead>
			<tr>
				<th><input type="checkbox" name="id_all" value="" onclick="checkAll(this.form,'id[]',this.checked)" /></th>
				<th>ID</th>
				<th><?php echo lang("homebanner_image"); ?></th>
				<th><?php echo lang("homebanner_link"); ?></th>
			</tr>
		</thead>
		<tbody>
			<?php if ( isset($list_banner) && is_array($list_banner) ): ?>
			<?php foreach ( $list_banner as $key => $item ): ?>
				<?php if ( is_numeric($key) ): ?>
			<tr>
				<td><?php echo form_checkbox("id[]", $key); ?></td>
				<td><?php echo $key; ?></td>
				<td><a href="<?php echo base_url().$item['banner_img']; ?>"><img src="<?php echo base_url().$item['banner_img']; ?>" alt="banner" style="max-height: 50px;" /></a></td>
				<td><?php echo $item['banner_url']; ?></td>
			</tr>
				<?php endif; ?>
			<?php endforeach; ?>
			<?php else: ?>
			<tr>
				<td colspan="4"><?php echo lang("admin_nodata"); ?></td>
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