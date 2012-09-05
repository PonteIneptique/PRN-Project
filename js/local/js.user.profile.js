// JavaScript Document
$(document).ready(function(){
	//Les tabs
	$( "#profile-edit" ).tabs({ collapsible: true });

	//Envoi du formulaire Form
	//Règle de validation
	$("#password-form").validate({
		rules: {
			realpwd: {
				required: true,
				minlength: 5,
			},
			pwd: {
				required: true,
				minlength: 5,
			},
			pwd2: {
				required: true,
				minlength: 5,
				equalTo: "#pwd"
			}
		},
		// specifying a submitHandler prevents the default submit, good for the demo
		submitHandler: function() {
			$.post(BASEurl+'./ajax/user.update.password.php', $('#password-form').serialize(), function(data) { $('#password-results').html(data); });
		}
	});
	//Envoi infos
	$("#infos-form").validate({
		rules: {
			userlage: {
				required: true
			},
			registeruniversity: {
				required: true
			}
		},
		// specifying a submitHandler prevents the default submit, good for the demo
		submitHandler: function() {
			$.post(BASEurl+'./ajax/user.update.university.php', $('#infos-form').serialize(), function(data) { $('#infos-results').html(data); });
		}
	});
	
	//Activer le social
	$('.activate-social').live('click', function() {
		$('#minor').fadeOut();
		$.getJSON(BASEurl+"./json/json.socialactivate.php", null, function(data) {
			if(data.activate == 1) //Si on a activé
			{
				$('#minor').html("Rechargez la page. Le mode social est activé.").fadeIn();
			}
			else
			{
			}
		});
	});
});