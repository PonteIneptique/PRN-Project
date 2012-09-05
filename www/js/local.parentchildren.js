// JavaScript Document
/*
Module de Hierarchie par Thibault Clérice

Pour le faire fonctionner :
- Un élément parent sur lequel on cliquera pour faire dérouler les enfants
	Cet élément parents a comme classe nav-parent et un attribut hierarchie
	Exemple : <li class="nav-parent" hierarchie="navigation-7">Parent Navigation ID 7</li>
- Des éléments enfants
	Ils ont la class nav-children et une classe parent-#LAVALEURHIERARCHIEDUPARENT#
	Exemple : <ul class="nav-children parent-navigation-7
	
Avantages : vous pouvez créer plusieurs système de hiérarchie automatisée en ne mettant donc pas qu'un numéro à hierarchie mais aussi un identifiant (Imaginons une navigation par hiérarchie en menu + en body : hierarchie="menu-1" et hierarchie="body-1" pour des classes enfants de type parent-menu-1 et parent-body-1
*/
$(document).ready(function(){
	$('.nav-parent').live('click', function() {
		var id = $(this).attr("hierarchie");
		$(".parent-"+id).slideToggle('slow');
	});
	$(".nav-children").hide();
});