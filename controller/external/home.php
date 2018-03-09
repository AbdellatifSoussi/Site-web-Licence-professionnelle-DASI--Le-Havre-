<?php
    include_once("../../model/ini_DB.php");
    session_save_path('/home/users6/s/sml3759/www/lpromp2/sessions');
    session_start();
    if(isset($_SESSION["id_user"])){
        $reqUser = $bdd->query("SELECT * FROM users WHERE id_users = ".$_SESSION["id_user"]);
        $user = $reqUser->fetch();
    }
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Licence Professionnelle Développement et Administration des Sites Internet - Le Havre</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="view/images/general/favicon.ico">
    <link href="https://fonts.googleapis.com/css?family=Abril+Fatface|Roboto:400,700" rel="stylesheet">
    <link rel="stylesheet" href="framework/flexbox/flexboxgrid.min.css">
    <link rel="stylesheet" href="framework/fontAwesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="view/css/external/page_home/home.css">
  </head>
  <body>
    <header>
      <div class="container">
        <nav>
          <a href="" class="home_link">
            <img src="view/images/general/dasi_white.png" alt="DASI logo" id="logo"/>
          </a>
          <a href="#" class="hamburger">
            <i class="fa fa-bars"></i>
          </a>
          <ul>
            <li><a href="#home" class="active">Accueil</a></li>
            <li><a href="#presentation">Présentation</a></li>
            <li><a href="#modules">Modules</a></li>
            <li><a href="#admission">Admission</a></li>
            <li><a href="#informations">Informations pratiques</a></li>
            <?php if(isset($user) && !empty($user)): ?>
                <li><a href="dashboard"><?php echo $user['firstname']." ".$user['lastname']; ?> <i class="fa fa-lg fa-sign-in"></i></a></li>
            <?php else: ?>
                <li><a href="login">Se connecter <i class="fa fa-lg fa-sign-in"></i></a></li>
            <?php endif; ?>
          </ul>
        </nav>
      </div>
    </header>
    <section id="home" opacity="0">
      <div class="blackout">
        <!-- TOTALBLACKOUT -->
      </div>
      <h1>Licence Professionnelle<br/>Développement et Administration des Sites Internet</h1>
    </section>
    <?php
    //Présentation
    include('../../view/import-HTML/presentation.php');
    ?>
    <section id="modules">
      <div class="container">
        <div class="row">
          <div class="col-lg-12 col-xs-12">
            <h2>Les modules</h2>
          </div>
        </div>
        <div class="row">
          <?php
          $modules = $bdd->query("SELECT * FROM modules ORDER BY title")->fetchAll();
          foreach($modules as $module): ?>
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
              <div class="card-o">
                <h3><?php echo $module['title']; ?></h3>
                <p><?php echo $module['subtitle']; ?></p>
                <button class="field-submit modules_modal" data-module="<?php echo $module['id_module']; ?>">En savoir plus !</button>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </section>
    
    <?php
    //Admission
    include('../../view/import-HTML/admission.php');
    ?>
    <?php
    //Informations pratiques
    include('../../view/import-HTML/infos.php');
    ?>
    <footer>
      <div class="container">
        &copy; 2018 | <a href="https://www.univ-lehavre.fr/">https://www.univ-lehavre.fr/</a>
      </div>
    </footer>
    <?php
    $modules = $bdd->query("SELECT * FROM modules")->fetchAll();
    foreach($modules as $module):
        $getInfos = $bdd->query("SELECT users.firstname, users.lastname, modules_teach.nb_hours, SUM(nb_hours) as total FROM modules_teach INNER JOIN users ON modules_teach.id_teach = users.id_users WHERE id_module='".$module['id_module']."'")->fetchAll(); ?>
        <div class="modal" id="module_<?php echo $module['id_module']; ?>">
            <div class="modal_content container">
                <i class="fa fa-close"></i>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <h2 style="text-align:center;"><?php echo $module['title']." - ".$module['subtitle']; ?></h2>
                        <h4>Coefficient : <?php echo $module['coefficient']; ?></h4>
                        <?php echo $module['description']; ?>
                        <?php if(!empty($getInfos)): ?>
                            <hr>
                            <h3 style="text-align:left; font-size: 20px; margin-bottom: 10px;">Enseigné par : </h3>
                            <?php foreach($getInfos as $info): ?>
                                <?php echo $info['firstname']." ".$info['lastname']." : ".$info['nb_hours']; ?> heures<br/>
                            <?php endforeach; ?>
                            <br>
                            Total d'heures : <?php echo $info['total']; ?> heures
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
    <script src="framework/jquery/jquery-3.2.1.min.js"></script>
    <script src="framework/jquery-easing/jquery.easing.js"></script>
    <script type="text/javascript">
    function nav() {
        $navbara = $('header nav ul li a[href*="#"]:not([href="#"])');
        var sections = [];
        $navbara.each(function () {
            sections.push($($(this).attr('href')));
        })
        var scroll = $(window).scrollTop();
        for (var i in sections) {
            var section = sections[i];
            if (scroll >= (section.offset().top - 56)) {
                scroll_id = "#" + section.attr("id");
            }
            $navbara.removeClass("active");
            $("header nav ul li a[href='" + scroll_id + "']").addClass("active");

            var height = $("section#home").height();
            $(".blackout").css("opacity", scroll / height);
        }
    }
    $(document).ready(function () {

        $(".modules_modal").click(function(){
            var module = $(this).data("module");
            $("#module_"+module).css("display", "flex").hide().fadeIn();
        });

        $(".fa-close").click(function(e){
            e.preventDefault();
            $(this).parent().parent().fadeOut();
        });

        $(".modal").click(function(e){
            e.preventDefault();
            e.stopPropagation();
            if($(e.target).attr('class') == "modal"){
                $(this).fadeOut("fast");
            };
        });

        nav();

        $('header nav ul li a[href*="#"]:not([href="#"])').click(function (e) {
            e.preventDefault();
            if (location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '') && location.hostname == this.hostname) {
                var target = $(this.hash);
                target = target.length ? target : $(this.hash);
                if (target.length) {
                    $('html, body').animate({
                        scrollTop: (target.offset().top - 55)
                    }, 1000, "easeInOutExpo");
                    return false;
                }
            }
        });

        $(".hamburger").click(function (e) {
            e.preventDefault();
            $("header nav ul").slideToggle();
        })
        $(window).scroll(function () {
            nav();
        });
    });
    </script>
  </body>
</html>
