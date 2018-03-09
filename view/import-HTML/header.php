<header>
  <div class="container">
    <a href="dashboard">
      <img src="view/images/general/dasi_black.png" alt="Logo DASI" class="logo">
    </a>
    <div class="dasi">
      Licence Professionnelle<br/>
      Développement et Administration de Sites Internet
    </div>
    <div class="user">
      <?php echo $user['firstname']." ".$user['lastname']; ?>
      <a href="logout">
        <i class="fa fa-sign-out"></i>
      </a>
    </div>
  </div>
</header>
