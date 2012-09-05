<?php 	
	define('BASEurl', 'http://beta.algorythme.net/');
	define("RELurl", './');
	
	/*Definitions variables par défaut*/
	define('DEFAULT_LG', 'fr');
	/**/
	require_once(RELurl.'inc/inc.conn.php');
	if(isset($_GET['logout'])) { session_destroy(); unset($_SESSION['uid']); unset($_SESSION['username']); }
	
	require_once(RELurl.'inc/inc.func.lang.php');
	require_once(RELurl.'inc/inc.login.php');

	
	//Sécurité de $_GET['p'] au cas où
	if(isset($_GET['p'])) { $_GET['p'] = basename($_GET['p']); }
	
	if(isset($_GET['p']) && file_exists('./inc/header.'.$_GET['p'].'.php'))
	{
		include(RELurl.'inc/header.'.$_GET['p'].'.php');
	}

?>
<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="utf-8">
    
    <base href="<?php echo BASEurl; ?>" />
    <title>Pierre de Rosette Numérique</title>
    
    <script type="text/javascript">
	var BASEurl = '<?php echo BASEurl; ?>';
    var gbook;
    var gauthor;
    var glang;
	var error = '<div class="ui-picture ui-icon-cancel" style="float:left;"></div>Le formulaire n\'est pas complet.';
	var varload = "<div style='text-align:center;' class='varload'><img src='./images/ajax-loader.gif' alt='Loading...' /></div>";
	var livarload = '<li class="varload">'+varload+'</li>';
    var lt = new Date();
    </script>

    <!-- Le HTML5 shim, for IE6-8 support of HTML elements -->
    <!--[if lt IE 9]>
