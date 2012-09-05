// JavaScript Document
$(document).ready(function(){
	var extract, extract2;
	
	//General
	function checkempty(sel)//Supprime espace sélection
	{
		//Définition RegExp
		var reg = /^\s+/g;
		var reg2 = /\s+$/g;
		
		//Création donnée objet
		this.text = sel.text;
		this.start = sel.start;
		this.end = sel.end;
		
		//Nettoyage
		if(reg.test(this.text ) == true){ this.start = this.start+1; }
		if(reg2.test(this.text ) == true){ this.end = this.end -1; }
	}
	function getwholeextract(sel, src)//Sel = données de getSelection , src = texte source de la sélection
	{
		var before = "<b>";
		var after = "</b>";
		
		var subtostart = src.substring(0,sel.start);
		
		//Si on commence après 20
		var start;
		var substart;
		if(sel.start> 20) { start = sel.start - 20; substart = 20; } else { start = 0; substart = sel.start; }
		
		//Si on finit après 20
		var end = src.length;
		var diff;
		diff = end - sel.end;//Longueur entre end et la fin du tradtext
		if(diff > 20) { end = sel.end + 20; diff = 20; } if(diff < 0) { diff = 0; }
				
		//Maintenant on coupe
		cutsrc = src.substring(start, end);
		//Maintenant on positionne la fin
		subend = cutsrc.length - diff;
		
		//Maintenant on crée 3 sous chaine qu'on va coller ensemble
		return cutsrc.substring(0, substart) + before + cutsrc.substring(substart,subend) + after + cutsrc.substring(subend, cutsrc.length);
	}
	
	//MINOR
		//Traductions
		//Avancés
		$('#trad-langs').chosen().change(function() {
			//On met l'icone de chargement
			$('#traduction-list').html(varload);
			//On poste
			$.post(BASEurl+'./ajax/trad.php?text='+$('#textid').val()+'&lage='+$(this).val(), function(data) { $('#traduction-list').html(data); ; 
			$(document).ratyP('.tradnote', 1);});
		});
		//On charge au lancement de la page
		$.getJSON(BASEurl+"./json/json.trad.lang.php?text="+$('#textid').val(), null, function(data) { $("#trad-langs").get(0).length = 0; $("#trad-langs").addItems(data); $("#trad-langs").trigger("liszt:updated"); })
		//Commentaire de traduction
		//Afficher
		$('.traduction-action').live('click', function() {
			var tradid = $(this).parents('.traduction-div').attr('id');
			var h = $('#'+tradid+' .traduction-text').height() + 30;
			$('#'+tradid+' .traduction-text').toggle();
			$('#'+tradid+' .traduction-area').toggle().height(h);
		});
		//Action a la selection
		$('.traduction-area').live('mouseup', function(e) {
			var sel = $(this).getSelection();
			var trad = $(this).attr("val");
			if((sel) && (sel.start != sel.end)) {
				//On supprime les espaces
				var str = new checkempty(sel); 
				var extemp = '&#8220;...' + getwholeextract(str, $(this).val() )+ '...&#8221;';
				if(extract != extemp)
				{
					extract = extemp;
				}
				//On remplit les données notes : uid = id texte, restes = données de selection
				$('#dialog-new-trad-note-extract').html(extract);
				$('#tt-trad-note-uid').val($('#textid').val());
				$('#tt-trad-note-start').val(str.start);
				$('#tt-trad-note-end').val(str.end);
				$('#tt-trad-note-tradid').val(trad);	
				
				//On ouvre le dialog
				$('#dialog-new-trad-note').dialog("open");
			}
		});
	//Major	
	//Boutons Major
	$('#noteaction').click(function() { 
		var h = $('#poem').height() + 30;
		$('#poem').toggle(); 
		$('#poemarea').toggle();
		$('#poemarea').height(h);
		//Prévision multilingue
		var note = "Ajouter note(s)"; 
		var text = "Retourner au texte annoté";
		var that = $(this);
		if(that.button( "option", "label" ) == note) { that.button( "option", "label", text ); } else { that.button( "option", "label", note ); $('#tooltip').hide(); }
	});

	//Poem
	$('#poem .epi-note').live('click', function(e) {
		//On récupère l'id part (Appelé idnote...)
		var idnote = $(this).attr("val");
		//On met l'icone de charge
		$('#note-list').html(varload);
		//On appelle l'html
		$.post(BASEurl+'./ajax/tt.note.php?text='+$('#textid').val()+'&part='+idnote, function(data) { 
			//Chargement des notes
			$('#note-list').html(data); 
			//Mise en place du rate
			$(document).ratyP('.noterate', 2);
			//Mise en cache de l'extrait
			extract2 = $('#note-list .comments-extract').html(); 
		});
	});
	//Major-Formulaire
		//Tous les formulaires
		$(".close-form").live('click', function() {
			$.purgeform($(this).attr('target'), true);
		});
		//Formulaire Note
		
		//Switch de note vers var
		$('.switch-note-var').live('click', function() {
			//On récupère la cible et on en déduit la source
			var target = $(this).attr("target");
			if(target == "note") 
			{ 
				var src = "var"; 
			} 
			else 
			{ 
				var src = "note"; 
				//On set au cas où
				$('#maninput').chosen({no_results_text: "Pas de résultat"}).change(function()
				{
					if($(this).val() == 0)
					{
						$("#tt-new-manuscrit").dialog("open");
					}
				});
			}
			//On transfert les données
			$('#new-'+target+'-uid').val($('#new-'+src+'-uid').val());
			$('#new-'+target+'-start').val($('#new-'+src+'-start').val());
			$('#new-'+target+'-end').val($('#new-'+src+'-end').val());
			//Affichage de l'extrait
			$('#new-'+target+' .form-extract').html(extract2);
			//On met à jour les manuscrits
			if(target=='var') {$.getJSON(BASEurl+"./json/json.manuscrits.php?uid="+$('#new-'+target+'-uid').val(), null, function(data) { $("#maninput").get(0).length = 0; $("#maninput").addItems(data); $("#maninput").trigger("liszt:updated"); }) }
			//On switch
			$('#new-'+src).hide();
			$('#new-'+target).show();
		});
		//A la selection
		$('#poemarea').mouseup(function(e) {
			$('#new-note .biblioselect').chosen();
			var sel = $(this).getSelection();
			if((sel) && (sel.start != sel.end)) {
				//On supprime les espaces
				var str = new checkempty(sel); 
				//Ici on chope l'extrait
				var extemp = '&#8220;...' + getwholeextract(str, $(this).val()) + '...&#8221;';
				//On supprime les espaces
				if(extract2 != extemp)
				{
					extract2 = extemp;
				}	
				//On remplit les données notes : uid = id texte, restes = données de selection
				$('#new-note-uid').val($('#textid').val());
				$('#new-note-start').val(str.start);
				$('#new-note-end').val(str.end);	
				$('#new-note .form-extract').html(extract2); // On affiche l'extrait
				//Mise à jour Biblio
				$.getJSON(BASEurl+"./json/json.biblio.select.php", null, function(data) { $("#new-note .biblioselect").get(0).length = 0; $("#new-note .biblioselect").addItems(data); $("#new-note .biblioselect").trigger("liszt:updated"); });
				//On ouvre le dialog
				$('#new-note').slideDown();
			}
		});
		//Envoi ajout de notes
		$('#new-note-submit').live('click', function(){
			if( $("#new-note").valid()) {
				$.post(BASEurl+'./ajax/tt.submit.php', $('#new-note').serializeArray(), function(data) { 
					$('#new-note .form-result').html(data);
					$.closeform('new-note');
				});  
			}
		});
		
		//Formulaire Var
		//Envoi
		$('#new-var-submit').click(function(){
			if( $("#new-var").valid()) {
				$.post('./ajax/tt.submit.php', $('#new-var').serialize(), function(data) { 
					$('#new-var .form-result').html(data); 
					$.closeform('new-var');
				});  
			}
		});
	
//Dialogs		
	//Dialog Manuscrits
	$( "#tt-new-manuscrit" ).dialog({
		autoOpen: false,
		height: 300,
		width: 500,
		modal: true
	});
	$("#tt-new-manuscrit-submit").click(function() {
		if( $("#tt-new-manuscrit-form").valid()) {
			$("#tt-new-manuscrit .dialog-result").html(varload);
			//On poste
			$.post('./ajax/tt.add.manuscrit.php?uid='+$('#textid').val(), $('#tt-new-manuscrit-form').serialize(), function(data) { 
				$('#tt-new-manuscrit-result').html(data); 
				if($.closedialog('tt-new-manuscrit') == true)	
				{ 
					/* Action au cas où réussite */ 
					$("#maninput").val($("#tt-new-manuscrit-name").val());
					$.getJSON(BASEurl+"./json/json.manuscrits.php?uid="+$('#textid').val(), null, function(data) { $("#maninput").get(0).length = 0; $("#maninput").addItems(data); $("#maninput").trigger("liszt:updated"); });
				}
			}); 
		}
	});
	
	//Dialog Traduction
	$( "#tradaction" ).click(function() {
		$( "#tt-new-trad" ).dialog('open');
	});
	
	$( "#tt-new-trad" ).dialog({
			autoOpen: false,
				height: 800,
				width: 860,
				modal: true
	});
		
	$("#tt-new-trad #autorid").change(function() {
		if($(this).val() == 0) //Si auteur != me
		{
			$("#tt-new-trad-autor").show("slide", { direction: "down" });
		}
		else
		{
			$("#tt-new-trad-autor").hide("slide", { direction: "top" });
		}
	});
	
	
	$('#tt-new-trad .lage').chosen({no_results_text: "Pas de résultat"});
	
	$("#tt-new-trad-submit").click(function() {
		if( $("#tt-new-trad-form").valid()) {
			$("#tt-new-trad-result").html(varload);
			//On poste
			$.post('./ajax/tt.add.trad.php?uid='+$('#textid').val(), $('#tt-new-trad-form').serialize(), function(data) { $('#tt-new-trad-result').html(data); $.closedialog('tt-new-trad'); }); 
		}
	});
	
	//Dialog Commentaire de traduction
	//Déclaration
	$( "#dialog-new-trad-note" ).dialog({
			autoOpen: false,
				height: 800,
				width: 500,
				modal: true,
				beforeClose: function(event, ui) {
					$('#dialog-new-trad-note-reply').html('');
				}
	});
	//Submit
	$('#dialog-new-trad-note-submit').click(function(){
		if($("#dialog-new-trad-note-form").valid()) {
			$.post(BASEurl+'./ajax/dialog.add.trad.note.php', $('#dialog-new-trad-note-form').serializeArray(), function(data) { $('#dialog-new-trad-note .dialog-result').html(data); $.closedialog('dialog-new-trad-note'); });  
		}
	});
	
	//Notes de traductions
	//Déclaration dialogue
	$( "#dialog-tradnotes" ).dialog({
			autoOpen: false,
				height: 800,
				width: 500,
				modal: true
	});
	//Appel du dialogue
	$('.epi-comment').live('click', function() {
		var partid = $(this).attr("val");
		//On met l'icone de charge
		$('#dialog-tradnotes').html(varload);
		//On affiche le tooltip
		$('#dialog-tradnotes').dialog('open');
		//On appelle l'html
		$.post(BASEurl+'./ajax/dialog.trad.note.php?part='+partid, function(data) { $('#dialog-tradnotes').html(data); extract = $('#dialog-tradnotes .comments-extract').html(); });
	});
	//Réponse : clic
	//Action quand appel de réponse
	$(".answer").live("click", function() {
		var that = $(this);
		var id = that.attr("val"); // L'id du commentaire parent 
		var to = that.attr("to"); // Le nom de l'auteur du commentaire parent
		var start = that.attr("start"); // Le nom de l'auteur du commentaire parent
		var trad = that.attr("trad"); // Le nom de l'auteur du commentaire parent
		
		$('#tt-trad-note-tradid').val(trad); //Id de la trad ?
		$('#tt-trad-note-uid').val($('#textid').val());//On set le texte
		$('#tt-trad-note-start').val(start);//On a besoin que du start
				
		//On ouvre le dialog
		$('#dialog-new-trad-note-reply').html('<div class="control-group"><label for="dialog-new-trad-note-answer-input">Réponse à </label><div class="controls"><span class="btn btn-info button-reply" id="dialog-new-trad-note-answer-input"><i class="icon-remove"></i> '+to+'</span><input type="hidden" name="reply" value="'+id+'" /></div></div>');
		$('#dialog-new-trad-note-extract').html(extract);//On met l'extrait
		$('#dialog-new-trad-note').dialog("open");
		$('#dialog-tradnotes').dialog("close");
		
	});
	//Supprimer le mode réponse
	$( "#dialog-new-trad-note-reply .button-reply").live('click', function() {
		$('#dialog-new-trad-note-reply').html('');
	});
	//Notation
	//Fonction pour obtenir le vote
	$.fn.checkvote = function(sel, it, table, div)
	{
		$.getJSON(BASEurl+'./json/json.vote.php?t='+table+'&i='+it, function(json) {
			var note = parseInt(json.avg);
			sel.parents().attr("note", note);
			sel.raty('start', note).tri(div+'-global');
		});
	}
	//Fonction pour obtenir le vote
	$.fn.sendvote = function(sel, it, vote, table, div)
	{
		$.getJSON(BASEurl+'./json/json.vote.php?t='+table+'&i='+it, {rate: vote}, function(json) {
			var note = parseInt(json.avg);
			sel.parents().attr("note", note);
			sel.raty('start', note).tri(div+'-global');
		});
	}
	//Fonction pour trier ensuite
	$.fn.tri = function(div) {
		$(div).children().delay(3000).tsort({attr:'note', order:'desc'});
		//$('.tradnote-global>div').tsort({order:"desc",attr:"note"});
	}
	//Function générale pour raty
	$.fn.ratyP = function (div, table)
	{
		$(div).raty({
			start:3,
			click: function(score) {
				$.fn.sendvote($(this), $(this).attr('item'), score, table, div);
			}
		});
	
		$(div).each(function() {$(this).checkvote($(this), $(this).attr('item'), table, div)});
	}
	$(document).ratyP('.tradnote', 1);
	//Tri
	//$('.showtrad-div').delay(3000).tsort({attr:'note'}); -> Tri les div de traduction en fonction de leur attribut note
});