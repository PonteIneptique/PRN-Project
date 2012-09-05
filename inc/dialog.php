	<div id="dialog-new-author" title="Ajouter un auteur" class="auto-dialog">
	<form class="form-horizontal auto-form" data-target="dialog.add.author" id="dialog-new-author-form">
    	<div id="dialog-new-author-result" class="dialog-result"></div>
        
        <p class="help-block">Vous allez ajouter un auteur. Veillez à remplir chaque champs.</p>
        
        <div class="control-group">
			<label for="auth-name">Nom, Prénom</label>
            <div class="controls">
				<input type="text" id="auth-name" name="name" class="required" />
            </div>
        </div>
        <div class="control-group">
			<label for="auth-lage">Langue</label>
            <div class="controls">
				<?php echo langSelect('name="lage" class="chzn-auto" id="auth-lage"'); ?>
            </div>
        </div>
        <div class="form-actions">
        	<input type="button" id="dialog-new-author-submit" class="btn btn-primary" value="Enregistrer" />
        </div>
	</form>
	</div>
    
    <div id="dialog-new-book" title="Ajouter un livre" class="auto-dialog">
	<form class="form-horizontal auto-form" id="dialog-new-book-form" data-target="dialog.add.book">
        <p class="help-block">Vous allez ajouter un livre. Veillez à remplir chaque champs.</p>
    	<div id="dialog-new-book-result" class="dialog-result"></div>
    	
        <div class="control-group">
			<label for="bk-name">Titre</label>
            <div class="controls">
            	<input type="text" name="name" id="bk-name" class="required" />
            </div>
        </div>
        <div class="control-group">
			<label for="bk-author">Auteur</label>
            <div class="controls">
        		<select name="author" class="chzn-autofeed" data-src="json.author.php" id="bk-author" data-target="author"></select>
            </div>
        </div>
        <div class="form-actions">
	        <input type="button" class="btn btn-primary" value="Enregistrer" />
       </div>
	</form>
	</div>
    
    <div id="dialog-new-biblio" title="Ajouter un élément de bibliographie" class="auto-dialog">
        <form class="form-horizontal auto-form" id="dialog-new-biblio-form" data-target="dialog.add.biblio">
        <div id="dialog-new-biblio-result" class="dialog-result"></div>
        <p class="block-help">Vous allez ajouter un élément de bibliographie. Veillez à remplir chaque champs.</p>
        
        	<div class="control-group">
            <label for="biblio-title">Titre</label>
                <div class="controls">
                    <input type="text" name="title" id="biblio-title" class="required" />
                </div>
            </div>
            
            <div class="control-group">
            <label for="biblio-url">Lien</label>
                <div class="controls">
                    <input type="text" name="url"  id="biblio-url" class="required" />
                </div>
            </div>
            
            
            <div class="control-group">
            <label for="biblio-auteur">Auteur</label>
                <div class="controls">
                    <input type="text" name="auteur" id="biblio-auteur" class="required" />
                </div>
            </div>
            
            
            <div class="form-actions">
            	<input type="button" id="dialog-new-biblio-submit" class="btn btn-primary" value="Enregistrer" />
            </div>
        </form>
	</div>
    
    <div id="dialog-new-text" title="Ajouter un texte" class="auto-dialog">
	<form class="form-horizontal auto-form" id="dialog-new-text-form" data-target="dialog.add.text">
        <div id="dialog-new-text-result" class="dialog-result"></div>
        <p class="help-block">Vous allez ajouter un texte. Veillez à remplir chaque champs.</p>
        
        <div class="control-group">
			<label for="txt-author">Auteur</label>
    	    <div class="controls">
            	<select name="author" class="chzn-autofeed chzn-autochange" data-src="json.author.php" id="txt-author" data-target="author" data-update="#txt-book" data-var="a"></select>
            </div>
        </div>
        <div class="control-group">
		<label for="txt-book">Livre</label>
            <div class="controls">
                <select name="book" class="chzn-autofeed" data-src="json.book.php" id="txt-book" data-target="book"></select>
            </div>
        </div>
        <div class="control-group">
	        <label for="txt-chapter">Chapitre, Partie</label>
            <div class="controls">
	            <input type="text" name="chapter" id="txt-chapter"  class="required" />
                <p class="help-block">En chiffre</p>
            </div>
        </div>
            
        <div class="control-group">
	        <label for="txt-sschapter">Sous-Partie</label>
            <div class="controls">
	            <input type="text" name="sschapter" id="txt-sschapter"/>
                <p class="help-block">En chiffre</p>
            </div>
        </div>    
        <div class="control-group">
        	<label for="txt-text">Texte</label>
            <div class="controls">
	            <textarea name="text" style="width:90%; height:400px; display:block;" id="txt-text" class="required"></textarea>
            </div>
        </div>
        <div class="form-actions">
	        <input type="button" id="dialog-new-text-submit" class="btn btn-primary" value="Enregistrer" />
        </div>
	</form>
	</div>

    <div id="dialog-new-privatedoc" title="Ajouter un document privé" class="auto-dialog">
	<form method="post" action="" class="form-horizontal auto-form" id="dialog-new-privatedoc-form" data-target="dialog.add.privatedoc">
        <div id="dialog-new-privatedoc-result" class="dialog-result"></div>
        <p class="help)-block">Vous allez ajouter un document privé. Veillez à remplir chaque champs.</p>
		
        <div class="control-group">
	        <label for="pdoc-title">Titre</label>
        	<div class="controls">
		        <input type="text" name="title" class="required" id="pdoc-title" value="" />
            </div>
        </div>
        
		<?php socialString('
		<div class="control-group">
			<label for="pdoc-shared">Partage</label>
			<div class="controls">
				<select multiple="multiple" name="shared[]" id="pdoc-shared" class="chzn-autofeed" data-src="json.social.people.php?myfriend=1"></select>
			</div>
        </div>'); ?>
        <div class="control-group">
        	<label for="pdoc-text">Texte</label>
            <div class="controls">
	            <textarea name="text" style="width:90%; height:400px; display:block;" id="pdoc-text" class="required"></textarea>
            </div>
        </div>
        <div class="form-actions">
        	<input type="button" id="dialog-new-privatedoc-submit" class="btn btn-primary" value="Enregistrer" />
        </div>
	</form>
	</div>