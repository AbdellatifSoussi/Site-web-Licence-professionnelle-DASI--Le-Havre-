<?php
	session_save_path('/home/users6/s/sml3759/www/lpromp2/sessions');
	session_start();
	include_once("../../model/ini_DB.php");

	if(!isset($_SESSION["id_user"]))
		header('location: login');

	$reqUser = $bdd->query("SELECT * FROM users INNER JOIN roles ON roles.id_role = users.role WHERE id_users = ".$_SESSION["id_user"]);
	$user = $reqUser->fetch();
	setlocale(LC_TIME, "fr_FR");
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
	<title>LP DASI - Devoirs</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link type="text/css" rel="stylesheet" href="view/css/general/general.css">
    <link rel="stylesheet" href="framework/flexbox/flexboxgrid.min.css">
    <link rel="stylesheet" href="framework/fontAwesome/css/font-awesome.min.css">
    <link rel="icon" href="view/images/general/favicon.ico">
    <link href="https://fonts.googleapis.com/css?family=Abril+Fatface|Roboto:400,700" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="framework/dateTimePicker/DateTimePicker.min.css" />
</head>
<body>
  <?php include_once('../../view/import-HTML/header.php'); ?>
	<section>
		<div class="container">
			<div class="row">
				<div class="col-xs-12">
					<h1>Devoirs</h1>
				</div>
			</div>
            <?php
            if($user['value'] < 10){ // ETUDIANT ?>
                <div class="row">
                <?php $works = $bdd->query("SELECT * FROM works INNER JOIN users ON works.teacher = users.id_users INNER JOIN modules ON works.module = modules.id_module WHERE works.date > NOW() ORDER BY works.date")->fetchAll();
                if(count($works) == 0): ?>
                    <div class="col-xs-12">
                        <div class="card-o">
                            Il n'y a aucun devoirs actuellement.
                        </div>
                    </div>
                <?php endif;
                foreach($works as $work):
                    $date = new DateTime($work['date']);
                    ?>
                    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                        <div class="card-o">
                            <h4><?php echo $work['title']." - ".$work['name']; ?></h4>
                            Par <?php echo $work['firstname']." ".$work['lastname']; ?>
                            <a href="work-<?php echo $work['id_work']; ?>" class="btn"><?php echo utf8_encode(strftime('%A %d %B %Y', $date->getTimestamp())); ?> à <?php echo utf8_encode(strftime('%H:%M', $date->getTimestamp())); ?></a>
                        </div>
                    </div>
                <?php endforeach; ?>
                </div>
            <?php }else{ //ENSEIGNANT ?>
                <div class="row">
                    <div class="col-xs-12">
                        <a class="btn left" href="#" id="addWork">Ajouter un devoir</a>
                    </div>
                </div>
                <div class="row">
                <?php $works = $bdd->query("SELECT * FROM works INNER JOIN users ON works.teacher = users.id_users INNER JOIN modules ON works.module = modules.id_module WHERE works.teacher = ".$user['id_users']." ORDER BY works.date")->fetchAll();
                if(count($works) == 0): ?>
                    <div class="col-xs-12">
                        <div class="card-o">
                            Vous n'avez créé aucun devoir.
                        </div>
                    </div>
                <?php endif;
                foreach($works as $work):
                    $date = new DateTime($work['date']);
                    ?>
                    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                        <div class="card-o">
                            <h4><?php echo $work['title']." - ".$work['name']; ?></h4>
                            <?php
                                $now = new DateTime();
                            $date_respected = $now->diff($date);
                            if($date_respected->invert): ?>
                                <a href="work-<?php echo $work['id_work']; ?>" class="btn">Vous pouvez récupérer les fichiers.</a>
                            <?php else: ?>
                                <a href="work-<?php echo $work['id_work']; ?>" class="btn"><?php echo utf8_encode(strftime('%A %d %B %Y', $date->getTimestamp())); ?> à <?php echo utf8_encode(strftime('%H:%M', $date->getTimestamp())); ?></a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
                </div>
            <?php } ?>
		</div>
	</section>
    <div class="modal" id="add">
		<div class="modal_content container">
			<i class="fa fa-close"></i>
			<div class="row">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<h2 style="text-align:center;">Ajouter un devoir</h2>
				</div>
			</div>
            <form action="" method="POST" id="addAWork">
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
                        <div class="field">
                            <input type="text" id="date" class="field-input" required readonly data-field="datetime">
                            <label for="date" class="field-label">Date de rendu</label>
                        </div>
                        <div id="dtBox"></div>
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
                        <input type="submit" value="Ajouter le devoir" id="add-work" class="field-submit">
                    </div>
                </div>
            </form>
		</div>
	</div>
	<script src="framework/jquery/jquery-3.2.1.min.js"></script>
	<script type="text/javascript" src="framework/dateTimePicker/DateTimePicker.min.js"></script>
	<script type="text/javascript" src="framework/dateTimePicker/DateTimePicker-i18n-fr.js"></script>
	<script type="text/javascript">
		$(document).ready(function(){
			$("#dtBox").DateTimePicker({
				"language":"fr",
                "shortDayNames":["Dimanche","Lundi","Mardi","Mercredi","Jeudi","Vendredi","Samedi"]
			});
            $(".modal").click(function(e){
				e.preventDefault();
				e.stopPropagation();
				if($(e.target).attr('class') == "modal"){
					$(this).fadeOut("fast");
				};
			});
			$("#addWork").click(function(e){
				e.preventDefault();
				$("#add").css("display", "flex").hide().fadeIn();
			})
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
            $(".field-input").change(function(){
                if($(this).val() != ""){
					$(this).parent().addClass("has-content");
				}else{
                    $(this).parent().removeClass("has-content");
                }
            })

            $("label").click(function(){
                $("#"+$(this).attr("for")).focus();
            })

            $("#add-work").click(function(){
                var name        = $("#subject").val();
                var description = $("#description").val();
                var date        = $("#date").val();
                var id_module   = $("#module").val();

                $.ajax({
                    type : "POST",
                    url: "model/request-DB/works/insert_work.php",
                    data : {
                        name :name,
                        description :description,
                        date :date,
                        id_module :id_module
                    },
                    dataType: "json",
                    success: function(data) {
                        location.reload();
                    }
                });
            });

        });
	</script>
</body>
</html>
