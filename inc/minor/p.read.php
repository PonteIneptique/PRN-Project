<form class="form-horizontal cache" id="new-note">
    <div class="well">
        <span class="pull-right close-form" target="new-note"><i class="icon-remove"></i> Fermer </span>
        <h3>Ajouter une note</h3>
        <a class="switch-note-var link" target="var"><i class="icon-edit"></i>Ajouter une variation</a>
        <p class="help-block">Vous allez ajouter une note.</p>
        <div class="form-result"></div>
        <!-- Les inputs cachés -->
        <input type="hidden" name="id" id="new-note-uid" value="" />
        <input type="hidden" name="start" id="new-note-start" value="" />
        <input type="hidden" name="end" id="new-note-end" value="" />
        <!-- /Input -->
        <div class="form-extract alert alert-info"></div>
        <div class="control-group">
            <label for="note-bibliographie"><i class="icon-book"></i> Bibliographie :</label>
            <div class="controls"><select id="note-bibliographie" multiple name="bibliographie[]" class="biblioselect unstyled"></select></div>
        </div>
        <div class="control-group">
        <label for="note-textarea">Ajouter une note :</label>
            <div class="controls">
            <textarea id="note-textarea" style="width:90%; height:120px; margin:auto; display:block;"  class="required" name="note"></textarea>
            </div>
        </div>
        <div class="form-actions">
        <input type="button" id="new-note-submit" class="btn btn-primary" value="Enregistrer" />
        </div>
    </div>
    <hr />
</form>
<form class="form-horizontal cache" id="new-var">
	<div class="well">
        <span class="pull-right close-form" target="new-var"><i class="icon-remove"></i> Fermer </span>
        <h3>Ajouter une variation</h3>
        <a class="switch-note-var link" target="note"><i class="icon-edit"></i>Ajouter une note</a>
        <p class="help-block">Vous allez ajouter une variation de manuscrit.</p>
        <div class="form-result"></div>
        <!-- Les inputs cachés -->
            <input type="hidden" name="id" id="new-var-uid" value="" />
            <input type="hidden" name="start" id="new-var-start" value="" />
            <input type="hidden" name="end" id="new-var-end" value="" />
        <!-- /Input -->
        <div class="form-extract alert alert-info"></div>
       
        <div class="control-group">     
            <label for="maninput">Manuscrit</label>
            <div class="controls"><select name="maninput" id="maninput"></select></div>
        </div>
        <div class="control-group">    
            <label for="variation-text">Note de variation :</label>
            <div class="controls">
            	<textarea id="variation-text" name="note" class="required"></textarea>
            </div>
        </div>      
            <div class="form-actions">
                <input type="button" id="new-var-submit" class="btn btn-primary" value="Enregistrer" />
            </div>
    </div>
    <hr />
