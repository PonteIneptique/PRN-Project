// JavaScript Document
$(document).ready(function(){
	$('#reguserlage').chosen({no_results_text: "Pas de résultat"});
	$('#registeruniversity').chosen({no_results_text: "Pas de résultat"}).change(function()
	{
		if($(this).val() == 0)
		{
			$("#dialog-new-university").dialog("open"); 
		}
	});
	$.getJSON(BASEurl+"./json/json.university.php", null, function(data) { $("#registeruniversity").get(0).length = 0; $("#registeruniversity").addItems(data); $("#registeruniversity").trigger("liszt:updated"); });
	$( "#dialog-new-university" ).dialog({
			autoOpen: false,
				height: 250,
				width: 450,
				modal: true
	});
	$('#dialog-new-university-submit').click(function(){
		if( $("#dialog-new-university-form").valid()) {
			$.post(BASEurl+'./ajax/dialog.add.university.php', $('#dialog-new-university-form').serializeArray(), function(data) { 
				$('#dialog-new-university .form-result').html(data);
				if($.closedialog('dialog-new-university') == true)
				{
					$.getJSON(BASEurl+"./json/json.university.php", null, function(data) { 
						var reguni = $("#registeruniversity");
						reguni.get(0).length = 0; 
						reguni.addItems(data); 
						reguni.trigger("liszt:updated");
						$("#registeruniversity_chzn input").val($("#dialog-new-university-name").val());
					});
				}
			});  
		}
	});	
	$("#userregister").validate({
		rules: {
			name: {
				required: true,
				minlength: 5,
				remote: BASEurl+"./json/json.user.php?row=name"
			},
			login: {
				required: true,
				minlength: 5,
				remote: BASEurl+"./json/json.user.php?row=login"
			},
			email: {
				required: true,
				minlength: 2,
				email:true,
				remote: BASEurl+"./json/json.user.php?row=email"
			},
			pwd: {
				required: true,
				minlength: 5
			},
			pwd2: {
				required: true,
				minlength: 5,
				equalTo: "#regpwd"
			}
		},
		submitHandler: function(form) {
			$.post(BASEurl+'./ajax/user.register.php', $('#userregister').serialize(), 
			function(data) { 
				$('#userregister-results').html(data); 
			});
		}
	});
});