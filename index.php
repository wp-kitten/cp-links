<?php

/**
 * Stores the name of the plugin's directory
 * @var string
 */
define( 'CPL_PLUGIN_DIR_NAME', basename( dirname( __FILE__ ) ) );
/**
 * Stores the system path to the plugin's directory
 * @var string
 */
define( 'CPL_PLUGIN_DIR_PATH', trailingslashit( wp_normalize_path( dirname( __FILE__ ) ) ) );

require_once( CPL_PLUGIN_DIR_PATH . 'src/Models/Link.php' );
require_once( CPL_PLUGIN_DIR_PATH . 'src/Controllers/CpLinksController.php' );

require_once( CPL_PLUGIN_DIR_PATH . 'functions.php' );
require_once( CPL_PLUGIN_DIR_PATH . 'plugin-hooks.php' );
require_once( CPL_PLUGIN_DIR_PATH . 'routes/web.php' );
