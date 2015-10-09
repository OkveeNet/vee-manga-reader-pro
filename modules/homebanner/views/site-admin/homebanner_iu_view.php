<h1><?php echo ($this->uri->segment(4) == "install" ? lang("homebanner_install") : lang("homebanner_uninstall")); ?></h1>

<?php echo form_open(); ?>
	<?php echo ( isset($form_status) ? $form_status : ""); ?>

	<?php if ( !isset($form_status) || (isset($form_status) && $form_status == null) ): ?>
	<p><?php echo lang("homebanner_are_you_sure"); ?></p>
	<input type="submit" name="btn" value="<?php echo lang("homebanner_yes"); ?>" />
	<input type="button" name="btn" value="<?php echo lang("homebanner_no"); ?>" onclick="window.location.href='<?php echo site_url("site-admin"); ?>';" />
	<?php endif; ?>
	
<?php echo form_close("\n"); ?>
