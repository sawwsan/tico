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
/** The name of the database for WordPress */
define( 'DB_NAME', 'new_tico' );

/** MySQL database username */
define( 'DB_USER', 'nikuadmin' );

/** MySQL database password */
define( 'DB_PASSWORD', '@Nikukiosk!' );

/** MySQL hostname */
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
define( 'AUTH_KEY',         'Z#*CHQSVJbiNC,BKZT^+uOkaGC9$?_!aXrUpoS|hP=)gWQAiaNgvGB%N^ZvQCVPp' );
define( 'SECURE_AUTH_KEY',  '>{IQs8$*S|bBp{ +hrS@U*p[qM2nvwY7w_dCIHwkTNt_2pu8OVBSD$<En.td&u-!' );
define( 'LOGGED_IN_KEY',    '`T{7wD7`nr|cTqn4M%PAl3VF~Z_:iIDcYngpEI$gn8zu_`Xy[,kb7i}]IA>]fNmr' );
define( 'NONCE_KEY',        '5(Fjyg6q{`g(OPZ}<,UjGbOO:!}gdYdXp/?JF{xpgCwe&*[md *27UZ@!ZiyD?73' );
define( 'AUTH_SALT',        '3e=x;|zViRfRQ]WPcfR?Aq>Pl/1bF3^qj-&vT*[D%j+jU|nVf~7g[)Tq=YooWbEm' );
define( 'SECURE_AUTH_SALT', '1& !dX+,)[$D2}_#l~Aen,rBm- Q~q!TBUMVN@*qb/s2Oy,@cySFxX},~@U5j[<g' );
define( 'LOGGED_IN_SALT',   '*y@_yiRg;.{kpRcOAkUCOD0-@~c9Ln]!mKZxcjRvad^HO52Ks2V/X3Yw:jj}8IF!' );
define( 'NONCE_SALT',       '}Nc5^;/[v#5^3Z-.@E7{pRg.[hAo8pfemA`*6=%zF4M8ud!vI5ce[S~>?$p$R`gy' );

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
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
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
