<?php

use App\Helpers\UserNotices;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;

if ( !defined( 'CPL_PLUGIN_DIR_NAME' ) ) {
    exit;
}

/**
 * Create the feeds table if it doesn't exist
 */
add_action( 'contentpress/plugin/activated', function ( $pluginDirName, $pluginInfo ) {
    //#! Run the migration
    if ( $pluginDirName == CPL_PLUGIN_DIR_NAME ) {
        if ( !Schema::hasTable( 'links' ) ) {
            if ( !class_exists( 'CreateLinksTable' ) ) {
                require_once( CPL_PLUGIN_DIR_PATH . 'database/migrations/2020_10_13_144313_create_links_table.php' );
            }
            try {
                Artisan::call( 'migrate', [
                    '--path' => 'public/plugins/' . $pluginDirName . '/database/migrations/',
                ] );
            }
            catch ( Exception $e ) {
                UserNotices::getInstance()->addNotice( 'danger', '[' . $pluginDirName . '] Error: ' . $e->getMessage() );
            }
        }
    }
}, 10, 2 );

//#! Register the views path
add_filter( 'contentpress/register_view_paths', function ( $paths = [] ) {
    $viewPath = path_combine( CPL_PLUGIN_DIR_PATH, 'views' );
    if ( !in_array( $viewPath, $paths ) ) {
        array_push( $paths, $viewPath );
    }
    return $paths;
}, 20 );

//
add_action( 'contentpress/admin/sidebar/menu', function () {
    if ( cp_current_user_can( 'manage_options' ) ) {
        ?>
        <li class="treeview <?php App\Helpers\MenuHelper::activateMenuItem( 'admin.cp_links' ); ?>">
            <a class="app-menu__item" href="#" data-toggle="treeview">
                <i class="app-menu__icon fas fa-link"></i>
                <span class="app-menu__label"><?php esc_html_e( __( 'cpl::m.Links' ) ); ?></span>
                <i class="treeview-indicator fas fa-angle-right"></i>
            </a>
            <ul class="treeview-menu">
                <li>
                    <a class="treeview-item <?php App\Helpers\MenuHelper::activateSubmenuItem( 'admin.cp_links.all' ); ?>"
                       href="<?php esc_attr_e( route( 'admin.cp_links.all' ) ); ?>">
                        <?php esc_html_e( __( 'cpl::m.Manage' ) ); ?>
                    </a>
                </li>
                <?php do_action( 'contentpress/admin/sidebar/menu/cp_links' ); ?>
            </ul>
        </li>
        <?php
    }
} );

/**
 * Register the path to the translation file that will be used depending on the current locale
 */
add_action( 'contentpress/app/loaded', function () {
    cp_register_language_file( 'cpl', path_combine( CPL_PLUGIN_DIR_PATH, 'lang' ) );
} );

