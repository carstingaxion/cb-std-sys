<?php
/**
 * In dieser Datei werden die Grundeinstellungen für WordPress vorgenommen.
 *
 * Zu diesen Einstellungen gehören: MySQL-Zugangsdaten, Tabellenpräfix,
 * Secret-Keys, Sprache und ABSPATH. Mehr Informationen zur wp-config.php gibt es auf der {@link http://codex.wordpress.org/Editing_wp-config.php
 * wp-config.php editieren} Seite im Codex. Die Informationen für die MySQL-Datenbank bekommst du von deinem Webhoster.
 *
 * Diese Datei wird von der wp-config.php-Erzeungsroutine verwendet. Sie wird ausgeführt, wenn noch keine wp-config.php (aber eine wp-config-sample.php) vorhanden ist,
 * und die Installationsroutine (/wp-admin/install.php) aufgerufen wird.
 * Man kann aber auch direkt in dieser Datei alle Eingaben vornehmen und sie von wp-config-sample.php in wp-config.php umbenennen und die Installation starten.
 *
 * @package WordPress
 */
if ( file_exists( dirname( __FILE__ ) . '/wp-config-local.php' ) ) {
  include( dirname( __FILE__ ) . '/wp-config-local.php' );
  define( 'WP_LOCAL_DEV', true );
} else {
  define( 'DB_NAME',     'production_db'       );
  define( 'DB_USER',     'production_user'     );
  define( 'DB_PASSWORD', 'production_password' );
  define( 'DB_HOST',     'production_db_host'  );
}

define('DB_CHARSET', 'utf8');

define('DB_COLLATE', '');

/** overwrite the default value of 32M **/
define('WP_MEMORY_LIMIT', '72M');

/** define theese for better performance ( no trailing slash )  **/
if ( defined( 'WP_LOCAL_DEV' ) ){
		$abs_path = 'E:/xampp/htdocs/standard/wp-content';
		$url_path = 'http://std.dev';
} else {
		$abs_path = 'E:/xampp/htdocs/standard/wp-content';
		$url_path = 'http://std.de';
}
define('WP_HOME', $url_path);  
define('WP_SITEURL', $url_path);  

define('WP_CONTENT_DIR', $abs_path);  
define('WP_CONTENT_URL', $url_path.'/wp-content');
define('WP_PLUGIN_DIR', $abs_path.'/plugins');
 
define('COMPRESS_SCRIPTS', true);
define('COMPRESS_CSS', true);
define('ENFORCE_GZIP', true);


/**
 *	BACKWPUP
 *
 *  @source http://wordpress.org/support/topic/2-new-errors-since-update-to-210
 */
define('WP_TEMP_DIR', ini_get('upload_tmp_dir'));

/**#@+
 * Sicherheitsschlüssel.
 *
 * Ändere jeden KEY in eine beliebiege, möglichst einzigartige Phrase. 
 * Auf der Seite {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service} kannst du dir alle KEYS generieren lassen.
 * Bitte trage für jeden KEY eine eigene Phrase ein. Du kannst die Schlüssel jederzeit wieder ändern, alle angemeldeten Benutzer müssen sich danach erneut anmelden.
 *
 * @seit 2.6.0
 */
define('AUTH_KEY',         ':_1m!GH*|$~@4o&jScQqM,/`@p{E5?3G*uVW/>&[0F&VP;K|)]MrW^z^0+3[0h6K');
define('SECURE_AUTH_KEY',  '#f4u-XD+N;1<%Vw&<>AM=nZB7nX `qDG<^hnMdF`~c,9)u1e/<*xV/yy~j4,F/,5');
define('LOGGED_IN_KEY',    '6iConS,0^|zIr1|niGYfM@@fjIlRy,TEY9$5f+Aqq[Cv>ZE{02AboBU4XgM/aFr5');
define('NONCE_KEY',        'QP$O&&[Th5j+wWeaaZ19l2.Z>LLdS9+DpB6[[v[LwZz9wWCl8?@`h9~[E`Y^{<9+');
define('AUTH_SALT',        'eLeG2RGknq;!|x*ZE`{ 4-d}VH&bc {:`9~_4<J)+iEpZ9={Ooyu{f=lf^2wQ/H<');
define('SECURE_AUTH_SALT', '4-$yiX**k2$8M4KXvHDhNO+ }xN.|[e-Z#aEnVVL3,AQ-hwT _~}|uVOR)mKT=*L');
define('LOGGED_IN_SALT',   'reG0tXV$Fidmv9AuqDO)&!IqfCi5$zt0n.N/%v8E)(t}v!1[sR*=ZW1fSU1}]wq!');
define('NONCE_SALT',       '~CA?5p2+@ |:+c7VLh)O52RE+Z<Z]TF^~-]s0PS|+9L7n]E5jELlwR4v(Y![c`L=');


/**#@-*/

/**
 * WordPress Datenbanktabellen-Präfix.
 *
 *  Wenn du verschiedene Präfixe benutzt, kannst du innerhalb einer Datenbank
 *  verschiedene WordPress-Installationen betreiben. Nur Zahlen, Buchstaben und Unterstriche bitte!
 */
$table_prefix  = 'hipepe34_';

/**
 * WordPress Sprachdatei
 *
 * Hier kannst du einstellen, welche Sprachdatei benutzt werden soll. Die entsprechende
 * Sprachdatei muss im Ordner wp-content/languages vorhanden sein, beispielsweise de_DE.mo
 * Wenn du nichts einträgst, wird Deutsch genommen.
 */
  if ( $_POST['lang'] ) {
        switch ( $_POST['lang'] ) {
            case "es":
                define ('WPLANG', 'es_ES');
                define ('FORCE_ADMIN_LANG', 'es_ES');
                break;
            case "de":
                define ('WPLANG', 'de_DE');
                define ('FORCE_ADMIN_LANG', 'de_DE');
                break;
            case "fr":
                define ('WPLANG', 'fr_FR');
                define ('FORCE_ADMIN_LANG', 'fr_FR');
                break;
            case "pt-br":
                define ('WPLANG', 'pt_BR');
                define ('FORCE_ADMIN_LANG', 'pt_BR');
                break;
            case "en":
                define ('WPLANG', 'en_US');
                define ('FORCE_ADMIN_LANG', 'en_US');
                break;
        }         
  } else {
    define ('WPLANG', 'de_DE');
    define ('FORCE_ADMIN_LANG', 'de_DE');
  }


/**
 * contact Form 7 Plugin 
 * dissable all unused stuff and call it only direct from the contact page  
**/
define( 'WPCF7_SHOW_DONATION_LINK', false );
define( 'WPCF7_LOAD_CSS', false );
define( 'WPCF7_LOAD_JS', false );
define( 'WPCF7_SHOW_DONATION_LINK', false );
define( 'WPCF7_AUTOP', false );
/**
 *  define an error log
 */
@ini_set('log_errors','On');
@ini_set('display_errors','Off');
@ini_set('error_log',$abs_path.'/php_error.log');  

/**
 *  only save max. 5 revisions per post
 */
define('WP_POST_REVISIONS', 5);

     
/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 * 
 *  Logged to /wp-content/debug.log  
 */
define( 'WP_DEBUG', false );
define( 'WP_DEBUG_DISPLAY', false ); 
define( 'WP_DEBUG_LOG', true); 

//The WordPress database class can be told to store query history:
define( 'SAVEQUERIES', true );


//E_ALL & ~E_DEPRECATED & ~E_STRICT
// USE ONLY in PHP 5.3 or higher
#define('E_DEPRECATED', true); 




/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');