<?php
// 1. Redirect logged-out users who try to visit '/dashboard' to the login page.
function redirect_dashboard_to_login() {
    if ( is_page('dashboard') && !is_user_logged_in() ) {
        wp_redirect( wp_login_url() );
        exit;
    }
}
add_action( 'template_redirect', 'redirect_dashboard_to_login' );

// 2. Redirect non-administrator users to '/dashboard' when they log in.
function redirect_non_admin_users_on_login( $redirect_to, $request, $user ) {
    // Is there a user to check?
    if ( isset( $user->roles ) && is_array( $user->roles ) ) {
        // If the user is an administrator, let them stay on the backend
        if ( in_array( 'administrator', $user->roles ) ) {
            return $redirect_to;
        } else {
            // Otherwise, redirect them to '/dashboard'
            return home_url( '/dashboard' );
        }
    } else {
        return $redirect_to;
    }
}
add_filter( 'login_redirect', 'redirect_non_admin_users_on_login', 10, 3 );

// 3. Remove the admin bar for non-administrator users.
function hide_admin_bar_from_non_admins() {
    if ( ! current_user_can( 'administrator' ) ) {
        show_admin_bar( false );
    }
}
add_action( 'init', 'hide_admin_bar_from_non_admins', 9 );
