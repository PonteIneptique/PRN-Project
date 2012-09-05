<?php
	if($profileaccess == true)
	{
		if($su->twitter != "")
		{
			if($usid == $_SESSION['uid'])
			{
				echo '<span class="link alternate-next" target="twtr-widget-1"><i class="icon-minus-sign"></i> Modifier</span>';
				$form = '
				<form class="form-horizontal cache auto-form" id="twtr-widget-1-alternate" data-target="user.twitter">
					<div id="infos-results" class="form-results"></div>
					<div class="control-group">
						<label for="twitter-name">Nom d\'utilisateur Twitter :</label>
						<div class="controls"><input type="text" name="name" id="twitter-name" value="'.$su->twitter.'" /></div>
					</div>
					<div class="form-actions"><input type="button" class="btn btn-primary" value="Modifier" /></div>
				</form>
				';
			}
			echo '<script charset="utf-8" src="http://widgets.twimg.com/j/2/widget.js"></script>
			<div id="twitter">
			
				<script>
				new TWTR.Widget({
				  version: 2,
				  type: \'profile\',
				  rpp: 5,
				  interval: 30000,
				  width: \'auto\',
				  height: \'auto\',
				  theme: {
					shell: {
					  background: \'#dddddd\',
					  color: \'#222222\'
					},
					tweets: {
					  background: \'#fbfbfb\',
					  color: \'#333333\',
					  links: \'#006dcc\'
					}
				  },
				  features: {
					scrollbar: false,
					loop: false,
					live: false,
					behavior: \'all\'
				  }
				}).render().setUser(\''.$su->twitter.'\').start();
				</script>
				'.$form.'
			</div>
			<hr />';
		}
	?>

	<span class="pull-right">
		<span class="link minimize-next" target="profile"><i class="icon-minus-sign"></i> Réduire</span>
	</span>
	<h3>Profil</h3>
	<div id="profile">
		<div class="row">
			<div class="span2">Université</div><div class="span8"><?php echo $us->uname; ?></div>
		</div>
		<div class="row">
			<div class="span2">Langue :</div><div class="span8"><?php echo langReturn($us->lage); ?></div>
		</div>
		<div class="row">
			<div class="span2">Courriel</div><div class="span8"><?php echo $us->email; ?></div>
		</div>
	</div>
	<hr />
	<?php
	if(socialBolean())
	{
		$friends = $connectBDD->prepare("SELECT u.name as username, un.name as university, u.id FROM users u, social_users su, university un WHERE u.id=su.user AND u.university=un.id AND su.status=1 AND (su.user IN (SELECT user FROM social_link sl WHERE sl.friend= ? ))"); 
		$friends->execute(array($usid));
		$friendcount = $friends->rowCount();
		if($friendcount > 0)
		{
			if($uid == $_SESSION['uid']) { $mes = "mes"; } else { $mes = ""; }
			echo '<span class="pull-right"><span class="link minimize-next" target="friends-list"><i class="icon-minus-sign"></i> Réduire</span></span><h3>'.$mes.' Amis ('.$friendcount.')</h3><div id="friends-list"><ul class="unstyled">';
			while($fr = $friends->fetch(PDO::FETCH_OBJ))
			{
				echo '<li><a class="show-hover" href="./user/'.$fr->id.'/'.urlencode($fr->username).'/"><i class="icon-user"></i> '.$fr->username.' <span class="cache show-showed">'.$fr->university.'</span></a></li>';
			}
			echo '</ul></div><hr />';
		}
	}
	if($usid == $_SESSION['uid'])
	{
	?>
	<span class="pull-right">
		<span class="link minimize-next" target="profile-edit"><i class="icon-plus-sign"></i> Agrandir</span>
	</span>
	<h3>Editer</h3>
	<div id="profile-edit" class="cache">
		<ul>
			<li><a href="#profile-password">Mot de passe</a></li>
			<li><a href="#profile-infos">Informations</a></li>
		</ul>
		<div id="profile-password">
			<form class="form-horizontal" id="password-form">
				<div id="password-results" class="form-results"></div>
				<div class="control-group">
					<label for="pfil-realpwd">Ancien mot de passe :</label>
					<div class="controls">
						<input id="pfil-realpwd" type="password" name="realpwd" />
					</div>
				</div>
				<div class="control-group">
				<label for="pfil-pwd">Mot de passe :</label>
					<div class="controls">
						<input id="pfil-pwd" type="password" name="pwd" />
					</div>
				</div>
				<div class="control-group">
				<label for="pfil-pwd2">Mot de passe :</label>
					<div class="controls">
						<input id="pfil-pwd2" type="password" name="pwd2" />
					</div>
				</div>
				<div class="form-actions"><button class="btn btn-primary">Modifier</button></div>
			</form>
		</div>
		<div id="profile-infos">
			<form class="form-horizontal" id="infos-form">
			<div id="infos-results" class="form-results"></div>
			<div class="control-group">
				<label for="pfil-userlage">Langue maternelle :</label>
				<div class="controls"><?php echo langSelect('name="lage" id="pfil-userlage" class="chzn-auto required" style="width:200px;"', $_SESSION['lage']); ?></div>
			</div>
			<div class="control-group">
			<label for="pfil-university">Université :</label>
				<div class="controls"><select id="pfil-university" name="university"  class="chzn-autofeed" data-src="json.university.php" data-target="university"></select></div>
			</div>
			<div class="form-actions"><button class="btn btn-primary">Modifier</button></div>
			</form>
		</div>
	</div>
	<hr />
	<?php
	}
	else
	{
		echo '<h3>Documents Partagés</h3>';
		$docs = $connectBDD->prepare("SELECT sds.doc, sdsrc.titre FROM social_doc_share sds, social_doc_src sdsrc WHERE user = ? AND sds.doc=sdsrc.id AND doc IN (SELECT doc FROM social_doc_share WHERE user = ? )");
		$docs->execute(array($_SESSION['uid'], $us->id));
		if($docs->rowCount() > 0)
		{
		echo '<ul class="unstyled">';
		while($ddta = $docs->fetch(PDO::FETCH_OBJ))
		{
			echo '<li><a href="/document/'.$ddta->doc.'/'.urlencode($ddta->titre).'.html"><i class="icon-file"></i> '.$ddta->titre.'</li>';
		}
		echo '</ul>';
		}
		else
		{
			echo 'Aucun document partagé avec cette personne.';
		}
	}
	if(!socialBolean())
	{
		?>
		<a class="activate-social">Activer le mode Social</a>
		<p>Le mode social vous permet d'interagir avec vos amis sur PRN : partager des documents, voir leurs dernières mises à jour, derniers status. En soi, il permet de travailler plus efficacement à plusieurs sur des textes, de mettre en relation les chercheu-r-se-s quelque soit leur origine et leur niveau. Bien sûr, vous devrez accepter les personnes pour qu'elles aient accès à vos propres informations (Derniers ajouts de notes, variation de manuscrits, status, documents partagés) et puissent vous contacter par la messagerie PRN.
		</p>
		<?php
	}
}
?>