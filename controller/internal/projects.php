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
		<title>LP DASI - Projets</title>
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
					<h1>Projets</h1>
				</div>
			</div>
			<div class="row">
				<div class="col-xs-12">
					<a href="#" class="btn left" id="submitProject">Proposer un sujet</a>
				</div>
			</div>
			<div class="row">
				<?php
        if($user['value'] >= 10) { //Enseignant
            $projects = $bdd->query("SELECT * FROM projects INNER JOIN users ON projects.user = users.id_users ORDER BY module,is_validate")->fetchAll();
        }else{ //Etudiant
            $projects = $bdd->query("SELECT * FROM projects INNER JOIN users ON projects.user = users.id_users WHERE is_validate=1 OR (is_validate=0 AND user='".$_SESSION['id_user']."') ORDER BY module,name")->fetchAll();
        }
				$module = null;
				if(!$project): ?>
					<div class="col-lg-12">
						<div class="card-o">
							Il n'y a aucun projets, ajoutez-en un !
						</div>
					</div>
				<?php endif;
				foreach($projects as $project):
					if($module != $project['module']):
						$module = $project['module'];
						$get_module = $bdd->query("SELECT * FROM modules WHERE id_module='$module'")->fetch(); ?>
						<div class="col-xs-12">
							<h3><?php echo $get_module['title'].' - '.$get_module['subtitle']; ?></h3>
						</div>
					<?php endif; ?>
					<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
						<div class="card-o">
							<h4><?php echo $project['name']; ?></h4><br>
							<p><?php echo $project['description']; ?></p><br><br>
							Proposé par <?php echo $project["firstname"]." ".$project['lastname']; ?><br><br>
							<?php if($project['user'] == $_SESSION['id_user'] && $user['value'] < 10){
								echo ($project['is_validate']) ? "Le sujet a été validé." : "Le sujet est en cours de validation par un enseignant.";
                            }
                            if($project['is_validate'] == 0 && $user['value'] >= 10): ?>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <a href="#" idproject="<?php echo $project['id_project']; ?>" class="btn validateSubject">Valider</a>
                                    </div>
                                    <div class="col-lg-6">
                                        <a href="#" idproject="<?php echo $project['id_project']; ?>" class="btn red deleteSubject">Supprimer</a>
                                    </div>
                                </div>
                            <?php elseif($project['user'] == $_SESSION["id_user"] || $user['value'] >= 10): ?>
                                <a href="#" idproject="<?php echo $project['id_project']; ?>" class="btn red deleteSubject">Supprimer</a>
                            <?php endif; ?>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</section>
	<div class="modal" id="submit">
		<div class="modal_content container">
			<i class="fa fa-close"></i>
			<div class="row">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<h2 style="text-align:center;">Proposer un sujet</h2>
				</div>
			</div>
			<div class="row">
				<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
					<div class="field">
						<input type="text" id="subject" class="field-input" required>
						<label for="subject" class="field-label">Sujet</label>
					</div>
					<select id="module">
						<option disabled selected>Choisissez un module</option>
						<?php
						$modules = $bdd->query("SELECT * FROM modules")->fetchAll();
						foreach ($modules as $module): ?>
							<option value="<?php echo $module['id_module']; ?>"><?php echo $module['title']." - ".$module['subtitle']; ?></option>
						<?php endforeach; ?>
					</select>
				</div>
				<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
					<div class="field">
						<textarea id="description" rows="10" class="field-input"></textarea>
						<label for="description" class="field-label">Description</label>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<input type="submit" value="Proposer un sujet" id="modalSubmitProject" class="field-submit">
				</div>
			</div>
		</div>
	</div>
	<script src="framework/jquery/jquery-3.2.1.min.js"></script>
	<script type="text/javascript">
		$(document).ready(function(){
			$(".modal").click(function(e){
				e.preventDefault();
				e.stopPropagation();
				if($(e.target).attr('class') == "modal"){
					$(this).fadeOut("fast");
				};
			});
			$("#submitProject").click(function(e){
				e.preventDefault();
				$("#submit").css("display", "flex").hide().fadeIn();
			});
			$(".fa-close").click(function(e){
				e.preventDefault();
				$(this).parent().parent().fadeOut();
			});
			$(".field-input").focus(function(){
				$(this).parent().addClass("is-focused has-content");
			});
			$(".field-input").bind("blur", function(){
				$(this).parent().removeClass("is-focused");

				if($(this).val() == ""){
					$(this).parent().removeClass("has-content");
				}
			});
            $("label").click(function(){
                $("#"+$(this).attr("for")).focus();
            });
            $("#modalSubmitProject").click(function(e){
                e.preventDefault();
				submitProject();
            });
            $(".validateSubject").click(function(e){
				e.preventDefault();
				validateSubject(this);
            });
            $(".deleteSubject").click(function(e){
				e.preventDefault();
				deleteSubject(this);
            });
		});

		function submitProject() {
			var subject     = $("#subject").val();
			var module      = $("#module").val();
			var description = $("#description").val();
			$.ajax({ url: "model/request-DB/projects/insert_project.php",
					 type: "POST",
					 data: {
						 subject     :subject,
						 module      :module,
						 description :description
					 },
				success: function(){
                    location.reload();
                },
			});
		}

		function validateSubject(button) {
			var idProject = $(button).attr('idproject');
			console.log(idProject);
			$.ajax({ url: "model/request-DB/projects/validateProject.php",
					 type: "POST",
					 data: {
						 idProject :idProject
					 },
				success: function(){
                    location.reload();
                }
			});
		}

		function deleteSubject(button) {
			var idProject = $(button).attr('idproject');

			$.ajax({ url: "model/request-DB/projects/delete_project.php",
					 type: "POST",
					 data: {
						 idProject :idProject
					 },
				success: function(){
                    location.reload();
                }
			});
		}
	</script>
</body>
</html>
