// JavaScript Document
$(document).ready(function(){
		//Navigateur texte
	//Langue
	/*
	$('.navigator').live('click', function() {
		var that = $(this);
		$('#breadcrumbs .breadcrumb').append('<li><a class="breadcrumb-">'+that.text()+'</a> <span class="divider">/</span></li>');
	});
	*/
	function loadNavTextbook(gbook) {
		var chap;
		//On vide et on affiche le loader
		$("#nav-textbook ul li.nav-textbook").remove();
		$("#nav-textbook ul").append(livarload);
		$.getJSON(BASEurl+'./json/json.textbook.php?tb='+gbook, function(data) {
		  var items = [];
			$.each(data, function(index, itemData) {
				if(itemData.Value != 0)
				{
					if(itemData.chap == '1')
					{
						if(chap > 0)//Si on a déjà un chapitre, on ferme
						{
							items.push('</ul>');
						}
						chap = itemData.Value;
						items.push('<li class="nav-textbook nav-parent" hierarchie="left-'+ itemData.Value +'"><i class="icon-chevron-right"></i> ' + itemData.Text + '</li><ul class="unstyled">');
					}
					else
					{
						items.push('<li class="nav-textbook nav-children parent-left-'+chap+'" val="' + chap + '""><a href="' + itemData.Link +'"><i class="icon-chevron-right"></i> ' + itemData.Text + '</a></li>');
					}
				}
				else
				{
					items.push('<li class="nav-textbook" id="nav-newtextbook"><i class="icon-chevron-right"></i> ' + itemData.Text + '</li>');
				}
			});
			if(chap)//Si on a déjà un chapitre, on ferme
			{
				items.push('</ul>');
			}
		    $("#nav-textbook ul").append(items.join(''));
			$("#nav-textbook .varload").remove();
		});
	}
	function loadNavBook(gauthor) {
		//On vide et on affiche le loader
		$("#nav-book ul li.nav-book").remove();
		$("#nav-book ul").append(livarload);
		//On charge
		$.getJSON(BASEurl+'./json/json.book.php?a='+gauthor, function(data) {
		  var items = [];
			$.each(data, function(index, itemData) {
				items.push('<li class="nav-book navigator" val="' + itemData.Value + '"><i class="icon-chevron-right"></i> ' + itemData.Text + '</li>');
			});
		//On affiche
		  $("#nav-book ul").append(items.join(''));
		  $("#nav-book .varload").remove();
		});
	}
	function loadNavAuthor(glang) {
		//On vide et on affiche le loader
		$("#nav-author ul li.nav-author").remove();
		$("#nav-author ul").append(livarload);
		//On charge
		$.getJSON(BASEurl+'./json/json.author.php?l='+glang, function(data) {
		  var items = [];
			$.each(data, function(index, itemData) {
				items.push('<li class="nav-author navigator" val="' + itemData.Value + '"><i class="icon-chevron-right"></i> ' + itemData.Text + '</li>');
			});
		//On affiche
		  $("#nav-author ul").append(items.join(''));
			$("#nav-author .varload").remove();
		});
	}
	function loadNavLang() {
		//On vide et on affiche le loader
		$("#nav-lang ul li.nav-lang").remove();
		$("#nav-lang ul").append(livarload);
		$.getJSON(BASEurl+'./json/json.lang.php', function(data) {
		  var items = [];
		
			$.each(data, function(index, itemData) {
				items.push('<li class="nav-lang navigator" val="' + itemData.Value + '">' + itemData.Text + '</li>');
			});
		
		//On affiche
		  $("#nav-lang ul").append(items.join(''));
			$("#nav-lang .varload").remove();
		});
	}
	//loadNavLang();
	
	$(".nav-lang").live('click', function() {
		glang = $(this).attr("val");
		$("#nav-next-author").removeClass("cache");
		loadNavAuthor(glang);
		$("#nav-lang").hide("slide", function() { $("#nav-author").show("slide", {direction:'right'}); });
		
	});
	$("#nav-prev-lang").live('click', function() {
		loadNavLang();
		$("#nav-author").hide("slide", {direction:'right'}, function() { $("#nav-lang").show("slide"); });
	});
	$("#nav-next-author").live('click', function() {
		loadNavAuthor(glang);
		$("#nav-lang").hide("slide", function() { $("#nav-author").show("slide", {direction:'right'}); });
	});
	$(".nav-author").live('click', function() {
		gauthor = $(this).attr("val");
		if(gauthor == 0)//Si on a cliqué sur nouveau dans auteur
		{
			$("#dialog-new-author").dialog("open");
		}
		else
		{
			$("#nav-next-book").removeClass("cache");
			loadNavBook(gauthor);
			$("#nav-author").hide("slide", function() { $("#nav-book").show("slide", {direction:'right'}); });
		}
	});
	$("#nav-prev-author").live('click', function() {
		loadNavAuthor(glang);
		$("#nav-book").hide("slide", {direction:'right'}, function() { $("#nav-author").show("slide"); });
	});
	$("#nav-next-book").live('click', function() {
		loadNavBook(gauthor);
		$("#nav-author").hide("slide", function() { $("#nav-book").show("slide", {direction:'right'}); });
	});
	$(".nav-book").live('click', function() {
		gbook = $(this).attr("val");
		if(gbook == 0)//Si on a cliqué sur nouveau dans auteur
		{
			$.getJSON(BASEurl+"./json/json.author.php", null, function(data) { $("#dialog-new-author-bookselect").get(0).length = 0; $("#dialog-new-author-bookselect").addItems(data); $("#dialog-new-author-bookselect").trigger("liszt:updated"); });
			$("#dialog-new-book").dialog("open");
		}
		else
		{
			$("#nav-next-textbook").removeClass("cache");
			loadNavTextbook(gbook);
			$("#nav-book").hide("slide", function() { $("#nav-textbook").show("slide", {direction:'right'}); });
		}
	}); 
	$("#nav-prev-book").live('click', function() {
		loadNavBook(gauthor);
		$("#nav-textbook").hide("slide", {direction:'right'}, function() { $("#nav-book").show("slide"); });
	}); 
	$("#nav-next-textbook").live('click', function() {
		loadNavTextbook(gbook);
		$("#nav-book").hide("slide", function() { $("#nav-textbook").show("slide", {direction:'right'}); });
	}); 
	$("#nav-newtextbook").live('click', function() {
		$.getJSON(BASEurl+"./json/json.author.php", null, function(data) { $("#dialog-new-text-author").get(0).length = 0; $("#dialog-new-text-author").addItems(data); $("#dialog-new-text-author").trigger("liszt:updated"); });
		$.getJSON(BASEurl+"./json/json.book.php", null, function(data) { $("#dialog-new-text-book").get(0).length = 0; $("#dialog-new-text-book").addItems(data); $("#dialog-new-text-book").trigger("liszt:updated"); });
		$("#dialog-new-text").dialog("open"); 
	});
});