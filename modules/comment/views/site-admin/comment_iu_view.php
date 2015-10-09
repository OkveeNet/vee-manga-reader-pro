<h1><?php echo ($this->uri->segment(4) == "install" ? lang("comment_install") : lang("comment_uninstall")); ?></h1>

<?php echo form_open(); ?>
	<?php echo ( isset($form_status) ? $form_status : ""); ?>

	<?php if ( !isset($form_status) || (isset($form_status) && $form_status == null) ): ?>
	<p><?php echo lang("comment_are_you_sute"); ?></p>
	<input type="submit" name="btn" value="<?php echo lang("comment_yes"); ?>" />
	<input type="button" name="btn" value="<?php echo lang("comment_no"); ?>" onclick="window.location.href='<?php echo site_url("site-admin"); ?>';" />
	<?php endif; ?>
	
<?php echo form_close("\n"); ?>

<p><?php echo lang("comment_code_instruction"); ?></p>
<pre><code><?php
$code = "// list comment
<?php
\$this->load->module(\"comment\");
if ( method_exists(\$this->comment, \"list_comment\") ) {
	echo \$this->comment->list_comment();
}
// post comment
\$this->load->module(\"comment\");
if ( method_exists(\$this->comment, \"comment_form\") ) {
	echo \$this->comment->comment_form();
}
?>";
echo htmlentities($code, ENT_QUOTES, "UTF-8");
unset($code);
?></code></pre>