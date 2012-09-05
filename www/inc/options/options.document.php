<?php if(sessionBolean() == true) { ?>
	<div id="dialog-new-var" title="Ajouter une variation de manuscrit">
    <div id="dialog-new-var-result" class="dialog-result"></div>
	<form method="post" action="" class="myform" id="dialog-new-var-form">
    	<div id="transfert-note"><div style="float:left; margin-right:2px;" class="ui-picture ui-icon-transfer-e-w"></div>Basculer en mode manuscrit</div>
		<p class="dialog-new-var-legend">Vous allez ajouter une variation de manuscrit.</p>
        <input type="hidden" name="id" id="tt-var-uid" value="" />
        <input type="hidden" name="start" id="tt-var-start" value="" />
        <input type="hidden" name="end" id="tt-var-end" value="" />
        
    	<div class="dialog-new-var-error diverror"></div>
        <div id="dialog-new-var-extract" class="comments-extract"></div>
        
		<label for="maninput">Manuscrit</label><div class="select"><select name="maninput" id="maninput"></select></div>
        <label>Note de variation :</label> <textarea name="note" class="required"></textarea>
        
        <div class="spacer"></div>
        <input type="button" id="dialog-new-var-submit" class="button" value="Enregistrer" />
        <div class="spacer"></div>
	</form>
	</div>
    
    <div id="dialog-new-note" title="Ajouter une note">
    <div id="dialog-new-note-result" class="dialog-result"></div>
	<form method="post" action="" class="myform" id="dialog-new-note-form">
    	<div id="transfert-var"><div style="float:left; margin-right:2px;" class="ui-picture ui-icon-transfer-e-w"></div>Basculer en mode manuscrit</div>
		<p class="dialog-new-note-legend">Vous allez ajouter une note.</p>
        <input type="hidden" name="id" id="tt-note-uid" value="" />
        <input type="hidden" name="start" id="tt-note-start" value="" />
        <input type="hidden" name="end" id="tt-note-end" value="" />
        
    	<div class="dialog-new-note-error diverror"></div>
        <div id="dialog-new-note-extract" class="comments-extract"></div>
		<label>Bibliographie :</label><div class="select"><select multiple name="bibliographie[]" class="biblioselect"></select></div>
        <label>Ajouter une note :</label> <textarea style="width:90%; height:400px; margin:auto; display:block;"  class="required" name="note"></textarea>
        <div class="spacer"></div>
        <input type="button" id="dialog-new-note-submit" class="button" value="Enregistrer" />
        <div class="spacer"></div>
	</form>
	</div>
    
    <div id="tt-new-manuscrit" title="Ajouter un manuscrit">
	<form class="myform" id="tt-new-manuscrit-form">
	<p class="tt-legend">Vous allez ajouter un manuscrit. Veillez à remplir tous les champs</p>
    	<div class="tt-error"></div>
    	<div id="tt-new-manuscrit-result" class="dialog-result"></div>
		<label for="tt-new-manuscrit-name">Nom</label><input type="text" name="name" id="tt-new-manuscrit-name" class="required" />
		<label for="tt-new-manuscrit-location">Lieu</label><input type="text" name="location" id="tt-new-manuscrit-location" value="" class="required" />
        <div class="spacer"></div>
        <input type="button" id="tt-new-manuscrit-submit" class="button" value="Enregistrer" />
        <div class="spacer"></div>
	</form>
	</div>
    
    <div id="tt-new-trad" title="Ajouter une traduction">
    <div id="tt-new-trad-src"><?php echo nl2br($text->textsrc); ?></div>
	<form class="myform" id="tt-new-trad-form" >
	<p class="tt-legend">Vous allez ajouter une traduction. Veillez à remplir tous les champs</p>
    	<div class="tt-error"></div>
    	<div id="tt-new-trad-result" class="dialog-result"></div>
       	<label for="autorid">Auteur de la traduction : </label><div class="select"><select name="autorid" id="autorid"><option value="0">Autre</option><option value="1" selected="selected">Vous</option></select></div>
        <div id="tt-new-trad-autor">
            <label for="tt-new-trad-autorname">Nom</label><input type="text" name="autorname" id="tt-new-trad-autorname" />
            <label for="tt-new-trad-year">Année de traduction</label><input type="text" name="year" id="tt-new-trad-year" value="<?php echo date("Y"); ?>" />
        </div>
		<label for="tt-new-trad-lage">Langue</label><div class="select"><?php echo langSelect('name="lage" class="lage"', $_SESSION['lage']); ?></div>
        <label for="tt-new-trad-trad">Traduction :</label>
        <textarea id="tt-new-trad-trad" name="trad" class="trad"></textarea>
        <div class="spacer"></div>
        <input type="button" id="tt-new-trad-submit" class="button" value="Enregistrer" />
        <div class="spacer"></div>
	</form>
	</div>
    
    <div id="dialog-new-trad-note" title="Ajouter une note à une traduction">
    <div id="dialog-new-trad-note-result" class="dialog-result"></div>
	<form method="post" action="" class="myform" id="dialog-new-trad-note-form">
    	<p class="dialog-new-trad-note-legend">Vous allez ajouter une note à une traduction.</p>
        <input type="hidden" name="id" id="tt-trad-note-uid" value="" />
        <input type="hidden" name="start" id="tt-trad-note-start" value="" />
        <input type="hidden" name="end" id="tt-trad-note-end" value="" />
        <input type="hidden" name="tradid" id="tt-trad-note-tradid" value="" />
    	<div class="dialog-new-trad-note-error diverror"></div>
        
        <div id="dialog-new-trad-note-extract" class="comments-extract"></div>
        
        <div id="dialog-new-trad-note-reply"></div>
		<!--<label for="name">Sélection</label><input readonly="readonly" id="trad-note-sel-text" />-->
        <label>Ajouter une note :</label> <textarea style="width:90%; height:400px; margin:auto; display:block;"  class="required" name="note"></textarea>
        <div class="spacer"></div>
        <input type="button" id="dialog-new-trad-note-submit" class="button" value="Enregistrer" />
        <div class="spacer"></div>
	</form>
	</div>
    
<?php } ?>
    
    <div id="dialog-tradnotes" title="Notes de traduction">
	</div>
    <div id="dialog-text-note" title="Notes de texte">
	</div>