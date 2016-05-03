<!DOCTYPE html>
<html>
  <head>
    <script language="JavaScript">
      function click_cb(event) {
        pos_x=event.offsetX?(event.offsetX):event.pageX-document.getElementById("cb").offsetLeft;
        pos_y=event.offsetY?(event.offsetY):event.pageY-document.getElementById("cb").offsetTop;
        document.newmsg.xinsert.value=pos_x;
        document.newmsg.yinsert.value=pos_y;
        pin=document.getElementById('pin');
        pin.style.top=pos_y+"px";
        pin.style.left=pos_x+"px";
      }
      function donothing() {
      }
    </script>
    <?php
      require_once("libphp-phpmailer/PHPMailerAutoload.php");
      include "cb_config.php";
      include "cb_syst.php";
      include "cb_print.php";
      $pdo=opendb($db_loc);
      session_start();
      $users=get_users_table($pdo);
      $papers=get_papers_table($pdo);
      analyse_action($pdo,$users);
      if (!isset($_SESSION['user'])) {
        print_login_form();
      } 
    ?>
  </head>
  <body>
    <?php
	print_board($pdo,$_SESSION["user"]["current_board"]);
        print_pin();
        print_all_postits($pdo,$users,$papers,$_SESSION["user"]);
	print_board_title($pdo,$_SESSION["user"]["current_board"]);
        print_all_commands($pdo);
    ?>
  </body>
</html>
