# Snappy Shopper test

## Requirements
* PHP >= 7.0.0
* composer

## Instructions

1. Checkout project:
    ```
    git clone https://github.com/andrewf137/SnappyShoppyTest.git
    ```
2. "cd" to project folder.
3. Run `composer install`.
4. Set database credentials in the following line of .env file 
    ```
    DATABASE_URL="mysql://user:pass@127.0.0.1:3306/data_base_name"
    ```
5. Execute this command to create the database
    ```
    ./bin/console doctrine:database:create
    ```
6. Execute this command to run migrations
    ```
    ./bin/console doctrine:migrations:execute --up 'DoctrineMigrations\Version20210521002344'
    ```
7. Set a virtual host pointing out to `<projectFolder>/web/app`, something like:
    ```
    <VirtualHost *:80>
        ServerAdmin webmaster@localhost
        DocumentRoot /home/developer/workspace/SnappyShopperTest/web/app
    
        ServerName snappyshoppertest.loc
        ServerAlias snappyshoppertest.loc
    
        <Directory />
            Options FollowSymLinks
            AllowOverride All
        </Directory>
    
        <Directory /home/developer/workspace/SnappyShopperTest/web/app>
            AllowOverride All
            Order Allow,Deny
            Allow from All
            Require all granted
        </Directory>
    
        ErrorLog ${APACHE_LOG_DIR}/error.log
        CustomLog ${APACHE_LOG_DIR}/access.log combined
    </VirtualHost>
    
    # vim: syntax=apache ts=4 sw=4 sts=4 sr noet
    ```
8. Run fixtures to populate data so Top Agents can be calculated.
   This command will empty the database and then will set several random agents-properties relations.
   After that you can see the top agents by clicking on "Calculate Top Agents" button.
    ```
    ./bin/console doctrine:fixtures:load
    ```