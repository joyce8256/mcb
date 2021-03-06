Minimalist Corkboard
====================

This project is a minimalist corkboard. It is developped in php with a sqlite database. It has been designed with easyness of deployment in mind.

![Snapshot](snap_cb.jpg "Snapshot")

#Requirements

For deploying MC, you need a web server like apache or nginx with php mail installed. On debian

    apt-get libphp-phpmailer installed.

#installation

* create a folder or a specific site for your corkboard on your web server.
* obtain the code via github

        git clone https://github.com/joyce8256/mcb.git

* create a folder ouside of any website, accessible by your web server. cp the file empty_cb.db to the folder, renaming it cb.db. Typically with apache on debian.

        mkdir /whereyouwant/cb/
        cp empty_cb.db /whereyouwant/cb/cb.db
        chown -R www-data:www-data /whereyouwant/cb/

* edit the file cb_config.php and change db_loc with the path to the db file

        $db_loc="/whereyouwant/cb/cb.db";

* your corkboard is installed, see next section for adding a board and a user. 

#administration

All the administration is done by editing directly the database with an editor. my favourite is sqlitebrowser.

##Adding a board

in the table "boards", add a line with an unused id and a name.

Example :

    id=1 name="Fred's Board"

##Adding a user

in the table "users", add a line with an unused id and all informations for the user. Assign 1 to current_board and 0 to max_msg_id. Fill the email if you want notifications.

Example :

    id=1 name="Fred" password="myterrificpassword" email="fred@world.net" current_board=1 max_msg_id=1

##Allow a user to access a board

In the "boards_users" table, add a line with the board id and the user_id
   
#How to use it

##Logging

* Type your name and password in the login form
* Press the "Enter" button

##Reload the board

* Press the "Reload" post-it

##Change board

* Choose the board you want in the choice list just above the "Change post-it"
* Press the "Change" post-it 

##Add a new message

* Click on the board where you want your new message appears. A red pin helps you to find the good place
* Type your message in the "new msg" text area
* Choose the post-it type in the "bg" choice list
* Press the "Create" post-it

##Delete a message

* Choose the message number in the "msg" choice list. The number of the messages are written on their postit at the upper left.
* Press the "Delete" post-it 

##Logout

* Press the "Goodbye!" post-it
