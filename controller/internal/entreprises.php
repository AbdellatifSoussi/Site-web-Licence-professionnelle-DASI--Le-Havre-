<?php
	session_save_path('/home/users6/s/sml3759/www/lpromp2/sessions');
	session_start();
    include_once("../../model/ini_DB.php");

	if(!isset($_SESSION["id_user"]))
		header('location: login');

	$reqUser = $bdd->query("SELECT * FROM users INNER JOIN roles ON roles.id_role = users.role WHERE id_users = ".$_SESSION["id_user"]);
	$user = $reqUser->fetch();
    setlocale(LC_TIME, "fr_FR");
    if(isset($_POST['submit']) && !empty($_POST['submit'])){
        if(isset($_FILES['csv']['tmp_name']) && !empty($_FILES['csv']['tmp_name']) && isset($_POST['year']) && !empty($_POST['year']) && isset($_POST['type']) && !empty($_POST['type']) && $user['value'] > 10){
            $csvFile = file($_FILES['csv']['tmp_name']);
            if($_POST['type'] == "semicolon"){
                $type = ";";
            }else if($_POST['type'] == "comma"){
                $type = ",";
            }
            $data = [];
            foreach ($csvFile as $line) {
                $data[] = array_map("utf8_encode", str_getcsv($line, $type));
            }
            unset($data[0]);
            $year = $bdd->quote($_POST['year']);
            foreach($data as $line){
                $name = $bdd->quote($line[0]);
                $address = $bdd->quote($line[1]);
                $additional = $bdd->quote($line[2]);
                $postcode = $bdd->quote($line[3]);
                $city = $bdd->quote($line[4]);
                $civility = $bdd->quote($line[5]);
                $manager = $bdd->quote($line[6]);
                $telephone = $bdd->quote($line[7]);
                $email = $bdd->quote($line[8]);
                $bdd->query("INSERT INTO entreprises VALUES (null, $name, $address, $additional, $postcode, $city, $civility, $manager, $telephone, $email, $year)");
            }
        }        
    }
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
	<title>LP DASI - Entreprises</title>
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
                <div class="col-lg-12">
                    <h1>Entreprises ayant pris des stagiaires</h1>
                </div>
            </div>
            <?php
            if($user['value'] > 10): // ADMINISTRATEUR ?>
            <div class="row">
                <div class="col-lg-12">
                    <a class="btn left" href="#" id="addEntreprises">Ajouter des entreprises</a>
                </div>
            </div>
            <div class="row" id="add" style="display:none">
                <div class="col-lg-12">
                    <div class="card-o">
                        <h4>Ajouter des entreprises</h4><br>
                        <p>Format CSV avec séparation par virgule ou point virgule</p>
                        <p>Ordre des colonnes : Entreprises, Adresse 1, Adresse 2, Code Postal, Ville, Civilité, Responsable de stage, Téléphone, Email stage.</p>
                        <p>La première ligne du fichier n'est pas ajouté, vous devez donc laissez le titre des colonnes.</p>
                        <form enctype="multipart/form-data" action="" method="POST">
                            <div class="field has-content-ever">
                                <input type="file" id="csv" class="field-input" name="csv" required>
                                <label for="csv" class="field-label">Fichier CSV</label>
                            </div>
                            <div class="field">
                                <input type="text" id="year" class="field-input" name="year" required pattern="[0-9]{4}">
                                <label for="year" class="field-label">Année des stages</label>
                            </div>
                            Séparation du CSV par :
                            <input type="radio" value="comma" id="comma" name="type" required><label for="comma">Virgule</label>
                            <input type="radio" value="semicolon" id="semicolon" name="type" required><label for="semicolon">Point-virgule</label>
                            <input type="submit" value="Ajouter les entreprises" name="submit" class="field-submit">
                        </form>
                    </div>
                </div>
            </div>
            <?php endif; ?>
            <div class="row" style="margin: 20px;">
                <div class="col-lg-12">
                    <table class="table table-responsive table-striped">
                        <thead>
                            <tr>
                                <?php if($user['value'] > 10): ?>
                                    <th>Supprimer</th>
                                <?php endif; ?>
                                <th>Nom</th>
                                <th>Adresse</th>
                                <th>Adresse 2</th>
                                <th>Code Postal</th>
                                <th>Ville</th>
                                <th>Civilité</th>
                                <th>Responsable</th>
                                <th>Téléphone</th>
                                <th>Email</th>
                                <th>Année</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $entreprises_list = $bdd->query("SELECT * FROM entreprises")->fetchAll();
                            foreach($entreprises_list as $entreprise): ?>
                                <tr>
                                    <?php if($user['value'] > 10): ?>
                                        <td><a href="#" class="btn red deleteEntry" data-id="<?php echo $entreprise['id']; ?>" style="width: 39px;margin: 0;">X</a></td>
                                    <?php endif; ?>
                                    <td><?php echo $entreprise["name"]; ?></td>
                                    <td><?php echo $entreprise["address"]; ?></td>
                                    <td><?php echo $entreprise["additional"]; ?></td>
                                    <td><?php echo $entreprise["postcode"]; ?></td>
                                    <td><?php echo $entreprise["city"]; ?></td>
                                    <td><?php echo $entreprise["civility"]; ?></td>
                                    <td><?php echo $entreprise["manager"]; ?></td>
                                    <td><?php echo $entreprise["telephone"]; ?></td>
                                    <td><?php echo $entreprise["email"]; ?></td>
                                    <td><?php echo $entreprise["year"]; ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
	</section>
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
			$("#addEntreprises").click(function(e){
				e.preventDefault();
                $(this).toggleClass("active");
				$("#add").slideToggle();
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

            $(".deleteEntry").click(function(){
                var id = $(this).data("id");
                $.ajax({
                    type : "POST",
                    url: "model/request-DB/entreprises/delete_entreprises.php",
                    data : 'id_entreprise=' + id,
                    dataType: "json"
                });
                $(this).parent().parent().remove();
            });
    });
	</script>
</body>
</html>
