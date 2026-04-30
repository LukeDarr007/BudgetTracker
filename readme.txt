For this project you will need to download XAMPP 

Then activate Apache & MySQL

You will need to download the code and extract to htdocs folder XAMPP

Copy the DML and follow this link: http://localhost/phpmyadmin

Press SQL and paste the DML, this will create the database

The link to my website is the following: http://localhost/budgettracker/index.php

For the administrator first run the following: http://localhost/BudgetTracker/BudgetTracker/hash.php

A hashed password will be displayed, upon this copy the password and do the following:

INSERT INTO User
(user_id, first_name, last_name, email, password, role, security_answer)
VALUES
(
    1,
    'Admin',
    'User',
    'buffbudgetadmin@gmail.com',
    'PASTE_YOUR_HASH_HERE', <----- Put the hashed password here
    'admin',
    'Admin'
);

From there the admin password will be functional and then please enter the following:

Admin Credentials:  

Email: "buffbudgetadmin@gmail.com"  

Password: "Admin123!" 

Security Answer: Admin 