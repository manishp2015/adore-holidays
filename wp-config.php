<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //

if (strstr($_SERVER['SERVER_NAME'], 'adore-holidays')) {
	define( 'DB_NAME', 'adoreh5_julian' );
	define( 'DB_USER', 'adoreh5_admin' );
	define( 'DB_PASSWORD', '?Km,0[Z97d.D' );
	define( 'DB_HOST', 'localhost' );
} else {
	define( 'DB_NAME', 'adore-holidays' );
	define( 'DB_USER', 'root' );
	define( 'DB_PASSWORD', '' );
	define( 'DB_HOST', 'localhost' );
}	

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'Dr13(-b,TQF+bw.]Qm8{bNd?4H`u`MR&iz_< <r,!;.0@<o]/-zi%VY4iRu:Ifi<' );
define( 'SECURE_AUTH_KEY',  'Cl+ GcVL{vKlg}e!a. &{WPp zfklnvlD5CsI))}utB[r6#t4yA2t8i 5Q+mN3F6' );
define( 'LOGGED_IN_KEY',    '=e<lH{@r?gked_T/-Qy/@dIvT*I;W%<VO;B{+?-+O(iJCjPcGgU+pe&LyIyM~_h+' );
define( 'NONCE_KEY',        '^q ui13w%b,l aHi!*mFn=ddVQ)U{NnoRc5fzpQWIinRt!YEi<o_paJ<e9UOig7D' );
define( 'AUTH_SALT',        '-OitLGZSTd7**GCT-+sO,?3Ytv7b$;t8ElB1>|n1$c)2dO2w~>AQcq(-AfC<7P</' );
define( 'SECURE_AUTH_SALT', 'is<GF^UwNKxPGWI`x[<^Asgn7w@Ma)4:DH3+{@(dOPo8l@@P2v:Kz> 6m@cDD&4Z' );
define( 'LOGGED_IN_SALT',   'tu1$v*p#wc>n0Ns`svpzex~kR%3.Z8,r[.WZ1%4P]<i!9FmE6gl|od/6[K0>zLLL' );
define( 'NONCE_SALT',       '1C#Q0]JNc[{i#va@g^5=OyCbq;Rq^f>alujo23L8K>P6}lplJj72NKm@@$iRypE+' );

/**#@-*/

/**
 * WordPress Database Table prefix.
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
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */

define( 'WP_DEBUG', false );
/* define( 'WP_DEBUG_LOG', true ); */
/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';

define('DISALLOW_FILE_EDIT', false);
define('DISALLOW_FILE_MODS', false);