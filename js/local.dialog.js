$(document).ready(function(){
	$(".ui-dialog").live("dialogclose", function(event, ui) {
		var dialid = $(this).children(".ui-dialog-content").attr('id');
		if(($('#'+dialid+' .alert-success').length >= 1) || ($('#'+dialid+' input[type=text]').length >= 1))
		{
	  		$.purgedialog(dialid, false);
		}
	});
	//Auto Chzn
	$(".chzn-autofeed").each(function(index) {
		var sid = "#"+$(this).attr("id");
		$(sid).chosen({no_results_text: "Pas de résultat"}).change(function()
		{
			if($(this).val() == 0)
			{
				//Au cas où on est dans un dialog
				if($(this).parents('.ui-dialog-content').attr("id"))
				{
					var dialog = $("#"+$(this).parents('.ui-dialog-content').attr("id"));
					$(dialog).dialog("close");
				}
				var ndialog = "#dialog-new-" + $(sid).attr('data-target');
				$(ndialog).dialog("open");
				$(ndialog).append('<input type="hidden" class="auto-reload" data-target="'+sid+'" data-type="chzn" />');
			}
		});
		$.getJSON(BASEurl+"./json/"+$(this).attr('data-src'), null, function(data) { 
			$(sid).get(0).length = 0; 
			$(sid).addItems(data); 
			$(sid).trigger("liszt:updated"); 
		});
	});
	$(".chzn-auto").each(function(index) {
		$(this).chosen({no_results_text: "Pas de résultat"});
	});
	$('.open-dialog').live('click', function() {
		var target = "#dialog-new-" + $(this).attr('data-target');
		var dialog = $(target);
		dialog.dialog("open");
		
	});
	
	//Auto-Dialog
	$( ".auto-dialog" ).dialog({
			autoOpen: false,
				height: 800,
				width: 600,
				modal: true
	});
	$(".auto-form .form-actions .btn-primary").live('click', function() {
		var button = $(this);
		var form = button.parents('form');
		var dialogid = form.parents('.auto-dialog').attr('id');
		var formid = form.attr('id');
		var pagename = form.attr('data-target');
		if(form.valid())
		{
			$.post(BASEurl+'./ajax/'+pagename+'.php', form.serializeArray(), function(data) { 
				$('#'+formid+' .dialog-result').html(data);
				$.closedialog(dialogid);
			}); 
		}
	});
	$('.chzn-autochange').change(function() {
		
		//On récupère les variables
		var that = $(this);
		var val = that.val();
		var target = $(this).attr('data-update');
		var varname = $(this).attr('data-var');
		
		//On reload
		var src = $(target).attr('data-src');
		$(target).parent(".controls").append(varload);
		$.getJSON(BASEurl+"./json/"+src+"?"+varname+"="+val, null, function(data) { 
			$(target).get(0).length = 0; 
			$(target).addItems(data); 
			$(target).trigger("liszt:updated"); 
			$(target).parent(".controls").children('.varload').remove();
		})
		
	});
	//Biblio
	$('#biblio-title').autocomplete({
		minLength: 3,
		source : BASEurl+'./json/json.biblio.php?row=title',
	});
	$('#biblio-url').autocomplete({
		minLength: 3,
		source : BASEurl+'./json/json.biblio.php?row=url',
	});
	$('#biblio-auteur').autocomplete({
		minLength: 3,
		source : BASEurl+'./json/json.biblio.php?row=auteur',
	});
});