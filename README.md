# Smooth QSL
Smooth QSL is a simple QSL manager designed for amateur radio clubs in mind.
The primary design consideration is a simple, effective way for hams of
all computer skill levels to be able to upload ADIF logs and to provide a
quick and easy way for hams to download their QSLs for the club. Designed
originally to support the Silvercreek Amateur Radio Association's 40th
Anniversary Special Event.

# Installation
## Prerequisites
* A websever running PHP 5.x or better with the mysqli native client library
* A MySQL database running MySQL 5.6 or higher
* ImageMagick 6.x or better with the ImageMagick PHP library installed
* Basic ability to use an xxMP stack (e.g. LAMP, WAMP, etc.)

## Basic Directions
1. Download the Git repository.
2. Edit qslconf.php and change the configuration parameters as necessary.
3. Copy all of the files to your webserver directory
4. Load the qsl.sql into your MySQL server 
5. Setup some form of cron or other automated task to run the qslmaint.php
on a periodic basis (recommended 1hr for busier sites; see note bleow).

## Basic Coniderations
The application is design to have simple, user-friendly URLs. For example,
putting the base files in a subdirectory /qsl resultes in the URL
https://example.com/qsl for the main application and https://example.com/qsl/load
for the ADIF loader. All references to includes, configs, images, etc. are 
all relative pathed so the application should be installable virtually anywhere.

In order to make this as platform agnostic as possible, all temporary information
is stored in the MySQL database. In order to accomodate large ADIF imports,
the staging column type for the pre-commit data is a MySQL LONGTEXT field. This
will create a tremendous amount of empty-but-used space in MySQL when using
InnoDB. Ensure that the qslmaint.php script is being executed periodically as
one of its tasks is to run an OPTIMIZE TABLE on the transaction commit log.

# Using Smooth QSL
For hams, all the hams need is the "load key" set in qslconf.php. Smooth QSL
operates on a semi-trust basis in that hams are relied upon to use their
legitimate call signs as is expected throughout amateur radio. Set a strong
"load key" as that is the only protection against mischef such as loading
bogus QSOs or malicious actors attempting to DoS the database by filling it up
or overworking the system.

The load process uses the following workflow:
1. Navigate to https://example.com/qsl/load
2. Enter the callsign of the station operator, the load key, and select
the ADIF file. Upload the ADIF file.
3. Review the ADIF checkload on the next screen. Click Commit
4. A "receipt" page will apepr.

The QSL retrieval process is straightforward:
1. A ham navigates to https://example.com/qsl
2. Enters their callsign
3. Selects the QSO(s) they want to print on the card or certificate
4. Requests the print
5. Saves the PDF to their computer (or prints it directly)

# Printing QSOs on the template
Smooth QSO uses ImageMagick to "draw" text on top of the QSL template
file. qslconf.php contains a series of positioning configuration directives
that details how the QSL records are printed on the card. This allows for
maximum flexibilty in designing a card in regards to layout and size. However
keep the following in mind.

* The callsign for the QSO is printed once and is independent
of the other QSO information.
* The QSO record is always printed as a line of information with
relative or absolute offsets moving from the left to the page.
* The fields are in the order DATE, TIME, FREQ, RST, OPERATOR
and cannot be altered without changing the PHP code.
* Multiline fields are printed one after another ordered
by DATE, TIME in ascending order

# Contibuting and Support
All contributions will be considered if you send me a pull request
on GitHub. However I have certain design and simplicity goals for
this system and may not accept pulls that I deem to be too
complex or contrevenes one of my design goals.

I'll do my best to answer questions or fix bug as you find them but
this is a part-part-part time project for me. I will help as I can
with installation-related questions insofar as they deal with unclear
directions or bugs on particular platforms but I will not help with
general installation and configuration of a webserver, PHP, MySQL, etc.

# Platform
This system was designed and tested on a Raspberry Pi 3B running
Raspbian 8 Jessie + backports. The webserver was nginx.
