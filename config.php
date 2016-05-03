<!DOCTYPE html>
<html>
  <head>
    <script language="JavaScript">
      }
    </script>
    <?php
      require_once("libphp-phpmailer/PHPMailerAutoload.php");
      include "cb_syst.php";
      include "cb_print.php";
      $pdo=opendb();
      session_start();
      $users=get_users_table($pdo);
      analyse_action($pdo,$users);
      if (!isset($_SESSION['user'])) {
        print_login_form();
      } 
    ?>
  </head>
  <body>
    <center><h1>Configuration Page</h1></center><br><br>
    <?php
      print_chpasswd($pdo);
    ?>
   <a href="https://www.magniette.org/cb/index.php">Back to board</a>
  </body>
</html>
