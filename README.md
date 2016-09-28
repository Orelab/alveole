


# What is Alveole ?



# Installation

## Get Alveole

Download Alveole from Github here : https://github.com/Orelab/alveole

## Get a web hosting provider

Alveole is built to comply most of the hosting system. You'll need a hosting supporting PHP 5.4 or higher, and MySQL.

## Install the scripts

Alveole comes with two folders. The one called app must be placed outside the web root, in a way that is is impossible to point files from the web.

The content of the folder called site must be installed in the web root, in a way that when you visit your website, the root url point to index.php.

It is very important to respect this, as it is a question of security.

## Install the database

Connect to phpMyAdmin (or other tool to manage you database), and create a database where you'll execute the database.sql script available in the archive you previously downloaded.

## Configure Alveole

Edit the file configuration.php located in the web root folder

Here are some details about the content of this faile

    base_url Must contain the root url of Alveole.
    app_folder This folder points to the app/ folder.
    encryption_key For security, edit this string and put random data here (no need to remember it !).
    max_size The max file size Alveole will be allowed to upload. Note that you'll certainly have to edit your webserver configuration too.
    database
        hostname The server we your database is (localhost, or something else).
        username The username alloed to edit the database.
        password Username's password.
        database The database name.





# Default account

Once installed, clic oin the "No account yet ?" link to create your account.
If you want to become administrator, simply browse your database (phpMyAdmin or whatever else) to the "user" table,
and change the "group" value to "admin".

Please note that Alveole is set will a default admin account you could use instead. If you create your own account,
we greatly encourage you to remove this one ! ! !

- default user : "nobody@alveole.org"
- default password : "changeyourpasswordfirst!"


