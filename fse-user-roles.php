<?php
/**
 * Plugin Name:     ASU Engineering - Starfleet User Roles
 * Plugin URI:      <github>
 * Description:     The default user roles for WordPress don't match up with a traditional workflow which involves heavy editing of pages and almost no use of posts. This plugin defines new roles that assist with this. <br>Plus... there's way too much emphasis on Star Wars as the "default" choice for sci-fi fans within the FSE. Make it so!
 * Author:          Steve Ryan, Fulton Schools of Engineering
 * Author URI:      YOUR SITE HERE
 * Text Domain:     fse-user-roles
 * Domain Path:     /languages
 * Version:         0.1.0
 *
 */

// New Roles, defined by this plugin.
function asufse_create_startrek_user_roles() {
    add_role (
        'captain',
        'Captain',
        array(
            create_users => true,
            customize => true,
            delete_others_pages => true,
            delete_pages => true,
            delete_private_pages => true,
            delete_published_pages => true,
            delete_users => true,
            edit_others_pages => true,
            edit_pages => true,
            edit_private_pages => true,
            edit_published_pages => true,
            edit_theme_options => true,
            edit_users => true,
            list_users => true,
            promote_users => true,
            publish_pages => true,
            read => true,
            read_private_pages => true,
            remove_users => true,
            unfiltered_html => true,
            upload_files => true
        )
    );

    add_role (
        'commander',
        'Commander',
        array(
            customize => true,
            delete_others_pages => true,
            delete_pages => true,
            delete_private_pages => true,
            delete_published_pages => true,
            delete_users => true,
            edit_others_pages => true,
            edit_pages => true,
            edit_private_pages => true,
            edit_published_pages => true,
            edit_theme_options => true,
            list_users => true,
            publish_pages => true,
            read => true,
            read_private_pages => true,
            remove_users => true,
            unfiltered_html => true,
            upload_files => true
        )
    );

    add_role (
        'lieutenant',
        'Lieutenant',
        array(
            delete_others_pages => true,
            delete_pages => true,
            delete_published_pages => true,
            edit_others_pages => true,
            edit_pages => true,
            edit_published_pages => true,
            publish_pages => true,
            read => true,
            unfiltered_html => true,
            upload_files => true
        )
    );

    add_role (
        'ensign',
        'Ensign',
        array(
            delete_pages => true,
            edit_pages => true,
            read => true,
            unfiltered_html => true,
            upload_files => true
        )
    );

}

// General function to update user role names
function asufse_update_user_role_name($roleslug, $propername) {
    //Get entire array, alter the array as a whole, update the option.
    $userrole = get_option('wp_user_roles');
    $userrole[$roleslug]['name'] = $propername;
    update_option('wp_user_roles', $userrole);
}

function asufse_filter_out_default_roles( $editable_roles ) {
    // These three roles are still available, but the plugin removes them from the dropdown.
    // TODO: Unset everything here and add them back in the proper order.
    unset( $editable_roles['editor']);
    unset( $editable_roles['author']);
    unset( $editable_roles['contributor']);
    return $editable_roles;
}
add_filter( 'editable_roles', 'asufse_filter_out_default_roles' );

// Turning on the plugin should add the roles and change some labels.
function add_roles_on_plugin_activation() {
       asufse_create_startrek_user_roles();
       asufse_update_user_role_name('administrator', 'Starfleet Command');
       asufse_update_user_role_name('subscriber', 'Civilian'); 
   }
register_activation_hook( __FILE__, 'add_roles_on_plugin_activation' );

// Restore the status quo when the plugin is deactivated.
function remove_roles_on_plugin_deactivation() {
    // From the CODEX: When a role is removed, the users who have a deleted role lose all rights on the site.
    // So, upon deactivation, all users need to be mapped back to a default user roles. 
    remove_role('captain');
    remove_role('commander');
    remove_role('lieutenant');
    remove_role('ensign');
    asufse_update_user_role_name('administrator', 'Administrator');
    asufse_update_user_role_name('subscriber', 'Subscriber');
    remove_filter( 'editable_roles', 'asufse_filter_out_default_roles' );
}
register_deactivation_hook( __FILE__, 'remove_roles_on_plugin_deactivation' );