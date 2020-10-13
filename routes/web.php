<?php

use App\Http\Controllers\Admin\CpLinksController;
use Illuminate\Support\Facades\Route;

/*
 * Add custom routes or override existent ones
 */

Route::get( "admin/cp_links", [ CpLinksController::class, 'index' ] )
    ->middleware( [ 'web', 'auth', 'active_user' ] )
    ->name( "admin.cp_links.all" );

Route::get( "admin/cp_links/edit/{id}", [ CpLinksController::class, 'showEditPage' ] )
    ->middleware( [ 'web', 'auth', 'active_user' ] )
    ->name( "admin.cp_links.edit" );

Route::post( "admin/cp_links/create", [ CpLinksController::class, '__insert' ] )
    ->middleware( [ 'web', 'auth', 'active_user' ] )
    ->name( "admin.cp_links.create" );

Route::post( "admin/cp_links/update/{id}", [ CpLinksController::class, '__update' ] )
    ->middleware( [ 'web', 'auth', 'active_user' ] )
    ->name( "admin.cp_links.update" );

Route::post( "admin/cp_links/delete/{id}", [ CpLinksController::class, '__delete' ] )
    ->middleware( [ 'web', 'auth', 'active_user' ] )
    ->name( "admin.cp_links.delete" );


