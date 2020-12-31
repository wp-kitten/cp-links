<?php

use App\Helpers\UserNotices;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;

require_once( dirname( __FILE__ ) . '/index.php' );

add_action( 'valpress/plugin/deleted', function ( $pluginDirName ) {
    if ( CPL_PLUGIN_DIR_NAME == $pluginDirName ) {
        //#! Drop table
        if ( Schema::hasTable( 'links' ) ) {
            if ( !class_exists( 'CreateLinksTable' ) ) {
                require_once( CPL_PLUGIN_DIR_PATH . 'database/migrations/2020_10_13_144313_create_links_table.php' );
            }
            try {
                Artisan::call( 'migrate:rollback', [
                    '--path' => 'public/plugins/' . $pluginDirName . '/database/migrations/',
                ] );
            }
            catch ( Exception $e ) {
                UserNotices::getInstance()->addNotice( 'danger', '[' . $pluginDirName . '] Error: ' . $e->getMessage() );
            }
        }
    }
}, 10 );
