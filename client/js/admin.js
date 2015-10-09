/* 
 * @author mr.v
 * @copyright http://okvee.net
 */

function checkAll(pForm, boxName, parent) {
	for (i = 0; i < pForm.elements.length; i++)
		if (pForm.elements[i].name == boxName)
			pForm.elements[i].checked = parent;
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