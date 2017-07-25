<?php

/**

 * The base configurations of the WordPress.

 *

 * This file has the following configurations: MySQL settings, Table Prefix,

 * Secret Keys, WordPress Language, and ABSPATH. You can find more information

 * by visiting {@link http://codex.wordpress.org/Editing_wp-config.php Editing

 * wp-config.php} Codex page. You can get the MySQL settings from your web host.

 *

 * This file is used by the wp-config.php creation script during the

 * installation. You don't have to use the web site, you can just copy this file

 * to "wp-config.php" and fill in the values.

 *

 * @package WordPress

 */



// ** MySQL settings - You can get this info from your web host ** //

/** The name of the database for WordPress */

define('DB_NAME', 'ifmdcom');



/** MySQL database username */

define('DB_USER', 'ifmdcom');



/** MySQL database password */

define('DB_PASSWORD', 'XIqi97JfB0F');



/** MySQL hostname */

//define('DB_HOST', 'ifmdcom.db.7484239.hostedresource.com');
define('DB_HOST', 'localhost');



/** Database Charset to use in creating database tables. */

define('DB_CHARSET', 'utf8');



/** The Database Collate type. Don't change this if in doubt. */

define('DB_COLLATE', '');

/**#@+

 * Authentication Unique Keys and Salts.

 *

 * Change these to different unique phrases!

 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}

 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.

 *

 * @since 2.6.0

 */

define('AUTH_KEY',         ' ]5d.z__LN*f$*DnE8:<i)gAl?QgnY}}K&TmU?W-=+nD*AFY;^!%md>swB,]>m>Z');
define('SECURE_AUTH_KEY',  '-M.}8*|v8tC@iad x@jHkoC=.8j1-c9&r >~sv[5EkRt*g7a)X)ZB0$H>twrn>L!');
define('LOGGED_IN_KEY',    'ay |:5rt/ x:|0Aas1{1sX/R^XII`{:/GVUohKwC.{%_+z3p@TXs9GD->aZ@8-SQ');
define('NONCE_KEY',        '|gR4M2!:3c>E6XiH>^5z_/n&Z0zz|7!f~+DnC|S.JHGzcEx<8m[ ILY}X5;->CW$');
define('AUTH_SALT',        ':)oB0?Y%sI^&`ODgKu3>|Vzu-O3oqnl~@SJj+XC$B0rgBLB~7jwRb&nmh%Y.&I[@');
define('SECURE_AUTH_SALT', 'Q4n;-b^S-1^@sie V3eq)~);5;iU))i}2?<~o|7J3UVawgL@vkv=NS0Q08za`}+I');
define('LOGGED_IN_SALT',   '2 [bdKnGP0RFL$95V83+~=2?AcHNRK`Y8R0hXM%/A`x/Be<{dM{JhlEX+|[+7tTH');
define('NONCE_SALT',       'q_==cPY1] ZpA|%<C3Rvg-6t@%H`Sn:v_|MLg@Gw#/c!seOFwg3Nf +f`ckR8EM+');



/**#@-*/



/**

 * WordPress Database Table prefix.

 *

 * You can have multiple installations in one database if you give each a unique

 * prefix. Only numbers, letters, and underscores please!

 */

$table_prefix  = 'wp_live2_';



/**

 * WordPress Localized Language, defaults to English.

 *

 * Change this to localize WordPress. A corresponding MO file for the chosen

 * language must be installed to wp-content/languages. For example, install

 * de_DE.mo to wp-content/languages and set WPLANG to 'de_DE' to enable German

 * language support.

 */

define('WPLANG', '');



/**

 * For developers: WordPress debugging mode.

 *

 * Change this to true to enable the display of notices during development.

 * It is strongly recommended that plugin and theme developers use WP_DEBUG

 * in their development environments.

 */

define('WP_DEBUG', false);
define('WP_DEBUG_LOG', false);
define('WP_DEBUG_DISPLAY', false);
@ini_set('display_errors', 0);


/* That's all, stop editing! Happy blogging. */



/** Absolute path to the WordPress directory. */

if ( !defined('ABSPATH') )

	define('ABSPATH', dirname(__FILE__) . '/');



/** Sets up WordPress vars and included files. */

require_once(ABSPATH . 'wp-settings.php');
