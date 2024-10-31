<?php
/**
 * Plugin Name: NoFollow jQuery Links
 * Plugin URI: https://nex.ist
 * Version: 1.5.3
 * Author: Nexist, Ankur Chauhan
 * Author URI: https://nex.ist/
 * Description: A simple TinyMCE Plugin to add a java-script link solution for linking pages together in order to stop search engines crawlers go through those pages and keep them just in the related pages of the same topic.
 * License: GPLv2
 */

if ( ! function_exists( 'njl_fs' ) ) {
    // Create a helper function for easy SDK access.
    function njl_fs() {
        global $njl_fs;

        if ( ! isset( $njl_fs ) ) {
            // Include Freemius SDK.
            require_once dirname(__FILE__) . '/freemius/start.php';

            $njl_fs = fs_dynamic_init( array(
                'id'                  => '4551',
                'slug'                => 'nofollow-jquery-links',
                'type'                => 'plugin',
                'public_key'          => 'pk_32a3a2666e95732018a96d0b2bf57',
                'is_premium'          => false,
                'has_addons'          => false,
                'has_paid_plans'      => false,
                'menu'                => array(
                    'first-path'     => 'plugins.php',
                    'account'        => false,
                    'support'        => false,
                ),
            ) );
        }

        return $njl_fs;
    }

    // Init Freemius.
    njl_fs();
    // Signal that SDK was initiated.
    do_action( 'njl_fs_loaded' );
}

class TM_JSLink
{
    function __construct()
    {
        if (is_admin()) {
            add_action('init', array($this, 'setup_tinymce_plugin'));
        }
        add_action('wp_enqueue_scripts', array($this, 'onclick_script'));
    }

    /**
     * Check if the current user can edit Posts or Pages, and is using the Visual Editor
     * If so, add some filters so we can register our plugin
     */
    function setup_tinymce_plugin()
    {

// Check if the logged in WordPress User can edit Posts or Pages If not, don't register our TinyMCE plugin

        if (!current_user_can('edit_posts') && !current_user_can('edit_pages')) {
            return;
        }

// Check if the logged in WordPress User has the Visual Editor enabled If not, don't register our TinyMCE plugin
        if (get_user_option('rich_editing') !== 'true') {
            return;
        }

// Setup some filters
        add_filter('mce_external_plugins', array($this, 'add_tinymce_plugin'));
        add_filter('mce_buttons', array($this, 'add_tinymce_toolbar_button'));
        //add_filter('mce_buttons', array($this, 'dialog'));

        //add_action('wp_footer', array($this, 'dialog'));
    }

    /**
     * Adds a TinyMCE plugin compatible JS file to the TinyMCE / Visual Editor instance
     *
     * @param array $plugin_array Array of registered TinyMCE Plugins
     * @return array Modified array of registered TinyMCE Plugins
     */
    function add_tinymce_plugin( $plugin_array ) {

        $plugin_array['nex_jslink'] = plugin_dir_url( __FILE__ ) . 'nex-tm-jslink.js';
        return $plugin_array;

    }

    /**
     * Adds a button to the TinyMCE / Visual Editor which the user can click
     * to insert a link with a custom CSS class.
     *
     * @param array $buttons Array of registered TinyMCE Buttons
     * @return array Modified array of registered TinyMCE Buttons
     */
    function add_tinymce_toolbar_button( $buttons ) {

        array_push( $buttons, '|', 'jslink' );
        array_push( $buttons, '|', 'jslink_blank' );
        array_push( $buttons, '|', 'jslink_remove' );

        return $buttons;
    }

    function onclick_script() {
        wp_enqueue_script(
            'jslink-onclick',
            plugin_dir_url( __FILE__ ) . 'jslink-onclick.js',
            array( 'jquery' )
        );
    }

    function dialog() {
        echo "<div></div>";
    }

}

$tm_jslink = new TM_JSLink;