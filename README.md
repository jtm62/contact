contact
=======

My take on a dynamic contact form.

My Contact information:

Name: Joshua T. McCauley

email: jtmccauley62@gmail.com

phone: 412-414-1106

General Disclaimer:  I decided to start using Github for several reasons.  First, I wanted to share some of my work with the community so people did not have to reinvent the wheel if they were doing something similar.  However, more importantly, I also wanted my work critiqued by the community, all my webdev has been done by trial and error, with no formal training or mentorship, I know that I can make improvements in my methods and practices and I am looking forward to hearing your own thoughts and suggestions.

This library will contain my take on dynamic contact forms, built anywhere on a webpage, allowing contact via email, cellular phone call, or SMS text message.

=================================IMPORTANT======================

To perform SMS texting you will need a google voice account or a cell phone attached to your server capable of integrating with Gammu.

To perform cellular phone calls you will need a google voice account.

================================================================

The object of this project was initally so I could have a clean and portable contact form for any of my various website projects.  I wanted to be able to archive each contact attempt in a database for review or analysis, if it was necessary. Therefore, I created a contact database, it is built in MySQL, and a dump of the design is included in this library.  It is primative, with only one table for storing all contact data as a flat file.  It could be further refined by including a few other tables, with a primary need for a banned persons table.  Persons should be banned by IP, email, or phone.  IPs should have the ability to be Blacklisted entirely, or only in conjunction with a blacklisted contact number.

Files included:

1. contactDB.sql & loggerDB.sql - a dump of the database schema. 
Current Version 1.0; Updated 25 November 2013 12:00

2. contactScript.js - the script which builds the dynamic contact form. 
Current Version 1.0; Updated 25 November 2013 12:00

3. contactScript.css - css file for the stylizing of the form. 
Current Version 1.0; Updated 25 November 2013 12:00

4. sendmessage.php - script to perform the database archiving and sending of the contact message.
Current Version 1.0; Updated 25 November 2013 12:00

5. class.dbconnect.php - class to allow for Database connections.
Current Version 1.0; Updated 25 November 2013 12:00

6. class.mail.php - class to allow for the mailing of contact form emails.
Current Version 1.0; Updated 25 November 2013 12:00

7. class.gammu.php - class to allow for SMS texting of messages, if you have a cell phone attached to your server.
Current Version 1.0; Updated 25 November 2013 12:00

8. sample.html - webpage demonstrating the capabilities of the program.
Current Version 1.0; Updated 25 November 2013 12:00

