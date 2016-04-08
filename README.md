Minimalist Corkboard
====================

This project is a minimalist corkboard. It is developped in php with a sqlite database. It has been designed with easyness of deployment in mind.

#Requirements

For deploying MC, you need a web server like apache or nginx with php mail instakked. On debian
    apt-get libphp-phpmailer installed.

#installation

* create a folder or a specific site for your corkboard on your web server.
* obtain the code via github

    git clone https://github.com/joyce8256/mcb.git

* create a folder ouside of any website, accessible by your web server. cp the file empty_cb.db to the folder, renaming it cb.db. Typically with apache on debian.

    mkdir /whereyouwant/cb/  
    cp empty_cb.db /whereyouwant/cb/  
    chown -R www-data:www-data /whereyouwant/cb/  

* edit the file cb_config.php and change db_loc with the path to the db file

    $db_loc="/whereyouwant/cb/cb.db";

* your corkboard is installed, you can test it with user:test password:test

#administration

All the administration is done by editing directly the database with an editor. my favourite is sqlitebrowser.

Adding a user
Add a line in the user table
    



