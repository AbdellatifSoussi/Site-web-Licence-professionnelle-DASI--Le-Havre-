<?php
	session_save_path('/home/users6/s/sml3759/www/lpromp2/sessions');
	session_start();
	include_once("../../model/ini_DB.php");

	if(!isset($_SESSION["id_user"]))
		header('location: login');

	$reqUser = $bdd->query("SELECT * FROM users INNER JOIN roles ON users.role = roles.id_role WHERE id_users = ".$_SESSION["id_user"]);
	$user = $reqUser->fetch();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
		<title>LP DASI - Mon profil</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link type="text/css" rel="stylesheet" href="view/css/general/general.css">
    <link rel="stylesheet" href="framework/flexbox/flexboxgrid.min.css">
    <link rel="stylesheet" href="framework/fontAwesome/css/font-awesome.min.css">
    <link rel="icon" href="view/images/general/favicon.ico">
    <link href="https://fonts.googleapis.com/css?family=Abril+Fatface|Roboto:400,700" rel="stylesheet">
</head>
<body>
  <?php include_once('../../view/import-HTML/header.php'); ?>
	<section>
		<div class="container">
			<div class="row">
				<div class="col-xs-12">
					<h1>Mon profil</h1>
				</div>
			</div>
			<div class="row">
				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
					<div class="card-o">
						Nom : <?php echo $user['lastname']; ?><br/>
						Prénom : <?php echo $user['firstname']; ?><br/>
						Rôle : <?php echo $user['name']; ?>
					</div>
					<div class="card-o">
						<h2>Changement d'adresse email</h2>
						<div class="error mail">

						</div>
						<form action="#" method="post" id="emailchange">
							<div class="field has-content">
								<input type="email" id="email" class="field-input" required value="<?php echo $user['mail']; ?>">
								<label for="email" class="field-label">Adresse email</label>
							</div>
							<input type="submit" value="Modifier l'adresse email" class="field-submit">
						</form>
					</div>
				</div>
				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
					<div class="card-o">
						<h2>Changement de mot de passe</h2>
						<div class="error password">

						</div>
						<form action="#" method="post" id="passwordchange">
							<div class="field">
								<input type="password" id="oldpassword" class="field-input" required>
								<label for="oldpassword" class="field-label">Ancien mot de passe</label>
							</div>
							<div class="field">
								<input type="password" id="newpassword" class="field-input" required>
								<label for="newpassword" class="field-label">Nouveau mot de passe</label>
							</div>
							<div class="field">
								<input type="password" id="newpasswordagain" class="field-input" required>
								<label for="newpasswordagain" class="field-label">Encore une fois</label>
							</div>
							<input type="submit" value="Modifier le mot de passe" class="field-submit">
						</form>
					</div>
				</div>
			</div>
		</div>
	</section>
	<script src="framework/jquery/jquery-3.2.1.min.js"></script>
	<script type="text/javascript">
		$(document).ready(function(){
			$(".field-input").focus(function(){
				$(this).parent().addClass("is-focused has-content");
			});
			$(".field-input").bind("blur", function(){
				$(this).parent().removeClass("is-focused");

				if($(this).val() == ""){
					$(this).parent().removeClass("has-content");
				}
			});
			$("#emailchange").submit(function(e){
				e.preventDefault();
				update_mail();
			})
			$("#passwordchange").submit(function(e){
				e.preventDefault();
				update_password();
			})
		});


		function update_mail() {
			var mail = document.getElementById("email").value;

            $.ajax({
                type : "POST",
                url: "model/request-DB/users/update_user_mail_with_session.php",
                data : { 
                    mail :mail 
                },
                dataType: "json",
                success: function(data) {
                    if(data.codeError == 0) {
                        $(".error.mail").addClass("success").html("Votre adresse mail a été mise à jour.").slideDown().delay(2000).slideUp();
                    }
                    else {
                        $(".error.mail").removeClass("success").html(data.messageError[0]).slideDown();
                    }
                }
            });
		}

		function update_password() {
			var old_password    = document.getElementById("oldpassword").value;
			var new_password    = document.getElementById("newpassword").value;
			var re_new_password = document.getElementById("newpasswordagain").value;


            $.ajax({
                type : "POST",
                url: "model/request-DB/users/update_user_password_with_session.php",
                data : { 
                    old_password    :old_password,
					new_password    :new_password,
					re_new_password :re_new_password	
                },
                dataType: "json",
                success: function(data) {
                    if(data.codeError == 0) {
                        $(".error.password").addClass("success").html("Votre mot de passe a été mis à jour.").slideDown().delay(2000).slideUp();
                        $('input[type="password"]').val("").parent().removeClass("has-content");
                    }
                    else {
                        $(".error.password").removeClass("success").html(data.messageError[0]).slideDown();
                    }
                }
            });
		}
	</script>
</body>
</html>
