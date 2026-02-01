# Firefly QSL / Firefly QSL
Firefly QSL is a simple QSL manager designed for amateur radio clubs
and individuals who don't need the complex QSL management system of
major contesters and DXpeditions. The primary design consideration
is a simple, effective way for hams of all computer skill levels
to be able to upload ADIF logs and to provide a quick and easy way
for other hams to download their QSLs. Designed
originally to support the Silvercreek Amateur Radio Association's 40th
Anniversary Special Event.

## Major Changes from v2.x to v3
The following are the major changes in version 3:
* Full administrative login structure and session manager
* Support for multiple source callsigns to be loaded
* All libraries updated to the latest versions
* Refactoring the UI to be fully responsive
* Dark mode
* Configuration file has changed from `qslconf.php` to `config.php`


# Installation
## Prerequisites
* A websever running PHP 8.2 or better with the PDO, pdo_mysql, and
    imagick libraries available.
* A database running MariaDB 11+
* ImageMagick 6.x or better
* Basic ability to use an xxMP stack (e.g. LAMP, WAMP, etc.)

## New Installation
1. Download the [latest stable release](https://github.com/jxmx/smooth-qsl/releases/latest).
2. Edit `config.php` and change the configuration parameters as necessary.
3. Copy or upload all of the files to your webserver directory or hosting account.
4. Ensure the `cards` subdirectory is writable by the webserver. If this is
being installed in a hosting provider than this is likely the default.
5. Create the MySQL database as needed and load the `qsl.sql` into your MySQL server
6. Check the install with `check_install.php `.

## Upgrading from 2.x to 3
1. Backup your existing data and database
2. Download the [latest stable release](https://github.com/jxmx/smooth-qsl/releases/latest).
3. Edit the new `config.php` and merge the configuration from the old `qsoconfig.php`
    into the new file. The only material change is `$qsl_load_key` has been replaced
    with `$club_password`.

4. It is recommended to delete the existing website files and upload fresh ones from
    the latest release rather than try to hand-deleve the changes.
5. Ensure the `cards` subdirectory is writable by the webserver. If this is
    being installed in a hosting provider than this is likely the default.

6. Source the contents of `upgrade_2_to_3.sql` into the database. This makes
    changes to the qsos table and deletes other now-unused tables.

7. Check the install with `check_install.php `.

## Basic Coniderations
The application is designed to have simple, user-friendly URLs. For example,
putting the base files in a subdirectory `/qsl` resultes in the URL
`https://example.com/qsl` for the main application. All references to
includes, configs, images, etc. are all relative paths so the application
should be installable virtually anywhere.

The `qslmaint.php` script is run randomly on an index.php page with a 1 in 4
randomized chance by default of deleting old generated cards.

# Using Firefly QSL
For those uploading ADIFs, all the ham needs is the
`$club_callsign` and `$club_password` that was set in
`config.php`. Firefly QSL operates on a semi-trust basis in that
hams are relied upon to use their legitimate call signs as is
expected throughout amateur radio. Setting a strong password is the
only protection against mischef such as loading bogus QSOs or
malicious actors attempting to DoS the database by filling it up
or overworking the system.

The load process uses the following workflow:
1. Navigate to `https://example.com/qsl/load`
2. Enter the callsign of the QSLs. This does not have to match
the club callsigh. For example, a 1X1 or other call can be
loaded. Select the ADIF file, set the location from which the
contacts were made, and upload the ADIF file.
3. Review the ADIF checkload on the next screen. Click Commit
4. A "receipt" page will appear.

The QSL retrieval process is straight forward:
1. A ham navigates to `https://example.com/qsl`
2. Enters their callsign
3. Selects the QSO(s) they want to print on the card or certificate
4. Requests the print
5. QSL card is displayed. Save or print as desired.

# Printing QSOs on the template
Firefly QSL uses ImageMagick to "draw" text on top of the QSL template
file. `config.php` contains a series of positioning configuration directives
that details how the QSL records are printed on the card. This allows for
maximum flexibility in designing a card in regards to layout and size. However
keep the following in mind:

* The callsign for the QSO is printed once and is independent
of the other QSO information.
* The QSO record is always printed as a line of information with
relative or absolute offsets moving from the left to the page.
* The fields are in the order DATE, TIME, FREQ, RST, MODE, OPERATOR, COUNTY
* Multiline fields are printed one after another ordered
by DATE, TIME in ascending order

# Embedding Firefly QSL in another Site
It is possible to embed Firefly QSL in another site via an `<iframe>`
tag. For example, one user of Firefly QSL is embedding it in their
QRZ.com page. To embed the application, it's imperative to use
the `sandbox="allow-downloads"` attributes on the `<iframe>`
object or in-browser security will not permit the download of the
file. For example:

```
<iframe sandbox="allow-downloads" frameborder="1" height="700px"
  name="myiFrame"scrolling="no" src="https://example.com/qsl/"
  style="border:0px #ffffff none;" width="1000px"></iframe>
```

# Contributing and Support
All code contributions will be considered if you send me a pull request
on GitHub. However I have certain design and simplicity goals for
this system and may not accept pulls that I deem to be too
complex or contravenes one of my design goals.

I'll do my best to answer questions or fix bug as you find them but
this is a part-part-part time project for me. I will help as I can
with installation-related questions insofar as they deal with unclear
directions or bugs on particular platforms but I will not help with
general installation and configuration of a webserver, PHP, MySQL, etc.
