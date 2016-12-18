symfony-training
================

This is a blog builded with the framework Symfony 2

How to install 
---------
Step 1 (Switch to branch develop):

    git checkout develop

Step 2 (Update dependecies):

	composer update

Step 3 (Create Database):

	php app/console doctrine:database:create

Step 4 (Generate doctrine entities): 

	php app/console doctrine:schema:create
	
Step 5 (Generate data fixtures): 

	php app/console doctrine:fixtures:load 