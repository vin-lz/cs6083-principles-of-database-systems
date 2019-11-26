# cs6083-principles-of-database-systems
This is a repository for the coursework for CS-GY 6083 Principles of Database Systems at New York University. Fall 2019
## Objectives
* To build a simple PHP backend supporting queries required in Problem Set 3 Question 1
* To learn basic PHP
## How to use
### Example on macOS Catalina v10.15.1
#### Database Server

* Install `MySQL Workbench`, make sure the server is running
* For demo usage, create a user `ps3` with a password `pword`, grant the privilege to this user to create new database `weather_station`
* Load `hw2_revised_v2.sql` into `MySQL Workbench`
#### PHP Backend
* Set up Apache Server, enable PHP module from the `httpd.conf`
* Put the codes file in the `DocumentRoot` directory, or change `DocumentRoot` in `httpd.conf` to the directory in where you put the code
* Add `index.php` into `DirectoryIndex` in `httpd.conf` if it is not there already
* Run `sudo apachectl start` to start the server
* Visit `localhost` from your browser, there you go
## Known issues
* Browser adaptation issue - For best visual effect, please use `Chrome`
* Make sure `pdo_mysql.default_socket=` in `php.ini` matches the `.sock` your Data Sever provides
## License
Please note this is a coursework. Any re-use of these codes can result in **PLAGIARISM**. Refer to these codes at your own risk.