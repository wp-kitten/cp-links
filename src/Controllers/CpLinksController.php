<?php

namespace App\Http\Controllers\Admin;

use App\Models\Link;
use Illuminate\Support\Str;

class CpLinksController extends AdminControllerBase
{
    public function index()
    {
        if ( !cp_current_user_can( 'manage_options' ) ) {
            return redirect()->back()->with( 'message', [
                'class' => 'danger', // success or danger on error
                'text' => __( 'cpl::m.You are not allowed to perform this action.' ),
            ] );
        }

        return view( 'cpl_all' )->with( [
            'links' => Link::paginate( $this->settings->getSetting( 'posts_per_page' ) ),
        ] );
    }

    public function showEditPage( $id )
    {
        if ( !cp_current_user_can( 'manage_options' ) ) {
            return $this->_forbidden();
        }

        return view( 'cpl_edit' )->with( [
            'link' => Link::find( $id ),
        ] );
    }

    public function __insert()
    {
        if ( !cp_current_user_can( 'manage_options' ) ) {
            return redirect()->back()->with( 'message', [
                'class' => 'danger', // success or danger on error
                'text' => __( 'cpl::m.You are not allowed to perform this action.' ),
            ] );
        }

        $this->request->validate( [
            'title' => 'required',
            'url' => 'required',
        ] );

        $title = Str::title( esc_html( $this->request->get( 'title' ) ) );
        $url = strtolower( $this->request->get( 'url' ) );

        if ( !filter_var( $url, FILTER_VALIDATE_URL ) ) {
            return redirect()->back()->with( 'message', [
                'class' => 'danger',
                'text' => __( 'cpl::m.The url is not valid.' ),
            ] );
        }

        $result = Link::create( [
            'title' => $title,
            'hash' => md5( $url ),
            'url' => $url,
        ] );

        if ( $result ) {
            return redirect()->back()->with( 'message', [
                'class' => 'success',
                'text' => __( 'cpl::m.Link added.' ),
            ] );
        }

        return redirect()->back()->with( 'message', [
            'class' => 'danger',
            'text' => __( 'cpl::m.An error occurred and the link could not be added.' ),
        ] );
    }

    public function __update( $id )
    {
        if ( !cp_current_user_can( 'manage_options' ) ) {
            return redirect()->back()->with( 'message', [
                'class' => 'danger', // success or danger on error
                'text' => __( 'cpl::m.You are not allowed to perform this action.' ),
            ] );
        }

        $this->request->validate( [
            'title' => 'required',
            'url' => 'required',
        ] );

        $title = Str::title( esc_html( $this->request->get( 'title' ) ) );
        $url = strtolower( $this->request->get( 'url' ) );

        if ( !filter_var( $url, FILTER_VALIDATE_URL ) ) {
            return redirect()->back()->with( 'message', [
                'class' => 'danger',
                'text' => __( 'cpl::m.The url is not valid.' ),
            ] );
        }

        $link = Link::find( $id );
        if ( !$link ) {
            return redirect()->back()->with( 'message', [
                'class' => 'danger',
                'text' => __( 'cpl::m.The specified link was not found.' ),
            ] );
        }

        $link->title = $title;

        //#! Check to see whether or not the url changed
        if ( $link->url != $url ) {
            if ( $link->exists( $url ) ) {
                return redirect()->back()->with( 'message', [
                    'class' => 'danger',
                    'text' => __( 'cpl::m.A link with the same URL already exists.' ),
                ] );
            }
            $link->url = $url;
            $link->hash = md5( $url );
        }
        $result = $link->save();

        if ( $result ) {
            return redirect()->back()->with( 'message', [
                'class' => 'success',
                'text' => __( 'cpl::m.Link updated.' ),
            ] );
        }

        return redirect()->back()->with( 'message', [
            'class' => 'danger',
            'text' => __( 'cpl::m.An error occurred and the link could not be updated.' ),
        ] );
    }

    public function __delete( $id )
    {
        if ( !cp_current_user_can( 'manage_options' ) ) {
            return redirect()->back()->with( 'message', [
                'class' => 'danger', // success or danger on error
                'text' => __( 'cpl::m.You are not allowed to perform this action.' ),
            ] );
        }

        $link = Link::find( $id );
        if ( !$link ) {
            return redirect()->back()->with( 'message', [
                'class' => 'danger',
                'text' => __( 'cpl::m.The specified link was not found.' ),
            ] );
        }

        $deleted = $link->destroy( [ $id ] );
        if ( $deleted ) {
            return redirect()->back()->with( 'message', [
                'class' => 'success',
                'text' => __( 'cpl::m.The link has been deleted.' ),
            ] );
        }
        return redirect()->back()->with( 'message', [
            'class' => 'danger',
            'text' => __( 'cpl::m.An error occurred and the link could not be deleted.' ),
        ] );
    }
}
