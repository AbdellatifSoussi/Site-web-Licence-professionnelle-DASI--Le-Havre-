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
    <meta charset="utf-8">
		<title>LP DASI - Groupes</title>
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
					<h1>Groupes</h1>
				</div>
			</div>
			<?php if($user['value'] < 10): // ETUDIANT ?>
				<div class="row">
					<div class="col-xs-12">
						<a href="#" class="btn left" id="createGroup">Créer un groupe</a>
						<a href="#" class="btn right" id="joinGroup">Rejoindre un groupe</a>
					</div>
				</div>
				<div class="row">
					<?php
					$my_groups = $bdd->query("SELECT * FROM students_project INNER JOIN projects ON students_project.project = projects.id_project WHERE student=".$_SESSION["id_user"])->fetchAll();
					if(!$mygroups): ?>
						<div class="col-lg-12">
							<div class="card-o">
								Il n'y a aucun groupe, créez-en un !
							</div>
						</div>
					<?php endif;
					foreach ($my_groups as $my_group):
						$project_id = $my_group['project'];
						$members = $bdd->query("SELECT * FROM students_project INNER JOIN users ON students_project.student = users.id_users WHERE project='$project_id' ORDER BY is_boss DESC, firstname")->fetchAll(); ?>
						<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
							<div class="card-o">
								<h4><?php echo $my_group['name']; ?></h4><br>
								<?php foreach ($members as $member):
									if($member['is_boss'] == 1): ?>
										<?php echo $member['firstname']." ".$member['lastname']; ?>
									<?php else: ?>
										<br><?php echo $member['firstname']." ".$member['lastname']; ?>
									<?php endif; ?>
								<?php endforeach; ?>
								<input type="submit" class="field-submit red leave-group" value="<?php if($members['0']['id_users']==$_SESSION['id_user']){ echo 'Supprimer le groupe'; }else{ echo 'Quitter le groupe'; } ?>" project="<?php echo $project_id; ?>">
							</div>
						</div>
					<?php endforeach; ?>
				</div>
			<?php else: // FIN ETUDIANT ?>
				<div class="row">
					<?php
					$groups = $bdd->query("SELECT * FROM students_project INNER JOIN projects ON students_project.project = projects.id_project WHERE is_boss=1")->fetchAll();
                    if(!$groups): ?>
						<div class="col-lg-12">
							<div class="card-o">
								Il n'y a aucun groupe !
							</div>
						</div>
					<?php endif;
                    foreach ($groups as $group):
						$project_id = $group['project'];
						$members = $bdd->query("SELECT * FROM students_project INNER JOIN users ON students_project.student = users.id_users WHERE project='$project_id' ORDER BY is_boss DESC, firstname")->fetchAll(); ?>
						<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
							<div class="card-o">
								<h4><?php echo $group['name']; ?></h4><br>
								<?php foreach ($members as $member):
									if($member['is_boss'] == 1): ?>
										<?php echo $member['firstname']." ".$member['lastname']; ?> (Chef de projet)
									<?php else: ?>
										<br><?php echo $member['firstname']." ".$member['lastname']; ?>
									<?php endif; ?>
								<?php endforeach; ?>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
		</div>
	</section>
	<div class="modal" id="join">
		<div class="modal_content container">
			<i class="fa fa-close"></i>
			<div class="row">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<h2 style="text-align:center;">Rejoindre un groupe</h2>
				</div>
			</div>
			<div class="row">
				<div class="col-lg-8 col-md-7 col-sm-12 col-xs-12">
					<select id="join-subject-choice">
						<option disabled selected>Choisissez un groupe</option>
						<?php
						$groups = $bdd->query("SELECT * FROM students_project INNER JOIN projects ON students_project.project = projects.id_project INNER JOIN users ON students_project.student = users.id_users WHERE is_boss=1")->fetchAll();
						foreach ($groups as $group):
							$in_group = $bdd->query("SELECT * FROM students_project WHERE student='".$_SESSION['id_user']."' AND project='".$group['project']."'")->fetch();
							if(!$in_group): ?>
								<option value="<?php echo $group['project']; ?>"><?php echo $group['name']." (".$group['firstname']." ".$group['lastname'].")"; ?></option>
							<?php endif; ?>
						<?php endforeach; ?>
					</select>
				</div>
				<div class="col-lg-4 col-md-5 col-sm-12 col-xs-12">
					<input type="submit" value="Rejoindre un groupe" id="join-group" class="field-submit">
				</div>
			</div>
		</div>
	</div>
	<div class="modal" id="create">
		<div class="modal_content container">
			<i class="fa fa-close"></i>
			<div class="row">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<h2 style="text-align:center;">Créer un groupe</h2>
				</div>
			</div>
			<form action="#" id="createForm" method="post">
			<div class="row">
					<div class="col-lg-8 col-md-7 col-sm-12 col-xs-12">
						<select id="add-subject-choice" class="" name="">
							<option disabled selected>Choisissez un sujet</option>
						<?php
						$projects = $bdd->query("SELECT * FROM projects WHERE is_validate=1 ORDER BY module,name")->fetchAll();
						$module = null;
						foreach($projects as $project):
							$groups_exist = $bdd->query("SELECT project FROM students_project WHERE is_boss=1 AND project='".$project['id_project']."'")->fetch();
							if(!$groups_exist){
								if($module != $project['module']){
									if($module != null){
										echo '</optgroup>';
									}
									$module = $project['module'];
									$get_module = $bdd->query("SELECT * FROM modules WHERE id_module='$module'")->fetch();
									echo '<optgroup label="'.$get_module['title'].' - '.$get_module['subtitle'].'">';
								}

								?>
								<option value="<?php echo $project['id_project']; ?>"><?php echo $project['name']; ?></option>
							<?php
							}
							 endforeach; ?>
						</optgroup>
						</select>
					</div>
					<div class="col-lg-4 col-md-5 col-sm-12 col-xs-12">
						<input type="submit" value="Créer un groupe" class="field-submit" id="add-group">
					</div>
				</div>
			</form>
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
			$("#createGroup").click(function(e){
				e.preventDefault();
				$("#create").css("display", "flex").hide().fadeIn();
			})
			$("#joinGroup").click(function(e){
				e.preventDefault();
				$("#join").css("display", "flex").hide().fadeIn();
			})
			$(".fa-close").click(function(e){
				e.preventDefault();
				$(this).parent().parent().fadeOut();
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
			$("#add-group").focus(function(){
				add_group();
			});
			$("#join-group").focus(function(){
				join_group();
			});
			$(".leave-group").focus(function(){
				leave_group($(this).attr("project"));
			});
		});


        function add_group() {
            var subject_choice = Number($("#add-subject-choice").find(":selected").val());

            if(Number.isInteger(subject_choice)) {
                $.ajax({
                    type : "POST",
                    url: "model/request-DB/students_project/create-group.php",
                    data : 'id_project=' + subject_choice,
                    dataType: "json",
                    success: function(data) {
                        if(data.error == 0);
                            location.reload();
                    }
                });
            }
        }

        function join_group() {
            var subject_choice = Number($("#join-subject-choice").find(":selected").val());

            if(Number.isInteger(subject_choice)) {
                $.ajax({
                    type : "POST",
                    url: "model/request-DB/students_project/join-group.php",
                    data : 'id_project=' + subject_choice,
                    dataType: "json",
                    success: function(data) {
                        if(data.error == 0);
                            location.reload();
                    }
                });
            }
        }

        function leave_group(subject) {
            var subject = Number(subject);

            if(Number.isInteger(subject)) {
                $.ajax({
                    type : "POST",
                    url: "model/request-DB/students_project/leave_group.php",
                    data : 'id_project=' + subject,
                    dataType: "json",
                    success: function(data) {
                        if(data.error == 0);
                            location.reload();
                    }
                });
            }
        }
	</script>
</body>
</html>
