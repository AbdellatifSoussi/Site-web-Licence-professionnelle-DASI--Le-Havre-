<?php
if(!isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest')
  header('location: ../../../../../home');

if(isset($_POST['id_work']) && !empty($_POST['id_work'])){
  $id_work = $_POST['id_work'];
  session_save_path('/home/users6/s/sml3759/www/lpromp2/sessions');
  session_start();
  if(isset($_SESSION['id_user']) && !empty($_SESSION['id_user'])){
    include_once("../../../model/ini_DB.php");
    $user = $bdd->query("SELECT * FROM users WHERE id_users='".$_SESSION['id_user']."'")->fetch();
    if(file_exists("../../../works_files/".$id_work."/".$user['id_users']."_".$user['lastname']."_".$user['firstname'])){
        $files = scandir("../../../works_files/".$id_work."/".$user['id_users']."_".$user['lastname']."_".$user['firstname']);
        unset($files[0]);
        unset($files[1]);
        foreach($files as $file){
          ?>
          <br>
          <div style="display: inline-block; vertical-align:middle;" class="file-icon file-icon-lg" data-type="<?php echo pathinfo($file, PATHINFO_EXTENSION); ?>">
            <div class="delete" onclick="delete_file('<?php echo $file; ?>');">
            </div>
          </div>
          <p style="display: inline-block; vertical-align:middle;" ><?php echo $file; ?></p><br>
        <?php }
        if(count($files) == 0){
          echo "Aucun fichier n'a été envoyé";
        }else{}
    } else {
      echo "Aucun fichier n'a été envoyé";
    }
  }
}
?>
