<?php

//************************* PAPERS *****************************************

function print_papers_selector($pdo) {
  print("<select name=\"bg\">\n");
  $req="SELECT * FROM papers";
  $stmt=$pdo->prepare($req);
  $stmt->execute();
  $result=$stmt->fetchAll();
  for($i=0;$i<count($result);$i++) {
    print("<option value=\"".$result[$i]["id"]."\">".$result[$i]["name"]."</option>\n");
  }
  print("</select>\n");
}

//************************* POSTITS ****************************************

function print_postit($pdo,$users,$papers,$message) {

    $top=$message["y"];
    $left=$message["x"];
    $paper_id=$message["paper"];
    $paper_file=$papers[$paper_id]["file"];
    $paper_width=$papers[$paper_id]["width"];
    $paper_height=$papers[$paper_id]["height"];
    $paper_color=$papers[$paper_id]["color"];

    print("     <!-- new message -->\n");
    print("     <div style=\"position:absolute;\n");
    print("                 top:".$top."px;\n");
    print("                 left:".$left."px;\n");
    print("                 background-image:url('papers/".$paper_file."');\n");
    print("                 height:".$paper_height."px;\n");
    print("                 width:".$paper_width."px;\n");
    print("                 background-size:101%;\n");
    print("                 display:flex;\n");
    print("                 justify-content:center\">\n");
    print("       <div style=\"align-self: center;\">\n");
    print("         <font color=".$paper_color."><b>".$message["message"]."</b></font>\n");
    print("       </div>\n");
    print("    </div>\n");


    print("    <!-- pin -->\n");
    print("    <div id=\"pin\" style=\"position:absolute;\n");
    print("                        top:".strval($top-10)."px;\n");
    print("                        left:".strval($left+$paper_width/2-10)."px;\">\n");
    print("      <img src=\"pin.png\" height=25>\n");
    print("    </div>\n\n");

    print("    <!-- message id -->\n");
    print("    <div style=\"position:absolute;\n");
    print("                top:".strval($top+10)."px;\n");
    print("                left:".strval($left+$paper_width/2-50)."px;\">\n");
    print("      <font size=\"2pt\"");
    if ($message["id"]>$_SESSION["user"]["max_msg_id"]) {
      print(" color=red");
      $_SESSION["user"]["max_msg_id"]=$message["id"];
    }
    print(">".$message["id"]."</font>\n");
    print("    </div>\n");
    
    print("    <!-- message author -->\n");
    print("    <div style=\"position:absolute;\n");
    print("                top:".strval($top+$paper_height-30)."px;\n");
    print("                left:".strval($left+$paper_width/4)."px;\n");
    print("                width:140px;\">\n");
    print("      <font size=\"2pt\" color=".$paper_color.">");
    print(       $users[$message["author"]]["name"]."</font>\n");
    print("    </div>\n\n");	
}

function print_all_postits($pdo,$users,$papers,$current_user) {
    $req="SELECT * FROM messages WHERE board=\"".$current_user["current_board"]."\"";
    $stmt=$pdo->prepare($req);
    $stmt->execute();
    $result=$stmt->fetchAll();
    for($i=0;$i<count($result);$i++) {
      print_postit($pdo,$users,$papers,$result[$i]);
    }
    update_user_maxid($pdo,$_SESSION["user"]["id"],$_SESSION["user"]["max_msg_id"]);
}

//************************** PIN *****************************************

function print_pin() {
  print("    <div id=\"pin\" style=\"position:absolute;top:-120px;left:-120px;\">\n");
  print("      <img src=\"pin.png\" height=25>\n");
  print("    </div>\n\n");
}

//************************** BOARD *****************************************


function print_board($pdo,$board_id) {
  print("<img id=\"cb\" src=\"corkboard_resized.jpg\" width=1024 heigh=768 onclick=\"click_cb(event)\" />\n\n\n");
}

function print_board_title($pdo,$board_id) {
  print("<!-- name of board -->\n");
  $req="SELECT * FROM boards WHERE id=\"".$board_id."\"";
  $stmt=$pdo->prepare($req);
  $stmt->execute();
  $result=$stmt->fetchAll();
  if (empty($result)) {
    $name="unknown board";
  } else { 
    $name=$result[0]['name'];
  }
  print("<div style=\"position:absolute;left:10px;top:710px;width:1020px;height:30px;\">");
  print("<center><font color=#EDDA74 size=\"5\"><b>".$name."</b></font></center>");
  print("</div>\n\n\n");
}

//************************ LOGIN/LOGOUT ***********************************

function print_goodbye() {
  print("<h1>Please come back soon!!!</h1>");
  print("<center><a href=\"https://www.magniette.org/cb/index.php\">Log back in</a></center>");
  die();
}

function print_login_form() {
    print("</head><body>");
    print("<form action=\"index.php\"");
    print(">");
    //print(" method=\"post\">");
    print("<table border=1>");
    print("<input type=\"hidden\" name=\"action\" value=\"login\">");
    print("<tr><td>User</td><td><input type=\"text\" name=\"user\"></td></tr>");
    print("<tr><td>Password</td><td><input type=\"password\" name=\"passwd\"></td></tr>");
    print("<tr><td colspan=\"2\"><center><input type=\"submit\" value=\"Enter\"><center></td></tr>");
    print("</table></form></body></html>");
    die();
}

