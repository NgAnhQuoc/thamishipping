<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the website, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'thamishipping' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', '' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         't+J%56hwX^9ZAiqhZ,(s[q6ppbq0JV[.b  +z2#k[=$2Z.Q5N9i!j<-.l9P-@l|V' );
define( 'SECURE_AUTH_KEY',  'hQWGQ$U=5{6p>c?_Yuj{M>K(=GDeV*p(1]G0)5V9z&@7}qCmj=2Hq#bUlBD6N7})' );
define( 'LOGGED_IN_KEY',    'c:XPt!0w.-!:=2aJE8O.mK$#YbE> X&xSftJ5Q=@]qYZ#O1U%z4B0JT?R~90J7Kl' );
define( 'NONCE_KEY',        '-GZ=CBEaZZ8j%8#ef^xL|^/=-{642rkTwIX05_u4,]D6dICj1Itzn=^DoJ1<eD27' );
define( 'AUTH_SALT',        'lZKfw9V^<enYDWhWcI[[=$x-:8~nj6z9=K.J65SWE#UVj 4?=h<SLQhk[KaWRm9Z' );
define( 'SECURE_AUTH_SALT', 'L!X#efsq1mc1m.^peu.fw`>nWN=+.7NQx8>m=VWGKJtk[l@@&bmdAmP/*6C=:zpV' );
define( 'LOGGED_IN_SALT',   'Rx*m?hnjjU0H;o{x-pr!x5q/f_P@%<`:f86juG}cJ}Aa`<HUb.&[of_[InUru9B&' );
define( 'NONCE_SALT',       'u(!.w[D$4O3}3cqSu(+Z&tpEZGl/jb.G0>T9#Y$!OA%r5;y:c[@WNUAo|*|2Qauc' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 *
 * At the installation time, database tables are created with the specified prefix.
 * Changing this value after WordPress is installed will make your site think
 * it has not been installed.
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/#table-prefix
 */
$table_prefix = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://developer.wordpress.org/advanced-administration/debug/debug-wordpress/
 */
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
