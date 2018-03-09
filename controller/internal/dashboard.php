<?php
	session_save_path('/home/users6/s/sml3759/www/lpromp2/sessions');
	session_start();
	include_once("../../model/ini_DB.php");

	if(!isset($_SESSION["id_user"]))
		header('location: login');


	$reqUser = $bdd->query("SELECT * FROM users INNER JOIN roles ON roles.id_role = users.role WHERE id_users = ".$_SESSION["id_user"]);
	$user = $reqUser->fetch();
?>

<!DOCTYPE html>
<html>
<head>
    <title>LP DASI - Tableau de bord</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link type="text/css" rel="stylesheet" href="view/css/internal/page_dashboard/dashboard.css">
    <link rel="stylesheet" href="framework/flexbox/flexboxgrid.min.css">
    <link rel="stylesheet" href="framework/fontAwesome/css/font-awesome.min.css">
    <link rel="icon" href="view/images/general/favicon.ico">
    <link href="https://fonts.googleapis.com/css?family=Abril+Fatface|Roboto:400,700" rel="stylesheet">
</head>
<body>
	<input type="hidden" name="history" value="<?php echo end(explode("/", $_SERVER['HTTP_REFERER'])); ?>">
  <?php include_once('../../view/import-HTML/header.php'); ?>
  <section>
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <h1>Bienvenue <?php echo $user['firstname']." ".$user['lastname']; ?>,</h1>
            </div>
        </div>
      <div class="row">
        <div class="col-lg-2 col-md-3 col-sm-4 col-xs-4">
          <a href="profil" class="icon">
            <div class="icon_img">
              <i class="fa fa-user"></i>
            </div>
            <div class="icon_text">
              Mon profil
            </div>
          </a>
        </div>
        <div class="col-lg-2 col-md-3 col-sm-4 col-xs-4">
          <a href="groups" class="icon">
            <div class="icon_img">
              <i class="fa fa-users"></i>
            </div>
            <div class="icon_text">
              Groupes
            </div>
          </a>
        </div>
        <div class="col-lg-2 col-md-3 col-sm-4 col-xs-4">
          <a href="projects" class="icon">
            <div class="icon_img">
              <i class="fa fa-graduation-cap"></i>
            </div>
            <div class="icon_text">
              Projets
            </div>
          </a>
        </div>
        <div class="col-lg-2 col-md-3 col-sm-4 col-xs-4">
          <a href="works" class="icon">
            <div class="icon_img">
              <i class="fa fa-file"></i>
            </div>
            <div class="icon_text">
              Devoirs
            </div>
          </a>
        </div>
        <div class="col-lg-2 col-md-3 col-sm-4 col-xs-4">
          <a href="entreprises" class="icon">
            <div class="icon_img">
              <i class="fa fa-address-book-o"></i>
            </div>
            <div class="icon_text">
              Entreprises
            </div>
          </a>
        </div>
        <div class="col-lg-2 col-md-3 col-sm-4 col-xs-4">
          <a href="https://hplanning.univ-lehavre.fr/" target="_blank" class="icon">
            <div class="icon_img">
              <i class="fa fa-calendar-o"></i>
            </div>
            <div class="icon_text">
              Emploi du temps
            </div>
          </a>
        </div>
        <div class="col-lg-2 col-md-3 col-sm-4 col-xs-4">
          <a href="https://webmail.univ-lehavre.fr/SOGo/" target="_blank" class="icon">
            <div class="icon_img">
              <i class="fa fa-envelope"></i>
            </div>
            <div class="icon_text">
              Boîte mail
            </div>
          </a>
        </div>

        <?php if($user['value'] >= 100) { ?>
			<div class="col-lg-2 col-md-3 col-sm-4 col-xs-4">
			  <a href="users" class="icon">
				<div class="icon_img">
				  <i class="fa fa-user-plus"></i>
				</div>
				<div class="icon_text">
				  Gestion des utilisateurs
				</div>
			  </a>
			</div>

			<div class="col-lg-2 col-md-3 col-sm-4 col-xs-4">
			  <a href="content" class="icon">
				<div class="icon_img">
				  <i class="fa fa-code"></i>
				</div>
				<div class="icon_text">
				  Gestion du contenu
				</div>
			  </a>
			</div>

            <div class="col-lg-2 col-md-3 col-sm-4 col-xs-4">
			  <a href="reset" class="icon">
				<div class="icon_img">
				  <i class="fa fa-refresh"></i>
				</div>
				<div class="icon_text">
				  Remise à zéro
				</div>
			  </a>
			</div>
		<?php } ?>
        <!--END ROW-->
      </div>
    </div>
  </section>
	<script src="framework/jquery/jquery-3.2.1.min.js"></script>
	<script type="text/javascript">
		$(document).ready(function(){
			if($("input[name='history']").val() == "login" || $("input[name='history']").val() == "login?logout"){
				setTimeout(function(){
					$(".icon").each(function(index){
						$(this).delay(100*index).queue(function(next){
                            $(this).animate({  borderSpacing: 1 }, {
                                step: function(now,fx) {
                                $(this).css('-webkit-transform','scale('+now+')')
                                .css('-moz-transform','scale('+now+')')
                                .css('transform','scale('+now+')');
                                },
                                duration:300
                            }, 'swing');
					    next();
						});
					});
				}, 400);
				$("header").css("transform", "translateY(100%)");
                $("h1").css("opacity", '1');
			}else{
				$('.icon').css("transform", "scale(1)");
				$("header").css("transform", "translateY(100%)");
                $("h1").css("opacity", '1');
			}
		});
	</script>
</body>
</html>
