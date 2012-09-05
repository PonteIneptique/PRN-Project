// JavaScript Document
$(document).ready(function(){
	$.fn.addItems = function(data) {
		return this.each(function() {
			var list = this;
			$.each(data, function(index, itemData) {
				var option = new Option(itemData.Text, itemData.Value);
				list.add(option);
			});
		});
	};
	$.autoreload = function(dialid) {
		//L'auto reload ICI
		$('#'+dialid+' .auto-reload').each(function() {
			var rld = $(this);
			var type = rld.attr('data-type');
			var target = rld.attr('data-target');
			if(type == "chzn")
			{
				var sid = target;
				$.getJSON(BASEurl+"./json/"+$(sid).attr('data-src'), null, function(data) { 
					$(sid).get(0).length = 0; 
					$(sid).addItems(data); 
					$(sid).trigger("liszt:updated"); 
				});
			}
			if(type == "div")
			{
				var sid = rld.attr('data-src');
				$.post(BASEurl+'./ajax/'+sid, null, function(data) { 
					$(target).html(data);
				}); 
			}
		});
	};
	//Fonctions de nettoyage dialog
	$.purgedialog = function(dialid, vclose)
	{
		if($('#'+dialid+' .auto-reload').length > 0)
		{
			$.autoreload(dialid);
		}
		$('#'+dialid+' input[type=text]').val("");
		$('#'+dialid+' input[type=hidden]').val("");
		if(dialid == 'dialog-new-trad-note') { $('#dialog-new-trad-note-reply').html(''); }
		$('#'+dialid+' textarea').val("");
		$('#'+dialid+' .comments-extract').html("");
		$('#'+dialid+' .dialog-result').html("");
		$('#'+dialid+' .tt-error').hide();
		$('#'+dialid+' .auto-reload').remove();
		if(vclose) { $('#'+dialid).dialog('close'); }
		return true;
	}
	$.closedialog = function(dialid)
	{
		if($('#'+dialid+' .alert-success').length >= 1) {
			//$(document).delay(3000).purgedialog(dialid, true);
			setTimeout(function() { $.purgedialog(dialid, true); }, 2000);
			return true;
		}
		else
		{
			return false;
		}
	}
	
	//Fonction de nettoyage formulaire	
	$.purgeform = function(dialid, vclose)
	{
		if($('#'+dialid+' .auto-reload').length > 0)
		{
			$.autoreload(dialid);
		}
		$('#'+dialid+' input[type=text]').val("");
		$('#'+dialid+' input[type=hidden]').val("");
		$('#'+dialid+' textarea').val("");
		$('#'+dialid+' .form-extract').html("");
		$('#'+dialid+' .form-result').html("");
		if(vclose) { $('#'+dialid).fadeOut(); }
		return true;
	}
	$.closeform = function(dialid)
	{
		if($('#'+dialid+' .alert-success').length >= 1) {
			//$(document).delay(3000).purgedialog(dialid, true);
			setTimeout(function() { $.purgeform(dialid, true); }, 2000);
			return true;
		}
		else
		{
			return false;
		}
	}
	
	//Défaut plugin validate
	$.validator.setDefaults({ 
		errorClass: "cache",
		highlight: function(element, errorClass) {
			$(element).parent().parent().addClass("error")
		},
		unhighlight: function(element, errorClass) {
			$(element).parent().parent().removeClass("error")
		}
	});
	
	$( ".kw-autoselect" ).autocomplete({
      source: BASEurl+"/json/json.keyword.php",
      minLength: 2,
      search: function() {
     	$(this).css('background-image', 'url('+BASEurl+'/images/ajax-loader.gif)')
        },
      open: function() {
      	$(this).css('background-image', 'url('+BASEurl+'/images/icon/search.png)')
      }
	  ,
	  close : function() {
		$(this).val("");
		},
      select: function(event, ui ) {
			if(ui.item.id == 0)
			{
				$(this).next(".kw-append").prepend(" <span class='label label-info kw-form link'>"+$(this).val()+" *<input type='hidden' name='kw[]' value='"+$(this).val()+"' /></span> ");
			}
			else
			{
				$(this).next(".kw-append").prepend(" <span class='label label-info kw-form link'>"+ui.item.value+"<input type='hidden' name='kw[]' value='"+ui.item.id+"' /></span> ");
			}
        }
    });
	$('.kw-form').live('click', function() {
		$(this).remove();
	});
	$('.kw-filter').live('click', function() {
		var target = $(this).attr('data-target');
		var kw = $(this).attr('data-src');
		var state = $(this).attr('data-active');
		if(state == '0') { $(this).attr('data-active', '1'); } else { $(this).attr('data-active', '0'); $('.'+target+'-kw-filtered-'+kw).hide(); }
		$(this).toggleClass('label-info').toggleClass('label-success');
		//Hide all !
		$('.'+target+'-kw-filtered').hide();
		//Show the list !
		$('.'+target+'-kw-list').show();
		
		//SET THE FILTER !
		var keywords = $('.'+target+'-kw-filter[data-active="1"]').map(function () {
			  return target+'-kw-filtered-'+$(this).attr('data-src');
		}).get().join(".");
		
		//Launch the show !
		$('.'+keywords).show();		
	});
	
	$('.kw-filter-raz').live('click', function() {
		var target = $(this).attr('data-target');
		$('.'+target+'-kw-filtered').hide();
		$('.'+target+'-kw-list').hide();
		$('.'+target+'-kw-filter').attr('data-active', 0).removeClass('label-success').addClass('label-info');
	});
});