<?php
/*************************************************
 * base.config.php
 * Main Configuration for the application. Defines
 * constants for less redundancy and less obvious
 * hard-coding
 *
 * @author			Arbor Solutions, Inc.
 * @website			http://www.arbsol.com
 * @copyright		2014 (C) Arbor Solutions, Inc.
 * @developer		Tyler J Barnes, tbarnes@arbsol.com
 ****************************************************/

/**			DataBase Conn			**/

// hostname, username, password, connection port
define("DB_HOST", "localhost");
define("DB_UNAME", "ASPMconnect");
define("DB_PASS", "TnT2M2nJWRpAEwBv");
define("DB_PORT", "3306");

// database / schema name
define("DB_NAME", "ASPM");
define("DB_DFLT_TYP", "mysql");
// table names
define("DB_TBL_CLI", "Clients");
define("DB_TBL_PROD", "Products");
define("DB_TBL_TIME", "TimeSheets");
define("DB_TBL_USER", "UserMain");
define("DB_TBL_XPRODXCLI", "XProductClient");



/**			Paths & Files			**/

// file extension
define("F_E", ".php");


define("HOME_DFLT", "index.php");
define("DOMAIN_ROOT", "http://localhost:8081/ArborSolutionsProjectManagement/");


// database errors
define("DBCON_FAIL", "Failed to connect to the database.  Please start this process over.");
define("DBCON_TBLFAIL", "There was a problem getting data from the table. Contact an administrator.");
define("DBCON_NODATA", "Either the expected data, or no data at all, was received.");
define("DBCON_MULT_OPEN", "A database connection is already established and must terminate before conducting another.");
define("DBCON_INVALID_SET", "Tried to set authentication data with null values - did not complete action.");


/**			Text Format			**/

// space, tab, tabs, newline
define("TXT_SPC", " ");
define("TXT_TAB", "\t");
define("TXT_2TAB", "\t\t");
define("TXT_3TAB", "\t\t\t");
define("TXT_4TAB", "\t\t\t\t");
define("TXT_NL", "\n");


/**			Arbor Info			**/
define("ARB_ADDR", "1345 Monroe Ave NE | Grand Rapids, Michigan 49505");
define("ARB_PHN", "(616)451-2500");


/**			Notifications		**/

// application feed
define("FEED_STAT", "Recently updated their status:");
define("FEED_IMG", "Added imagery to their profile!");
define("FEED_DOC", "Shared a new document with the network:");
define("FEED_EVENT", "A new event... Productive!");
define("FEED_PROFILE", "Made changes to their public profile - check it out.");
define("FEED_ADM_STAT", "Systems Administrator notified the network:");
define("FEED_ADM_EVENT", "Admin event! Questions? Please inquire.");
define("FEED_ADM_", "Systems Administrator notified the network:");

// user notifications
define("NOTIF_MSG", "Sent you a message.  Reply, leave the conversation, delete, ...?");
define("NOTIF_EVENT", "Said that you are involved in one of their events...");
define("NOTIF_ACCEPT", "Agreed to being involved in your event! ");
define("NOTIF_CONTACTS", "The alert system has notified us that you have a client to contact soon!");
define("NOTIF_EVENT_TIME", "You have an event coming up!");
define("NOTIF_TIMESHEET", "TimeSheets are due - have you finished yours?");
define("NOTIF_ADM_MSG", "You have received a message from admin.");
define("NOTIF_PASSWRDX", "Your password is about to expire.  Please change it soon - otherwise you will be prompted to with a future login.");
define("NOTIF_PROFILE_CONFG", "Take a quick minute to validate your Profile so others are up to date.");
define("NOTIF_LOWACTIVE", "You seem to be regularly inactive.  Please let us know what to imporve!");


?>
