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
define('DB_NAME', 'nicolemi_w');

/** MySQL database username */
define('DB_USER', 'nicolemi_u1');

/** MySQL database password */
define('DB_PASSWORD', 'krRPmF7t+LRr');

/** MySQL hostname */
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
define('AUTH_KEY',         'n}HPr45,793{8 x61v$_Gs##Q0>J+w[S`e o_ECJt=2sITY&-,j=&peZ*-OkzHx~');
define('SECURE_AUTH_KEY',  '4Z6^XiFua>ta2mWj5GXQz@T&7>On9Q&fTnp^nL|I/Z!#v!hdEO*emwj1L|](* ;!');
define('LOGGED_IN_KEY',    '53 Vwz}A)?;Wf^5RxYF@;7f /-*vh4-Kd<7&yFivR(^tgvJ)f7QX4MBWsp,8#fN,');
define('NONCE_KEY',        '/p^t>C3Q~2Jmp8ZMu%Z=w5Y5vYfM;g2#}gK#|s|6@VNQ%7R1,?sx$v@OZKpV:38C');
define('AUTH_SALT',        'BrqgX!!QNdGS~3=F-v?{)+^sO3cwp#DSmlM|kk8!7E#CUu&J)G7+U#_:me-1 BSh');
define('SECURE_AUTH_SALT', 'g.o*F_DfQ;p|(h^9T07>x%8&zZPF.PNF+jQ_HfH!$Huc)3L/SN(,O^1i|k>[u}*d');
define('LOGGED_IN_SALT',   'JB,,`Kh<0gk]_~-![p*G[Gyk{MvV:N)}zwy2w1|1me-+uDL{%IQzbr-r^{jmRwgL');
define('NONCE_SALT',       'H{{$8D8Zn@h[JVu-aH`UZT]/$4cdZ:W7+4I/}!~_O=x>.Mq3v<;D)yUO_rZ!<~00');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_nwm_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');

/**
* Added to allow install without FTP
**/
define( 'FS_METHOD', 'direct' );
