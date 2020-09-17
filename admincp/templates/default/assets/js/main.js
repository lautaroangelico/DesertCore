function confirmationMessage(actionPath, cmTitle="Are you sure?", cmText="", cmConfirmBtn="Confirm", cmCancelBtn="Cancel") {
	swal({
		title: cmTitle,
		text: cmText,
		type: 'question',
		showCancelButton: true,
		confirmButtonColor: '#3085d6',
		cancelButtonColor: '#d33',
		confirmButtonText: cmConfirmBtn,
		cancelButtonText: cmCancelBtn
	}).then(function (result) {
		if (result.value) {
			window.location.href = actionPath;
		}
	});
}

$(document).ready(function(){
	if($(".codemirror-textarea")[0]) {
		var code = $(".codemirror-textarea")[0];
		var mixedMode = {
		name: "htmlmixed",
		scriptTypes: [{matches: /\/x-handlebars-template|\/x-mustache/i,
					   mode: null},
					  {matches: /(text|application)\/(x-)?vb(a|script)/i,
					   mode: "vbscript"}]
		};
		var editor = CodeMirror.fromTextArea(code, {
			mode: "application/x-httpd-php",
			matchBrackets: true,
			indentUnit: 4,
			indentWithTabs: true,
			lineNumbers : true
		});
	}
});

$(document).ready(function(){
	if($('#news_content').length) {
		CKEDITOR.replace('news_content', {
			language: 'en',
			uiColor: '#f1f1f1'
		});
	}
});