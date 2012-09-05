<form id="userregister" class="form-horizontal">
	<h1>Inscription</h1>
    <div id="userregister-results"></div>
    <p>Veuillez remplir entièrement ce formulaire.</p>
    <h2>Informations de connexion</h2>
    
    <div class="control-group">
	<label for="reglogin">Identifiant :</label>
    	<div class="controls">
    	<input id="reglogin" type="text" name="login" />
        <p class="help-block">Ne l'oubliez pas. Il sera utilisé pour se connecter</p>
     	</div>
     </div>
     
    <div class="control-group">
	<label for="regemail">Email :</label>
    	<div class="controls">
    	<input type="text" id="regemail" name="email" />
    	<p class="help-block">Il ne sera pas divulgué.</p>
        </div>
    </div>
    
    <div class="control-group">
		<label for="regpwd">Mot de passe :</label>
    	<div class="controls">
            <input id="regpwd" type="password" name="pwd" />
            <p class="help-block">Alphanumérique</p>
        </div>
    </div>
	
    <div class="control-group">	
        <label for="regpwd2">Mot de passe :</label>
    	<div class="controls">
            <input id="regpwd2" type="password" name="pwd2" />
            <p class="help-block">Veuillez retaper votre mot de passe</p>
        </div>
    </div>
   
   <h2>Informations utilisateur</h2>
   
    <div class="control-group">
	<label for="regname">Nom :</label>
    	<div class="controls">
            <input id="regname" type="text" name="name" />
            <p class="help-block">Votre nom d'affichage</p>
        </div>
    </div>
    
    <div class="control-group">
	<label for="reguserlage">Langue maternelle :</label>
    	<div class="controls">
			<?php echo langSelect('name="lage" id="reguserlage" class="lage required"'); ?>
        </div>
    </div>
    
    <div class="control-group">
	<label for="registeruniversity">Université :</label>
    	<div class="controls">
        <select id="registeruniversity" name="university"></select>
        </div>
    </div>

	<div class="form-actions">
		<input type="submit" class="btn btn-primary" value="Inscription" id="reg-submit" />
	</div>
</form>