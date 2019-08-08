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
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'shop' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', '' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

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
define( 'AUTH_KEY',         'T<B^NKRPQ.6n{S*#q,0+G^Uf2qlCJPTvvC0H#AY)} 4/V GFjDK;w)m)@a$=XJRi' );
define( 'SECURE_AUTH_KEY',  '%z6Z.zne~K~Uv7f[zC/Xq9]udA@z3E-K&%o.R9>$&ai`yLa0 dHPC(pdGLtM_TuY' );
define( 'LOGGED_IN_KEY',    '!B:3E6=|Jajb%6>Pe#_wXt3CN5/XPiw$+i^>%?Y]m1V nO>(aoB:@HYyd,dqDM[>' );
define( 'NONCE_KEY',        'S Th+=5R,i${JjWHwC>.2;6`&=e0?H{9s2he{5*sq/ey<[Om*b6GFbK*t=mscH|I' );
define( 'AUTH_SALT',        'hWEHDr3xV$ $*Senno2`qDr&~#Ff{pO<fOlH5?prs.2]#d?{uT4A79Qh>B&iC7gl' );
define( 'SECURE_AUTH_SALT', 'OZHVDF%H$5e>Zz3Dg[05T;H#gbZ~M?2~9L?CRP3G6zrhjtPH,|VwrzvAhZKQSNFB' );
define( 'LOGGED_IN_SALT',   'AinVd)J0B[t]~?UC]C/f9dV0.<.~CaLyqebGzF/u&>L!35ova}~O2{Fb8;kfFE&d' );
define( 'NONCE_SALT',       '~DU.-95zO&act!W;Mi5<rBrn0XH%py3r7AX6/F1DNQWVq6rH/z`Qlq&Q?5TB]|0=' );

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
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define( 'WP_DEBUG', false );

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );
}

/** Sets up WordPress vars and included files. */
require_once( ABSPATH . 'wp-settings.php' );
