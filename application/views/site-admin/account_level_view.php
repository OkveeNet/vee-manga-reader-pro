<h1><?php echo lang("account_level"); ?></h1>

<div class="list_item_cmdleft">
	<?php echo form_button("btn", lang("account_add_level"), "onclick=\"window.location='" . site_url($this->uri->segment(1)."/".$this->uri->segment(2)."/add") . "'\""); ?>
</div><!--.list_item_cmdleft-->
<div class="list_item_cmdright">
	<?php echo (isset($pagination) ? $pagination : ""); ?>
</div><!--.list_item_cmdright-->
<div class="clear"></div>

<?php echo form_open(current_url()."/process_bulk"); ?>
	<div class="form_result"><?php echo (isset($form_status) ? $form_status : ""); ?></div>
	
	<table class="list_item" id="sortable">
		<thead>
			<tr>
				<th><input type="checkbox" name="id_all" value="" onclick="checkAll(this.form,'id[]',this.checked)" /></th>
				<th><?php echo lang("account_level_priority"); ?></th>
				<th><?php echo lang("account_level"); ?></th>
				<th><?php echo lang("account_level_description"); ?></th>
				<th></th>
			</tr>
		</thead>
		<tbody>
			<?php if ( isset($list_level_group) && is_array($list_level_group) ): ?>
			<?php foreach ( $list_level_group as $key => $item ): ?>
			<tr class="state-default<?php if ( $key == '1' || $key == '2' || $key == '3' ): ?> ui-state-disabled<?php endif; ?>" id="listItem_<?php echo $key; ?>">
				<td><input type="checkbox" name="id[]" value="<?php echo $key; ?>"<?php if ( $key == '1' || $key == '2' || $key == '3' ): ?> disabled="disabled"<?php endif; ?> /></td>
				<td class="cursor_drag_ns"><?php echo $item['level_priority']; ?></td>
				<td><?php echo $item['level_name']; ?></td>
				<td><?php echo $item['level_description']; ?></td>
				<td><?php echo anchor(current_url()."/edit?id=$key", lang("admin_edit")); ?></td>
			</tr>
			<?php endforeach; ?>
			<?php else: ?>
			<tr>
				<td colspan="5"><?php echo lang("admin_nodata"); ?></td>
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

<script type="text/javascript">
	$(document).ready(function() {
		// Return a helper with preserved width of cells
		var fixHelper = function(e, ui) {
		    ui.children().each(function() {
			  $(this).width($(this).width());
		    });
		    return ui;
		};
		
		$("#sortable tbody").sortable({
			helper: fixHelper,
			start: function(event, ui) {ui.placeholder.html("<td colspan='5'>&nbsp;</td>")},
			placeholder: "ui-state-highlight",
			items: "tr:not(.ui-state-disabled)",
			update : function () {
				var order = $('#sortable tbody').sortable('serialize');
				$(".form_result").load("<?php echo site_url($this->uri->segment(1)."/".$this->uri->segment(2)); ?>/ajax_sort?return=true&"+order);
				setTimeout("clearinfo();", 3000);
			}
		});
		$("#sortable tbody").disableSelection();
	});// jquery document.ready
	function clearinfo() {
		$(".form_result").html('');
		location.reload();
	}
</script>