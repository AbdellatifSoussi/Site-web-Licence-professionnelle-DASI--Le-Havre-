<?php
	session_save_path('/home/users6/s/sml3759/www/lpromp2/sessions');
	session_start();
	include_once("../../model/ini_DB.php");
	if(isset($_SESSION["id_user"]))
        header('location: dashboard');
?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>LP DASI - Connexion</title>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link href="https://fonts.googleapis.com/css?family=Roboto:400,700" rel="stylesheet">
		<link rel="stylesheet" href="view/css/external/page_login/login.css">
		<link rel="icon" href="view/images/general/favicon.ico">
	</head>
	<body>
		<form action="#" method="post">
            <a href="/">
                <img src="view/images/general/dasi_white.png" alt="Logo Dasi" class="logo">
            </a>
            <?php if(isset($_GET['logout'])): ?>
                <div class="error success" style="display: block;">
                    Vous êtes déconnectés !
                </div>
            <?php else: ?>
                <div class="error">

                </div>
            <?php endif; ?>
			<div class="field">
				<input type="email" id="email" class="field-input" required>
				<label for="email" class="field-label">Adresse email</label>
			</div>
			<div class="field">
				<input type="password" id="password" class="field-input" required>
				<label for="password" class="field-label">Mot de passe</label>
			</div>
			<input type="submit" value="Se connecter" class="field-submit">
			<hr>
			<input type="button" value="MODE ETUDIANT" class="field-submit student">
			<input type="button" value="MODE ENSEIGNANT" class="field-submit teacher">
			<input type="button" value="MODE ADMIN" class="field-submit admin">
		</form>

		<script src="framework/jquery/jquery-3.2.1.min.js"></script>
		<script src="framework/jquery-ui/jquery-ui.min.js"></script>

		<script type="text/javascript">
			$(document).ready(function(){
				// A SUPPRIMER ULTERIEUREMENT
				$(".student").click(function(e){
					e.preventDefault();
					$("#email").val("lpro3@dasi.fr");
					$("#password").val("lprodasi");
					$("form").submit();
				});
				$(".teacher").click(function(e){
					e.preventDefault();
					$("#email").val("lpro2@dasi.fr");
					$("#password").val("lprodasi");
					$("form").submit();
				});
				$(".admin").click(function(e){
					e.preventDefault();
					$("#email").val("lpro@dasi.fr");
					$("#password").val("lprodasi");
					$("form").submit();
				});
				//////////////////////////////////////////////




				$("form").submit(function(e){
					e.preventDefault();
					login();
				})
				$(".field-input").focus(function(){
					$(this).parent().addClass("is-focused has-content");
				});
				$(".field-input").bind("blur", function(){
					$(this).parent().removeClass("is-focused");

					if($(this).val() == ""){
						$(this).parent().removeClass("has-content");
					}
				});
			});


			function login() {
				var email    = document.getElementById("email").value;
				var password = document.getElementById("password").value;

				$.ajax({
					type : "POST",
					url: "model/request-DB/pages/external/page_login/verif_login.php",
					data : 'email=' + email + '&password=' + password,
					dataType: "json",
					success: function(data) {
						if(data.codeError == 0) {
							$(".error").slideUp();
							document.location.href="dashboard";
						}
						else {
							$(".field").effect("shake");
							$(".error").delay(300).html(data.messageError[0]).slideDown();
						}
					}
				});
			}
		</script>
	</body>
</html>
