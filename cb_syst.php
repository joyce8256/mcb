<?php


function opendb($db_loc) {
  try{
    $pdo=new PDO("sqlite:".$db_loc);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE,PDO::FETCH_ASSOC);
    $pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_WARNING);
    return $pdo;
  } catch(Exception $e) {
    print("Impossible to access to DB : ".$e->getMessage());
    die();
  }
}

function analyse_action($pdo,$users) {

  //login if necessary
  if (!isset($_SESSION["user"]["name"])) {
    if ((!isset($_GET["action"])) || ($_GET["action"]!="login")) {
        print_login_form();
    }
  }

  if (isset($_GET["action"])) {
    if ($_GET["action"]=="login") {
      $guser=verify_user($pdo,$_GET["user"],$_GET["passwd"]);
      if (empty($guser)) {
        print("Wrong login, Please try again<br>");
        print_login_form();
      } else {
        $_SESSION["user"]=$guser;
      }
    }
    if ($_GET["action"]=="logout") {
      unset($_SESSION["user"]);
      print_goodbye();
    }
    if ($_GET["action"]=="chboard") {
      if (check_board_user($pdo,$_SESSION["user"]["id"],$_GET["board"])==1) {
        $_SESSION["user"]["current_board"]=$_GET["board"];
      } else {
        print("Access forbidden<br>");
        die();
      }
    }
    if ($_GET["action"]=="delmsg") {
      remove_message($pdo,$_GET["msgid"]);
    }
    if ($_GET["action"]=="newmsg") {
      new_message($pdo,$_GET["newmsg"],$_GET["bg"],$_GET["xinsert"]-64,$_GET["yinsert"],$_SESSION["user"]);
      send_email_forboard($pdo,$users,$_SESSION["user"],$_SESSION["user"]["current_board"]);
    }
  }
}

function get_users_table($pdo) {
    $table=[];
    $req="SELECT * FROM users";
    $stmt=$pdo->prepare($req);
    $stmt->execute();
    $result=$stmt->fetchAll();
    for($i=0;$i<count($result);$i++) {
      $table[$result[$i]["id"]]=$result[$i];
    }
    return $table;
}

function get_papers_table($pdo) {
  $table=[];
  $req="SELECT * FROM papers";
  $stmt=$pdo->prepare($req);
  $stmt->execute();
  $result=$stmt->fetchAll();
  for($i=0;$i<count($result);$i++) {
    $table[$result[$i]["id"]]=$result[$i];
  }
  return $table;
}

function verify_user($pdo,$user_name,$password) {
  $req="SELECT * FROM users WHERE name=\"".$user_name."\"";
  $stmt=$pdo->prepare($req);
  $stmt->execute();
  $result=$stmt->fetchAll();
  if (empty($result)) {
    return [];
  }
  if ($result[0]["password"]==$password)
    return $result[0];
  else
    return [];
}

function check_board_user($pdo,$user_id,$board_id) {
  $req="SELECT * FROM boards_users WHERE user_id=\"".$user_id."\" and board_id=\"".$board_id."\"";
  $stmt=$pdo->prepare($req);
  $stmt->execute();
  $result=$stmt->fetchAll();
  if (empty($result)) {
    return 0;
  } else {
    return 1;
  }
}

function update_user_maxid($pdo,$user_id,$maxid) {
  $req="UPDATE users SET max_msg_id=\"".$maxid."\" WHERE id=\"".$user_id."\"";
  $stmt=$pdo->prepare($req);
  $stmt->execute();
}

function remove_message($pdo,$msg_id) {
  $req="DELETE FROM messages WHERE id=\"".$msg_id."\"";
  $stmt=$pdo->prepare($req);
  $stmt->execute();
}

function new_message($pdo,$text,$bg,$x,$y,$user_obj) {
  $htmltext=htmlentities($text,ENT_QUOTES);
  $newtext=nl2br($htmltext);
  $req="INSERT INTO messages VALUES (NULL,'".$newtext."','".$x."','".$y."','".$bg."','".$user_obj["id"]."','".$user_obj["current_board"]."')";
  $stmt=$pdo->prepare($req);
  $stmt->execute();
}

function send_email($user_obj,$board_id,$users) {
  $mail = new PHPMailer();
  $mail->IsSMTP();
  $mail->CharSet="UTF-8";
  $mail->Host="localhost"; // SMTP server example
  $mail->SMTPDebug=0;           // enables SMTP debug information
  $mail->SMTPAuth=false;       // disable SMTP authentication
  $mail->Port=25;
  $mail->setFrom("cb@magniette.com","Corkboard");
  //$mail->addReplyTo("replyto@example.com","First Last");
  $mail->addAddress($user_obj["email"],$user_obj["name"]);
  $mail->Subject="New message for you on corkboard ".$board_id;
  $msg="There is a new message on board ".$board_id." available at address <a href=\"https://www.magniette.org/cb/index.php?action=chboard&board=".$board_id."\">here</a>";
  $mail->msgHTML("<html><body>".$msg."</body></html>");
  $mail->AltBody=$msg;
  $mail->send();
}

function send_email_forboard($pdo,$users,$creator_obj,$board_id) {
  $req="SELECT * FROM boards_users WHERE board_id=\"".$board_id."\"";
  $stmt=$pdo->prepare($req);
  $stmt->execute();
  $result=$stmt->fetchAll();
  for($i=0;$i<count($result);$i++) {
    if ($result[$i]["user_id"]!=$creator_obj["id"])
      send_email($users[$result[$i]["user_id"]],$board_id,$users);
  }
}

?>
