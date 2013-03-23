#Mockstock 
Mock stock is an online stock trading game played with virtual money. The aim of the player is to maximize profit at the end of the game. Player can sell or buy shares of different companies using his virtual money. In this game we simulate the stock market by creating a virtual environment in which the rise and fall of the stock prices are determined by the system generated news and past history along with the stock trend.
##How to run
1. Edit config.php and set all the database details and also set the base url i.e url where the application is hosted.
2. Import the export.sql to the database being used. You can either use phpMyAdmin or do this:  
<code>mysql --user=username  --password=my_password my_database_name &lt; export.sql</code>
##Start Game
1. Setup admin password either by editing <i>gameconf</i> table or by using  the default password <i>alseambusher</i>
2. Open [host]/admin.php to add/delete/modify news and to set the start time of the game
