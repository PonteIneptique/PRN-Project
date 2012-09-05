$(document).ready(function(){
    $( "#friendsearch" ).autocomplete({
      source: BASEurl+"/json/json.social.people.php",
      minLength: 2,
      search: function() {
     	$(this).css('background-image', 'url('+BASEurl+'/images/ajax-loader.gif)')
        },
      open: function() {
      	$(this).css('background-image', 'url('+BASEurl+'/images/icon/search.png)')
      },
	  close: function() {
		$("#friendsearch").delay(2000).val("");
      	$(this).css('background-image', 'url('+BASEurl+'/images/icon/search.png)')
      }, 
      select: function(event, ui ) {
      	$("#dialog-friendrequest .dialog-username").html(ui.item.value);
      	$("#dialog-friendid").val(ui.item.id);
     	$("#dialog-friendrequest").dialog("open");
      }
    });
    $( "#dialog-friendrequest" ).dialog({
    	autoOpen: false,
        resizable: false,
        height:200,
        modal: true,
        buttons: {
            "Oui": function() {
            	//On va chercher les données jsons
                var p =$('#dialog-friendrequest p');
                var text = p.html();
 	            p.html(varload);
                $.getJSON(BASEurl+"./json/json.social.ask.php?target="+$("#dialog-friendid").val(), null, function(data) {
                    if(data.done == 1) //Si on a activé
                    {                    	
                        p.fadeOut(function () {
                          p.text("Demande envoyée").fadeIn(function() {
                              p.delay(6000).html(text);
                   			  $( "#dialog-friendrequest" ).delay(6000).dialog( "close" );
                              })
                          });
                    }
                    else
                    {
                    	p.fadeOut(function () {
                          p.text("Demande déjà existante").fadeIn(function() {
                              p.delay(6000).html(text);
                   			  $( "#dialog-friendrequest" ).delay(6000).dialog( "close" );
                              })
                       });
                    }
                });
            },
            "Non": function() {
                $( this ).dialog( "close" );
            }
        }
    });
	
	$(".social-answer").live('click', function() {
		$( "#dialog-friendaccept address" ).html("<strong>"+$(this).text()+"</strong><br />"+$(this).next('.social-user-university').val());
      	$("#dialog-friendid").val($(this).attr("userid"));
    	$( "#dialog-friendaccept" ).dialog("open");
    });
	
    $( "#dialog-friendaccept" ).dialog({
    	autoOpen: false,
        resizable: false,
        height:200,
        modal: true,
        buttons: {
            "Oui": function() {
            	//On va chercher les données jsons
                var p =$('#dialog-friendaccept p');
                var text = p.html();
 	            p.html(varload);
                $.getJSON(BASEurl+"./json/json.social.answer.php?type=1&target="+$("#dialog-friendid").val(), null, function(data) {
                    if(data.done == 1) //Si on a activé
                    {                    	
                        p.fadeOut(function () {
                          p.text("Demande acceptée").fadeIn(function() {
                              p.delay(6000).html(text);
                   			  $( "#dialog-friendaccept" ).delay(6000).dialog( "close" );
                              })
                          });
                    }
                    else
                    {
                    	p.fadeOut(function () {
                          p.text("Demande refusée ou non-existante").fadeIn(function() {
                              p.delay(6000).html(text);
                   			  $( "#dialog-friendaccept" ).delay(6000).dialog( "close" );
                              })
                       });
                    }
                });
            },
            "Non": function() {
                //On va chercher les données jsons
                var p =$('#dialog-friendaccept p');
                var text = p.html();
 	            p.html(varload);
                $.getJSON(BASEurl+"./json/json.social.answer.php?type=0&target="+$("#dialog-friendid").val(), null, function(data) {
                    if(data.done == 2) //Si on a activé
                    {                    	
                        p.fadeOut(function () {
                          p.text("Demande refusée").fadeIn(function() {
                              p.delay(6000).html(text);
                   			  $( "#dialog-friendaccept" ).delay(6000).dialog( "close" );
                              })
                          });
                    }
                    else
                    {
                    	p.fadeOut(function () {
                          p.text("Demande non-existante").fadeIn(function() {
                              p.delay(6000).html(text);
                   			  $( "#dialog-friendaccept" ).delay(6000).dialog( "close" );
                              })
                       });
                    }
                });
            }
        }
    });
});