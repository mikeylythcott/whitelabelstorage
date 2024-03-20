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
 * * Localized language
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'local' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', 'root' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

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
define( 'AUTH_KEY',          '+uvWpB<g}:D+F5dHy}=PkQ4[~Ri{FX!@SQz}[v*xp:n9KHMk_;T`umS@U}Xp#;0g' );
define( 'SECURE_AUTH_KEY',   '#Um2Wi)Xp@=|Z?d8p#Y)gCAF|lvXVHmL_;b|ol1.SikJ/|r@<uJ<KP3_1UirQLuL' );
define( 'LOGGED_IN_KEY',     'CLbRQaDEcI3Qu,-[Nmc#v9Ym>Q;34h/itO?Nr+w@1l$M,cC?_w0U&6DwV]DBZv+&' );
define( 'NONCE_KEY',         'Ch,G]*!s]iomoq2w{~&t[Ec>4?/@zxQE]Sp%F`^s^6DH[D?WcRC>5V6Sn1IdI5aV' );
define( 'AUTH_SALT',         'Y*+e:]U&f&2_9g3A&#V6)PGyS6Bw{N2C&^,x:^GLWr!~Wk:cga4O*{C_>VV{[02p' );
define( 'SECURE_AUTH_SALT',  'asS2VjL;k?c8Sp:[&<E_k<bNYSA+>w|Ed~4m8wDxEG=j5NHjRfTFu3Y,&Z9,9APT' );
define( 'LOGGED_IN_SALT',    '.hk!r99;/t9[}wzC0B^E?8q(m@r*9~_h$iZJAL;$L-3FMfB@#B)@C(U2eg?vc+wb' );
define( 'NONCE_SALT',        'd4?q^h@z4u7mB1Ht>Dz[]HuN zoXKQ 0dgcNd0eV^^.bG.!}<]NKl{[D%XEfJyOt' );
define( 'WP_CACHE_KEY_SALT', '4acOYsp#cuvtAr88DkcHIzqbz^NAm{%}(kvX@5a3(lg]!MS=:+1p)3KdY#DZ@fRe' );


/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';


/* Add any custom values between this line and the "stop editing" line. */


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
if ( ! defined( 'WP_DEBUG' ) ) {
	define( 'WP_DEBUG', false );
}

define( 'WP_ALLOW_MULTISITE', true );
define( 'MULTISITE', true );
define( 'SUBDOMAIN_INSTALL', false );
$base = '/';
define( 'DOMAIN_CURRENT_SITE', 'white-label-storage-b2c.local' );
define( 'PATH_CURRENT_SITE', '/' );
define( 'SITE_ID_CURRENT_SITE', 1 );
define( 'BLOG_ID_CURRENT_SITE', 1 );

define( 'WP_ENVIRONMENT_TYPE', 'local' );

//Rename wp-content folder
define ('WP_CONTENT_FOLDERNAME', 'app');

//Define new directory path
define ('WP_CONTENT_DIR', ABSPATH . WP_CONTENT_FOLDERNAME);

//Define new directory URL
define('WP_SITEURL', 'https://' . $_SERVER['HTTP_HOST'] . '/');
define('WP_CONTENT_URL', WP_SITEURL . WP_CONTENT_FOLDERNAME);

// others
define('ENABLE_CACHE', TRUE);
define('WP_MEMORY_LIMIT', '512M');
define('WP_POST_REVISIONS', 5);


/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