</form>
<?php
#Page d'affichage minor de Read : traduction en gros
//On vérifie qu'on a un texte source
if($text->textsrc != '')//On vérifie qu'il n'y a qu'une réponse
{
	if(!isset($_SESSION['lage'])) { $lage = DEFAULT_LG; } else { $lage = $_SESSION['lage']; }
	
	if(sessionBolean())
	{
	}
	echo '
			<span class="pull-right">
					<span id="kw-tools-show" class="link" ><i class="icon-plus-sign"></i> Outils</span> 
					<span class="link minimize-next" target="kw-list"><i class="icon-minus-sign"></i> Réduire</span>
			</span>
			<h3>Mots Clefs</h3>
        	<div id="kw-tools" style="display:none;" class="well">
            	<h4>Ajouter un mot clef</h4>
				<form class="form-vertical auto-form" data-target="form.add.keyword">
					<div class="form-result"></div>
					<input type="hidden" name="text" value="'.$text->text.'" />
					<input name="keyword" class="kw-autoselect" place-holder="Cherchez un mot-clef..." />
					<div class="kw-append">
					<p class="help-block">Les mots-clefs suivis d\'un * sont vos propositions de nouveaux mots-clefs.</p>
					</div>
					<div class="form-actions">
						<input type="button" class="btn btn-primary" name="submit" value="Ajouter" />
					</div>
				</form>
            </div>
        	<div id="kw-list">
				';
			$kw_sql = $connectBDD->prepare("SELECT kw.name, kw.id FROM keyword_use ku, keyword_name kw WHERE ku.text= ? AND ku.`kw`=kw.id");
			$kw_sql->execute(array($text->text));
			if($kw_sql->rowCount() > 0)
			{
				while($kw = $kw_sql->fetch(PDO::FETCH_OBJ))
				{
					echo '<span class="label label-info" keyword="'.$kw->id.'">'.$kw->name.'</span> ';
				}
			}
			else
			{
				echo 'Aucuns Mots Clefs disponibles';
			}
			echo			
				'
			</div>
			<hr />
	';
	if($cache->exists("fullpage", "read", $text->text, array("traduction", $lage)))
	{
			$cache->read("fullpage", "read", $text->text, array("traduction", $lage));
	}
	else
	{
		$trad = $connectBDD->prepare("SELECT * FROM trad_text WHERE text=? AND archive=0 AND lage = ?");//Soit on met un ajax pour sélectionner d'autres langues aussi...
		$trad->execute(array($text->text, $lage));
		//On crée la var de print/cache $block
		$block = '
			<span class="pull-right">
					<span id="note-tools-show" class="link" ><i class="icon-plus-sign"></i> Avancé</span> 
					<span class="link minimize-next" target="note-list"><i class="icon-minus-sign"></i> Réduire</span>
			</span>
			<h3>Notes</h3>
        	<div id="note-tools" style="display:none;" class="well">
            	<h4>Outils</h4>
               	Filter par mots-clefs : <br />
				<div class="note-keyword-list"><span class="label label-info note-keyword-filter" keyword="1">Lorem</span> <span class="label label-info note-keyword-filter" keyword="2">Ipsum</span></div>
            </div>
        	<div id="note-list">
			</div>
			<hr />
			<span class="pull-right">
                <span id="traductions-tools-show" class="link" ><i class="icon-plus-sign"></i> Avancé</span> 
                <span class="link minimize-next" target="traduction-list"><i class="icon-minus-sign"></i> Réduire</span>
            </span>
			<h3>Traductions</h3>
			<div id="traductions-tools" style="display:none;" class="well">
            	<h4>Outils</h4>
                <select id="trad-langs" data-placeholder="Langue des traductions proposées">
                </select>
            </div>
        	<div id="traduction-list">
			';
		//$langs = "";
		if($trad->rowCount() > 0) // Si on a des traductions dispos
		{
			//On prépare les traductions en elle même
			while($tdat = $trad->fetch(PDO::FETCH_OBJ))
			{
				if($cache->exists("block", "read", $text->text, array("traduction", $tdat->id)))
				{
					$tradtext = $cache->text("block", "read", $text->text, array("traduction", $tdat->id));
				}
				else
				{
					//On récupère le texte annoté
					$tradtext = text_annote(array($tdat->id, "trad_part", "trad", "epi-comment", "trad_text", "trad"));
					$cache->write($tradtext, "block", "read", $text->text, array("traduction", $tdat->id));
				}
				
				if($tdat->authorid == 0 ) { $autor = $tdat->authorname; } else { $autor = "membre"; }
				$block .='
				<div class="traduction-div" id="traduction-'.$tdat->id.'">
					<div class="pull-right">
						<input type="button" class="btn btn-small traduction-action" value="Commenter" />
						<span class="tradnote" id="tradnote-'.$tdat->id.'" item="'.$tdat->id.'"></span>
					</div>
					<h4>'.$autor.' ('.$tdat->year.')</h4>
					<br clear="all" />
						<p class="traduction-text">'.$tradtext.'</p>
						<textarea val="'.$tdat->id.'" class="traduction-area area-disabled" readonly="readonly">'.$tdat->trad.'</textarea>
				</div>';
			}
		}
		else
		{
			$block .= "Aucune traduction pour votre langue utilisateur.";
		}
		$block .="</div> <hr />";
		
		echo $block;
		
		$cache->write($block, "fullpage", "read", $text->text, array("traduction", $lage));
	}
}
?>