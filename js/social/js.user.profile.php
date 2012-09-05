<?php
header("Content-type: text/javascript");
define("RELurl", '../../');
require_once(RELurl.'inc/inc.conn.php');
//Si on est bien en mode social
if(socialBolean())
{
?>
//Mon javascript

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
      select: function(event, ui ) {
      	$(".dialog-username").html(ui.item.value);
      	$("#dialog-friendid").val(ui.item.id);
     	$( "#dialog-friendrequest" ).dialog("open");
      /*
      	if (confirm("Demander à "+ui.item.value+" d'être votre ami ?")) { 
            alert(ui.item.id);
       }*/
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
});
<?php
}
?>