<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->

    <!-- Le styles -->
    <link href="./css/bootstrap.css" rel="stylesheet">
    <link href="./css/bootstrap-responsive.css" rel="stylesheet">
    <link href="./css/chosen.css" rel="stylesheet">   
    <link rel="stylesheet" href='./css/smoothness/jquery-ui-1.8.16.custom.css' type="text/css" media="all" />
    <style type="text/css">
	html, body {
		min-height:100%;
	}
      .sidebar-nav {
        padding: 9px 0;
      }
	  .area-disabled {
		  border:none !important;
		  background:none !important;
		  cursor:auto !important;
		  width:100% !important;
		  display:none;
		  padding:0!important;
		  margin:0!important;
		  color:inherit!important;
	  }
	  .note {
		  border-left:4px solid #eee;
		  padding-left:5px;
		  margin-bottom:10px;
	  }
	  .note ul {
		  list-style-type:none;
	  }
	  .note p {
		  text-indent:10px;
		  text-align:justify;
	  }
	  .note>.note-all {
		  display:none;
	  }
	  .cache { display:none; }
	  .link { cursor:pointer; }
	  .epi-note, .epi-comment { color:#36C; cursor:pointer; }
	  .epi-note:hover,  .epi-comment:hover { color:#3E59B7; text-decoration:underline; }
	  .nav-children { display:none; }
	  .margin10 { margin-left:10px; }
	  li.nav-icon {  background-position:center center; background-repeat:no-repeat; height:40px; width:40px; position:relative; }
	  .nav-label { position:absolute; bottom:0; right:0; }
	  .people { background-image:url(./images/icon_users.png); }
	  .messages { background-image:url(./images/icon_post.png); }
	  #friendsearch { background-image:url(/images/icon/search.png); background-repeat:no-repeat; background-position:right; padding-left:5px; }
	  .kw-autoselect { background-image:url(/images/icon/search.png); background-repeat:no-repeat; background-position:right; padding-left:5px; }
	  .show-showed { font-size:smaller; color:grey; }
    </style>

    <!-- Le fav and touch icons 
    <link rel="shortcut icon" href="images/favicon.ico">
    <link rel="apple-touch-icon" href="images/apple-touch-icon.png">
    <link rel="apple-touch-icon" sizes="72x72" href="images/apple-touch-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="114x114" href="images/apple-touch-icon-114x114.png">-->
  </head>

  <body>

    <div class="navbar">
      <div class="navbar-inner">
        <div class="container-fluid">
          <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </a>
          <a class="brand" href="#">PRN</a>
          <div class="nav-collapse">
            <ul class="nav">
              <li class="active"><a href="#">Accueil</a></li>
              <li><a href="./beta.html">Bug / Proposition</a></li>
              <?php //'.$_SESSION['username'].'
			session2Strings('

              <li class="dropdown" id="menu-tools">
                  <a class="dropdown-toggle" data-toggle="dropdown" href="#menu1">
                  Outils
                  <b class="caret"></b>
                  </a>
                  <ul class="dropdown-menu">
                      <li><a class="open-dialog" data-target="author"><i class="icon-user"></i> Ajouter un auteur</a></li>
                      <li><a class="open-dialog" data-target="book"><i class="icon-book"></i> Ajouter un livre</a></li>
                      <li><a class="open-dialog" data-target="text"><i class="icon-file"></i> Ajouter un texte</a></li>
                      <li><a class="open-dialog" data-target="biblio"><i class="icon-bookmark"></i> Ajouter une bibliographie</a></li>
                      <li class="divider"></li>
                      <li><a class="open-dialog" data-target="privatedoc"><i class="icon-file"></i> Ajouter un document privé</a></li>
                  </ul>
              </li>', '
              <li><a href="./user/register.html">Inscription</a></li>');
			  ?>
            </ul>
            <?php
			$notifs = array();
			if(socialBolean())
			{
				//On ajoute le formulaire de recherche
				$notifs[] = '<li><form class="navbar-form"><input type="text" placeholder="Rechercher un ami..." id="friendsearch" /></form></li>';
				$requests = $connectBDD->prepare("SELECT u.name as username, u.id as userid, un.name as university, sd.msg as msg FROM social_demande sd, users u, university un WHERE sd.target= ? AND u.id=sd.user AND un.id=u.university");
				$requests->execute(array($_SESSION['uid']));
				$reqnumb = $requests->rowCount();
				if($reqnumb > 0)
				{
					$temp = '<li class="link nav-icon people dropdown" ><a class="dropdown-toggle">&nbsp;<span class="label label-important nav-label">&nbsp;'.$reqnumb.'&nbsp;</span></a>
					<ul class="dropdown-menu">';
					while($duser = $requests->fetch(PDO::FETCH_OBJ))
					{
						
						$temp .= '<li><a class="social-answer" userid="'.$duser->userid.'">'.$duser->username.'</a><input type="hidden" class="social-user-university" value="'.$duser->university.'" /></li>';
						if($duser->msg != NULL)
						{
							//echo '<p>&laquo; '.$duser->msg.' &raquo;</p>';
						}
						//echo '</li>';
					}
					$temp .= '</ul></li>';
					$notifs[] = $temp;
				}
				$notifs[] = '<li class="link nav-icon messages" >&nbsp;<span class="label label-important nav-label">&nbsp;0&nbsp;</span></li>';
			}
			?>
            <?php 
			session2Strings('
			<ul class="nav pull-right">'.implode('<li class="divider-vertical"/>', $notifs).'
			</ul>
			', '
            <form class="navbar-form pull-right" method="post" action="index.html">
            	<input class="span2" type="text" placeholder="Pseudonyme" name="login" />
                <input class="span2" type="password" placeholder="Mot de passe" name="pwd" />
                <input type="submit" value="Connexion" />
            </form>');
			?>
            <!--<p class="navbar-text pull-right">Logged in as <a href="#">username</a></p>-->
          </div><!--/.nav-collapse -->
        </div>
      </div>
    </div>

    <div class="container-fluid">
      	<?php
		//$arianne = array('active' => 'Accueil');
			if(isset($arianne))
			{
				echo '<ul class="breadcrumb">';
				
				if(isset($arianne['path']))
				{
					foreach($arianne['path'] as $key => $link)
					{
						echo '<li><a href="'.$link.'">'.$key.'</a> <span class="divider">/</span></li>';
					}
				}
				
				echo '<li class="active"><a href="#">'.$arianne['active'].'</a></li>';
				echo '</ul>';
			}
		?>
	</div>
    <div class="container-fluid">
      <div class="row-fluid">
        <div class="span2" id="left">
          <div class="well sidebar-nav">
        <?php 
		  	if(sessionBolean()) {
		?>
            <ul class="nav nav-list">
              <li class="nav-header"><i class="icon-user"></i> Utilisateur</li>
              <li><a href="/user/profile.html">Profil</a></li>
              <li><a href="./user/documents.html">Documents</a></li>
              <li><a href="logout.html">Déconnexion</a></li>
            </ul>
            
            <br />
          <?php
		 	}
			else
			{
				echo 'Bienvenue sur la Pierre de Rosette Numérique';
			}
		  ?>
            <ul class="nav nav-list">
            	<li class="nav-header"><i class="icon-book"></i> Navigateur</li>
               <li id="nav-lang">
                    <ul class="unstyled nav">
                    	<li class="nav-header">
                        	<span class="pull-right"><i class="icon-step-forward cache" id="nav-next-author"></i></span>
                            Langue
                         </li>
                        <?php
							if($cache->exists("block", "lang", 0))
							{
									$cache->read("block", "lang", 0);
							}
							else
							{
								//Exemple de query
								$src = langList();
								
								//Normalement on reçoit $_POST qu'on transforme en data		
								$results = array();
								$row_array = array();
								
								$m_q=$connectBDD->prepare("SELECT DISTINCT lage FROM author ORDER BY lage");
								$m_q->execute();
								if($m_q)
								{
									$temp = array();
									while($man = $m_q->fetch(PDO::FETCH_OBJ))
									{
										$temp[$src[$man->lage]] = $man->lage;
									}
									ksort($temp);//On trie
									foreach($temp as $key => $value)
									{
										$echo .= '<li class="nav-lang navigator" val="' .$value. '"><i class="icon-chevron-right"></i> ' .$key. '</li>';
									}
								}
								$cache->write($echo, "block", "lang", 0);
								echo $echo;
							}
						?>
                    </ul>
                </li>
                <li id="nav-author" class="cache">
                    <ul class="unstyled">
                    	<li class="nav-header">
                        	<span class="pull-right">
                            	<i class="icon-step-backward" id="nav-prev-lang"></i>
                                <i class="icon-step-forward cache" id="nav-next-book"></i>
                            </span>
                            Auteur
                        </li>
                    </ul>
                </li>
                <li id="nav-book" class="cache">
                    <ul class="unstyled">
                    	<li class="nav-header">
                        	<span class="pull-right">
                            	<i class="icon-step-backward" id="nav-prev-author"></i>
                                <i class="icon-step-forward cache" id="nav-next-textbook"></i>
                            </span>
                            Livre
                        </li>
                    </ul>
                </li>
                <li id="nav-textbook" class="cache">
                    <ul class="unstyled">
                    	<li class="nav-header">
                        	<span class="pull-right">
                            	<i class="icon-step-backward" id="nav-prev-book"></i>
                            </span>
                            Texte</li>
                    </ul>
                </li>
            </ul>
            
          </div><!--/.well -->
        </div><!--/span-->
        
        <?php
		if(isset($_GET['p']) && file_exists('./inc/minor/p.'.$_GET['p'].'.php'))
		{
		?>
        <div class="span6" id="major">
        <i class="icon-resize-horizontal pull-right" id="cache-minor"></i>
        <?php if(isset($_GET['p']) && file_exists('./inc/major/p.'.$_GET['p'].'.php')){	include(RELurl.'inc/major/p.'.$_GET['p'].'.php'); } else { include(RELurl.'inc/major/p.home.php'); }  ?>
        </div><!--/span-->
        <div class="span4" id="minor">
        <?php include(RELurl.'inc/minor/p.'.$_GET['p'].'.php'); ?>
        </div><!--/span-->
        <?php
		}
		else
		{
			echo '<div class="span10">';
			if(isset($_GET['p']) && file_exists('./inc/major/p.'.$_GET['p'].'.php')){	include(RELurl.'inc/major/p.'.$_GET['p'].'.php'); } else { include(RELurl.'inc/major/p.home.php'); } 
			echo '</div>';
		}
		?>
        
	</div><!--/row-->
      <hr>

      <footer>
        <p>&copy; CLERICE Thibault 2012</p>
      </footer>

    </div><!--/.fluid-container-->
    <div class="cache">
    	<?php 
			if(socialBolean()) { include(RELurl.'inc/social/index.php');  } 
			if(isset($_GET['p']) && file_exists('./inc/options/options.'.$_GET['p'].'.php')){	include(RELurl.'inc/options/options.'.$_GET['p'].'.php');}
			include_once(RELurl.'/inc/dialog.php');

		?>
    </div>
    <!-- Le javascript
================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script type="text/javascript" src="http://code.jquery.com/jquery-1.7.min.js"></script>
    <script type="text/javascript" src="http://rangyinputs.googlecode.com/files/textinputs_jquery.js"></script>
    
    <script type="text/javascript" src="./js/bootstrap.min.js"></script>
    <script type="text/javascript" src="./js/bs.dropdown.js"></script>
    
    <script type="text/javascript" src="./js/jquery.validate.js"></script>
    <script type="text/javascript" src="./js/jquery.chosen.js"></script>
    <script type="text/javascript" src="./js/jquery.ui.js"></script>
    <script type="text/javascript" src="./js/jquery.raty.js"></script>
    
    <script type="text/javascript" src="./js/local.index.js"></script>
    <script type="text/javascript" src="./js/local.navigator.js"></script>
    <script type="text/javascript" src="./js/local.parentchildren.js"></script>
    <script type="text/javascript" src="./js/local.dialog.js"></script>
    <script type="text/javascript" src="./js/local.social.js"></script>
	<script type="text/javascript">
		$(document).ready(function(){
			/*
			$('#trad-langs').chosen();
			$('#noteaction').click(function() { 
				var h = $('#poem').height();
				$('#poem').toggle(); 
				$('#poemarea').toggle();
				$('#poemarea').height(h);
			});
			$( "#dialog-text-note" ).dialog({
				autoOpen: false,
				height: 800,
				width: 500,
				modal: true
			});	
			$(".epi-note").live('click', function() {
				$( "#dialog-text-note" ).dialog("open");
			});
			*/
			$('#traductions-tools-show').live('click', function() {
				$('#traductions-tools').slideToggle();
			});
			$('#note-tools-show').live('click', function() {
				$('#note-tools').slideToggle();
			});
			$('#kw-tools-show').live('click', function() {
				$('#kw-tools').slideToggle();
			});
			$(".note-maximize").live('click', function() {
				$(this).hide();
				$(this).next(".note-all").show("slide", { direction: "up" });
			});
			
			$(".note-minimize").live('click', function() {
				
				$(this).parent().prev('.note-maximize').show();
				$(this).parent().hide("slide", { direction: "up" });
			});
			
			$('.minimize-next').live('click', function() {
				$("#"+$(this).attr('target')).slideToggle();
				var text = $(this).html();
				if(text == '<i class="icon-minus-sign"></i> Réduire')
				{
					$(this).html('<i class="icon-plus-sign"></i> Agrandir');
				}
				else
				{
					$(this).html('<i class="icon-minus-sign"></i> Réduire');
				}
			});
			
			$('.alternate-next').live('click', function() {
				var tget = $(this).attr('target');
				$("#"+tget).slideToggle();
				$("#"+tget+"-alternate").slideToggle();
				var text = $(this).html();
				if(text == '<i class="icon-minus-sign"></i> Modifier')
				{
					$(this).html('<i class="icon-plus-sign"></i> Retour');
				}
				else
				{
					$(this).html('<i class="icon-minus-sign"></i> Modifier');
				}
			});
			
			$(".note-keyword-filter").live("click", function() {
				var kw = $(this).attr('keyword');
				//$(this).toggleClass('label-important');
				$('.note-keyword-'+kw).toggle();
			});
			/*GENERAL*/
			//CacheMINOR
			$("#cache-minor").live('click', function() {
				
				var that = $(this);
				var major = $("#major");
				var minor = $("#minor");
				
				if(minor.css('display') == 'none')
				{
					major.attr('class', "span6");
					minor.show('slide', { direction: "right" });
				}
				else
				{
					minor.hide('slide', { direction: "right" }, 
						function() {
							major.attr('class', "span10");
						}
					);
				}
			});
			//DropDown
			$('.dropdown-toggle').dropdown();
			$('.show-hover').live('mouseover', function () {
				$(this).children('.show-showed').fadeToggle();
			});
			$('.show-hover').live('mouseout', function () {
				$(this).children('.show-showed').fadeToggle();
			});
		});
	</script>
    <?php if(isset($_GET['p']) && file_exists('./js/local/js.'.$_GET['p'].'.js'))	{ echo '<script type="text/javascript" src="./js/local/js.'.$_GET['p'].'.js"></script>'; } ?>
    <?php /* if(isset($_GET['p']) && file_exists('./js/social/js.'.$_GET['p'].'.php'))	{ echo '<script type="text/javascript" src="./js/social/js.'.$_GET['p'].'.php"></script>'; } */?>
  </body>
</html>