//************************** BOARD *****************************************

function print_logout($pdo) {
  print("<div name=\"logout\" onclick=\"donothing()\">");
  print("logged as ".$_SESSION["user"]["name"]."<br>");
  print("<form action=\"index.php\">\n");
  print("<input type=\"hidden\" name=\"action\" value=\"logout\" />\n");
  print("<input type=\"image\" src=\"cmds/cmd_logout.png\"/>\n");
  print("</form></div>\n");
}

function print_reload($pdo) {
  print("<div name=\"reload\" onclick=\"donothing()\">");
  print("<form name=\"reload\" action=\"index.php\">\n");
  print("<input type=\"hidden\" name=\"action\" value=\"reload\" />\n");
  print("<input type=\"image\" src=\"cmds/cmd_reload.png\"/>\n");
  print("</form></div>\n");
}

function print_chboard($pdo) {

  print("<div name=\"chboard\" onclick=\"donothing()\">");
  print("<form name=\"chboard\" action=\"index.php\">\n");
  print("<input type=\"hidden\" name=\"action\" value=\"chboard\">\n");
  print("<select name=\"board\">\n");

  $req="SELECT * FROM boards";
  $stmt=$pdo->prepare($req);
  $stmt->execute();
  $boards_title=$stmt->fetchAll();

  $req="SELECT * FROM boards_users WHERE user_id=\"".$_SESSION["user"]["id"]."\"";
  $stmt=$pdo->prepare($req);
  $stmt->execute();
  $result=$stmt->fetchAll();
  for($i=0;$i<count($result);$i++) {
    for($j=0;$j<count($boards_title);$j++) {
      if ($boards_title[$j]["id"]==$result[$i]["board_id"]) {
        print("<option value=\"".$result[$i]["board_id"]."\">".$boards_title[$j]["name"]."</option>\n");
      }
    }
  }

  print("</select><br></br>\n");
  print("<input type=\"image\" src=\"cmds/cmd_change.png\"/>\n");
  print("</form></div>\n");
}

function print_delmsg($pdo) {
  print("<div name=\"delmsg\" onclick=\"donothing()\">");
  print("<table><tr><td><form name=\"delmsg\" action=\"index.php\">\n");
  print("<input type=\"hidden\" name=\"action\" value=\"delmsg\">\n");
  print("msg  <select name=\"msgid\">\n");

  $req="SELECT * FROM messages WHERE board=\"".$_SESSION["user"]["current_board"]."\"";
  $stmt=$pdo->prepare($req);
  $stmt->execute();
  $result=$stmt->fetchAll();
  for($i=0;$i<count($result);$i++) {
    print("<option value=\"".$result[$i]["id"]."\">".$result[$i]["id"]."</option>\n");
  }

  print("</select><br></br>\n");
  print("<input type=\"image\" src=\"cmds/cmd_delete.png\">\n");
  print("</form></td></tr></table></div>\n"); 
}

function print_newmsg($pdo) {
  print("<div name=\"newmsg\" onclick=\"donothing()\">");
  print("<form name=\"newmsg\" action=\"index.php\">\n");
  print("<input type=\"hidden\" name=\"action\" value=\"newmsg\" />\n");
  print("new msg <textarea rows=\"2\" name=\"newmsg\" cols=\"12\"></textarea>");
  print("<input type=\"hidden\" name=\"xinsert\" size=2 /><br>\n");
  print("<input type=\"hidden\" name=\"yinsert\" size=2 /><br>\n");
  print("bg  ");
  print_papers_selector($pdo);
  print("<br><br>\n");
  print("<input type=\"image\" src=\"cmds/cmd_create.png\"/>");
  print("</form></div>\n");
}

function print_all_commands($pdo) {

  print("<div style=\"position:absolute;top:20px;left:1050px;\">\n");

  print("<!-- COMMANDS -->\n");
  print_logout($pdo);
  print("<br>");
  print_reload($pdo);
  print("<br>");
  print_chboard($pdo);
  print("<br>");
  print_delmsg($pdo);
  print("<br>");
  print_newmsg($pdo);
  
  print("</div>");
}

//********************************************** CONFIG ****************************************

function print_chpasswd($pdo) {
  print("<form name=\"chpasswd\" action=\"config.php\">\n");
  print("<input type=\"hidden\" name=\"action\" value=\"chpasswd\" />\n");
  print("<table border=\"1\"><tr><td>");
  print("old passwd</td><td><input type=\"text\" width=10></td></tr>\n"); 
  print("<tr><td>new passwd</td><td><input type=\"text\" width=10></td></tr>\n");
  print("<tr><td colspan=\"2\"><input type=\"submit\" value=\"Change Password\"/></td></tr>");
  print("</table></form><br>\n\n");  
}

?>
