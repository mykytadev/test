<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/documentation/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'test' );

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
define( 'AUTH_KEY',         'N/}AU)jIl-?~KBg9V0%~# Sx Lx+-z;5SNRn^qQ26|X`5$q)]SluS|4F 3kaW<u)' );
define( 'SECURE_AUTH_KEY',  'E$-EY8yC3F0Rfy6O@A0Fh@jWGV9z)0beuLSUnYg%C;$r&0p0.IJ`T:KoHp5j0k#0' );
define( 'LOGGED_IN_KEY',    'uA8S3KP#=2ga4l<l1=`1b2vvz4/C0-K+z{-CHMWxZ#_laC&Sb0niiJ5F|cc~RlP5' );
define( 'NONCE_KEY',        'u+1D6a%[ylimJuj|%/-hX4?AY#NaIJfc)&0HnSdP7QvpHGzx|1-9~gkci*O#gIDj' );
define( 'AUTH_SALT',        '*<+pf[3AVb*-)2AgKU!t-X$fk9t@bj#pl/@d|kDBBFW;N_:LumrpaB=V(TUeVq<b' );
define( 'SECURE_AUTH_SALT', 'Px/}tiD,{I]D|FJgjTKxRod]2Uq}96!~|F$4Y8dH=~OOE- i*SqA[*V/s%=rKeMO' );
define( 'LOGGED_IN_SALT',   '6;R0Q$LMY~?NYheKMB.wB:0IKRc(@5T_R60|u*&6RQbyP|:<oOezP;i$~D}*IVY,' );
define( 'NONCE_SALT',       'glPXoXi;836)K0`q[Q3;AK94y_vJCI~kx^g&/y}P$V[V}LY`HG@huOs~nB $#>g*' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
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
 * @link https://wordpress.org/documentation/article/debugging-in-wordpress/
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
