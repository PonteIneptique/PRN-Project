<?php if(sessionBolean() == true) { ?>
    <div id="tt-new-manuscrit" title="Ajouter un manuscrit">
        <form class="form-horizontal" id="tt-new-manuscrit-form">
            <p class="help-block">Vous allez ajouter un manuscrit. Veillez à remplir tous les champs</p>
            <div id="tt-new-manuscrit-result" class="dialog-result"></div>
            
            <div class="control-group">
                <label for="tt-new-manuscrit-name">Nom</label>
                <div class="controls">
                    <input type="text" name="name" id="tt-new-manuscrit-name" class="required" />
                </div>
            </div>
            
            <div class="control-group">
                <label for="tt-new-manuscrit-location">Lieu</label>
                <div class="controls">
                    <input type="text" name="location" id="tt-new-manuscrit-location" value="" class="required" />
                </div>
            </div>
            
            <div class="form-actions">
                <input type="button" id="tt-new-manuscrit-submit" class="btn btn-primary" value="Enregistrer" />
            </div>
        </form>
	</div>
    
    <div id="tt-new-trad" title="Ajouter une traduction">
    	<div class="row">
        	<div class="span4 well" id="tt-new-trad-src">
			<?php echo nl2br($text->textsrc); ?>
            </div>
            <div class="span4">
                <form class="form-horizontal" id="tt-new-trad-form" >
                    <h2>Traduction</h2>
                    <p class="help-block">Vous allez ajouter une traduction. Veillez à remplir tous les champs</p>
                    
                    <div class="tt-error"></div>
                    <div id="tt-new-trad-result" class="dialog-result"></div>
                    
                    <div class="control-group">
                    	<label for="autorid" class="control-label">Auteur de la traduction</label>
                    	<div class="controls">
                    	<select name="autorid" id="autorid" class="span2">
                        	<option value="0">Autre</option>
                            <option value="1" selected="selected">Vous</option>
                        </select>
                        </div>
                    </div>
                        
                    <div id="tt-new-trad-autor" class="cache">
                        <div class="control-group">
                            <label for="tt-new-trad-autorname" class="control-label">Nom</label>
                            <div class="controls">
                                <input type="text" name="autorname" id="tt-new-trad-autorname" class="span2" />
                            </div>
                        </div>
                        <div class="control-group">
                        	<label for="tt-new-trad-year" class="control-label">Année de traduction</label>
                            <div class="controls">
                        		<input type="text" name="year" id="tt-new-trad-year" value="<?php echo date("Y"); ?>" class="span2" />
                            </div>
                        </div>
                    </div>
                    <div class="control-group">
                    	<label for="tt-new-trad-lage">Langue</label>
                        <div class="controls"><?php echo langSelect('name="lage" class="lage" id="tt-new-trad-lage"', $_SESSION['lage']); ?></div>
                    </div>
                    
                    <label for="tt-new-trad-trad">Traduction :</label>
                    
                    	<textarea id="tt-new-trad-trad" name="trad" class="trad" style="width:100%; min-height:300px;"></textarea>
                       
                    <div class="form-actions"><input type="button" id="tt-new-trad-submit" class="btn btn-primary" value="Enregistrer" /></div>
                </form>
            </div>
        </div>
	</div>
    
    <div id="dialog-new-trad-note" title="Ajouter une note à une traduction">
	<form method="post" action="" class="form-horizontal" id="dialog-new-trad-note-form">
    	<div id="dialog-new-trad-note-result" class="dialog-result"></div>
    	<p class="help-block">Vous allez ajouter une note à une traduction.</p>
        
        <input type="hidden" name="id" id="tt-trad-note-uid" value="" />
        <input type="hidden" name="start" id="tt-trad-note-start" value="" />
        <input type="hidden" name="end" id="tt-trad-note-end" value="" />
        <input type="hidden" name="tradid" id="tt-trad-note-tradid" value="" />
        
        <div id="dialog-new-trad-note-extract" class="comments-extract alert alert-info"></div>
        
        <div id="dialog-new-trad-note-reply"></div>
        
		<!--<label for="name">Sélection</label><input readonly="readonly" id="trad-note-sel-text" />-->
        <div class="control-group">
        <label for="dialog-new-trad-note-textarea">Note :</label>
        	<div class="controls">
        	<textarea style="width:90%; height:400px; margin:auto; display:block;"  class="required" name="note" id="dialog-new-trad-note-textarea" ></textarea>
            </div>
        </div>
        <div class="form-actions">
        <input type="button" id="dialog-new-trad-note-submit" class="btn btn-primary" value="Enregistrer" />
        </div>
	</form>
	</div>
    
<?php } ?>
    
    <div id="dialog-tradnotes" title="Notes de traduction">
	</div>