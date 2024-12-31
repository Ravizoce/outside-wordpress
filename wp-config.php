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
define( 'DB_NAME', 'wp' );

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
define( 'AUTH_KEY',         'o)Wqh<PcxTxY`dg2Ym+e)ufvFKy3->h-ba$7ZOg~ymsA6]p?CwdbhhMs`HuYPO*8' );
define( 'SECURE_AUTH_KEY',  'o%;,iu-F9BAKc#raLU@y{%La27o^Qnu3^%,CeK-PH*WU}FJ4kCD1|P|C?SgV3ET7' );
define( 'LOGGED_IN_KEY',    '}>j1<gjaeoFxti;^cT{y)-iY%WexeR4%6|E:>6KdjUYlmH3)JV1at^D`nM~]z(Nw' );
define( 'NONCE_KEY',        'V2auE`q9ExI#c^OzNZ!-<bSA(>k2pX_v0DX~.z47SZBLyS(5P4p+5_rw^hLBC3OG' );
define( 'AUTH_SALT',        '-h0R&i$qpAkme1HvIa)Wu$Uy3&STP]4: s[a$[7li&6|5OK}Qy[u&aP!=yBdQYJ-' );
define( 'SECURE_AUTH_SALT', 'pmH$O+|N?d,Nd.O:zPT`]FgWk4M8Tod F4BbH7%td;SS4d.GoP@X^s]bF{]Cm^S%' );
define( 'LOGGED_IN_SALT',   'E<IzuLX|rT[{NM0}=%u_-=21%0i,!}x*7%$E:9(L_QeidM;<z-W?Zrj[k#O5*(,b' );
define( 'NONCE_SALT',       '.b@gAHsvRJfsNbA#*1AJE({~y:@WQTTK6S!u^r(KrH44B:[GYV6uS0G`dS0ukh8J' );

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

/* Add any custom values between this line and the "*/

define('FS_METHOD', 'direct');




/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
