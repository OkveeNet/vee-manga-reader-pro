/* add media to content imgstring is html tag to media closewin is yes or nothing */
function addmedia(imgstring, closewin) {
	// implement file browser by tinymce please read. ->http://wiki.moxiecode.com/index.php/TinyMCE:Custom_filebrowser
	//current_content = tinyMCE.activeEditor.getContent();// get content from tiny mce.
	tinyMCE.activeEditor.execCommand('mceInsertContent', false, imgstring);// add content to cursor position in tinymce.
	if (closewin == 'yes') {
		var api = $("#overlay", window.parent.document).overlay({api: true}).close();
		var closer = api.getClosers();
		overlayapi.close();
		/*overlayapi.close();*/
	}
}

function change_redirect(obj) {
	window.location = $(obj).val();
}

function get_chapter_uri() {
	var urival = $("#chapter_uri").val();
	$.ajax({
		type: "GET",
		url: "./check_uri/",
		data: "uri="+urival,
		async: false,
		success: function(msg) {
			$("#chapter_uri").val(msg);
		}
	});// ajax
}

function checkAll(pForm, boxName, parent) {
	for (i = 0; i < pForm.elements.length; i++)
		if (pForm.elements[i].name == boxName)
			pForm.elements[i].checked = parent;
}

function manga_uri() {
	var urival = $("#story_uri").val();
	$.ajax({
		type: "GET",
		url: "./check_uri/",
		data: "uri="+urival,
		async: false,
		success: function(msg) {
			$("#story_uri").val(msg);
		}
	});// ajax
}

function pop_preview() {
	return false;
	var baseurl = $("#ar_bsurl").val();
	$('#article_form').attr("action", baseurl + 'article_preview');
	$('#article_form').attr("target", "article_preview");
	popup_reset();
	/*$('#article_form').submit(function() {
		window.open(baseurl + 'article_preview', 'formpopup', 'width=400,height=400,resizeable,scrollbars');
		this.target = 'formpopup';
		return false;
	});*/
	return false;
}

function show_resetpw() {
	$("#reset_password_form").toggle('slow');
}
