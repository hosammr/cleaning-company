<?php
/**
 * Environment-aware wp-config.php
 *
 * Determines the environment and loads appropriate settings.
 * Drop this into the WordPress root and delete the original wp-config.php.
 *
 * @package HDS
 */

// ── Load Composer autoloader if present ──
if ( file_exists( __DIR__ . '/wp-content/vendor/autoload.php' ) ) {
	require_once __DIR__ . '/wp-content/vendor/autoload.php';
}

// ── Determine environment ──
$env = getenv( 'WP_ENV' ) ?: 'production';

// ── Database configuration ──
$db_name     = getenv( 'DB_NAME' )     ?: 'wordpress';
$db_user     = getenv( 'DB_USER' )     ?: 'root';
$db_password = getenv( 'DB_PASSWORD' ) ?: '';
$db_host     = getenv( 'DB_HOST' )     ?: 'localhost';
$db_prefix   = getenv( 'DB_PREFIX' )   ?: 'wp_';

define( 'DB_NAME',     $db_name );
define( 'DB_USER',     $db_user );
define( 'DB_PASSWORD', $db_password );
define( 'DB_HOST',     $db_host );
define( 'DB_CHARSET',  'utf8mb4' );
define( 'DB_COLLATE',  'utf8mb4_unicode_ci' );

$table_prefix = $db_prefix;

// ── Authentication keys ──
define( 'AUTH_KEY',         getenv( 'AUTH_KEY' )         ?: 'default-auth-key' );
define( 'SECURE_AUTH_KEY',  getenv( 'SECURE_AUTH_KEY' )  ?: 'default-secure-auth-key' );
define( 'LOGGED_IN_KEY',    getenv( 'LOGGED_IN_KEY' )    ?: 'default-logged-in-key' );
define( 'NONCE_KEY',        getenv( 'NONCE_KEY' )        ?: 'default-nonce-key' );
define( 'AUTH_SALT',        getenv( 'AUTH_SALT' )        ?: 'default-auth-salt' );
define( 'SECURE_AUTH_SALT', getenv( 'SECURE_AUTH_SALT' ) ?: 'default-secure-auth-salt' );
define( 'LOGGED_IN_SALT',   getenv( 'LOGGED_IN_SALT' )   ?: 'default-logged-in-salt' );
define( 'NONCE_SALT',       getenv( 'NONCE_SALT' )       ?: 'default-nonce-salt' );

// ── WordPress URLs ──
if ( getenv( 'WP_HOME' ) ) {
	define( 'WP_HOME', getenv( 'WP_HOME' ) );
}
if ( getenv( 'WP_SITEURL' ) ) {
	define( 'WP_SITEURL', getenv( 'WP_SITEURL' ) );
}

// ── Debug settings per environment ──
switch ( $env ) {
	case 'local':
		define( 'WP_DEBUG', true );
		define( 'WP_DEBUG_LOG', true );
		define( 'WP_DEBUG_DISPLAY', false );
		define( 'SCRIPT_DEBUG', true );
		define( 'SAVEQUERIES', false );
		define( 'WP_DEVELOPMENT_MODE', 'theme' );
		break;

	case 'staging':
		define( 'WP_DEBUG', true );
		define( 'WP_DEBUG_LOG', true );
		define( 'WP_DEBUG_DISPLAY', false );
		define( 'SCRIPT_DEBUG', false );
		define( 'SAVEQUERIES', false );
		break;

	case 'production':
	default:
		define( 'WP_DEBUG', false );
		define( 'WP_DEBUG_LOG', false );
		define( 'WP_DEBUG_DISPLAY', false );
		define( 'SCRIPT_DEBUG', false );
		define( 'SAVEQUERIES', false );
		break;
}

// ── Environment type ──
define( 'WP_ENVIRONMENT_TYPE', $env );

// ── Performance ──
define( 'WP_MEMORY_LIMIT',     '256M' );
define( 'WP_MAX_MEMORY_LIMIT', '512M' );
define( 'WP_POST_REVISIONS',   10 );
define( 'MEDIA_TRASH',         true );

// ── Security ──
define( 'DISALLOW_FILE_EDIT', true );
define( 'DISALLOW_FILE_MODS', false );
define( 'FORCE_SSL_ADMIN',    $env !== 'local' );

// ── Redis object cache ──
if ( getenv( 'WP_REDIS_HOST' ) ) {
	define( 'WP_REDIS_HOST', getenv( 'WP_REDIS_HOST' ) );
	define( 'WP_REDIS_PORT', getenv( 'WP_REDIS_PORT' ) ?: 6379 );
	define( 'WP_CACHE', true );
}

// ── Automatic updates ──
define( 'WP_AUTO_UPDATE_CORE', 'minor' );

// ── Filesystem (staging/production) ──
if ( $env !== 'local' ) {
	define( 'FS_METHOD', 'direct' );
}

// ── Cron ──
define( 'DISABLE_WP_CRON', false );

// ── Multisite ──
define( 'WP_ALLOW_MULTISITE', false );

// ── Absolute path ──
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

require_once ABSPATH . 'wp-settings.php';
