<?php
/**
 * Plugin Name: TWG Announcement
 * Plugin URI: http://techlomedia.in/
 * Description: This plugin adds an announcement banner in blog posts.
 * Version: 1.0.1
 * Author: Deepanker Verma
 * Author URI: https://thewpguides.com
 * License: GPL2
 * Text Domain: techlomedia-affiliate
 */

// Prevent direct access.
if ( !defined( 'ABSPATH' ) ) {
    exit;
}

// Enqueue CSS properly.
function tma_enqueue_styles() {
    wp_enqueue_style( 'tma-style', plugins_url( 'style.css', __FILE__ ) );
}
add_action( 'wp_enqueue_scripts', 'tma_enqueue_styles' );

// Filter content to inject the announcement banner.
function tma_filter_content( $content ) {
    if ( is_single() ) {
        $pn       = absint( get_option( 'tma_p', 1 ) );
        $adtitle  = sanitize_text_field( get_option( 'tma_title', '' ) );
        $addesc   = sanitize_textarea_field( get_option( 'tma_desc', '' ) );
        $adurl    = esc_url( get_option( 'tma_link', '' ) );
        $enable   = sanitize_text_field( get_option( 'tma_enable', 'no' ) );
        $btntext  = sanitize_text_field( get_option( 'tma_btn', 'Click Here' ) );
        
        if ( $enable === 'yes' ) {
            $ad_code = '<div class="tma-wrapper">
                            <div class="tma-text">
                                <h4>' . esc_html( $adtitle ) . '</h4>
                                <span>' . esc_html( $addesc ) . '</span>
                            </div>
                            <div class="tma-button">
                                <a class="butn gtm-in-article-block" href="' . esc_url( $adurl ) . '" target="_blank">'
                                    . esc_html( $btntext ) . '</a>
                            </div>
                        </div>';

            if ( ! ( function_exists( 'is_amp_endpoint' ) && is_amp_endpoint() ) ) {

                $closing_p  = '</p>';
                $paragraphs = explode( $closing_p, $content );

                foreach ( $paragraphs as $index => $paragraph ) {
                    if ( trim( $paragraph ) ) {
                        $paragraphs[$index] .= $closing_p;
                    }
                    if ( $pn === $index + 1 ) {
                        $paragraphs[$index] .= $ad_code;
                    }
                }

                $content = implode( '', $paragraphs );
            }
        }
    }

    return $content;
}
add_filter( 'the_content', 'tma_filter_content' );

// Register settings securely.
function tma_register_settings() {
    register_setting( 'tma_settings_group', 'tma_title', 'sanitize_text_field' );
    register_setting( 'tma_settings_group', 'tma_desc', 'sanitize_textarea_field' );
    register_setting( 'tma_settings_group', 'tma_link', 'esc_url_raw' );
    register_setting( 'tma_settings_group', 'tma_enable', 'sanitize_text_field' );
    register_setting( 'tma_settings_group', 'tma_btn', 'sanitize_text_field' );
    register_setting( 'tma_settings_group', 'tma_p', 'absint' );
}
add_action( 'admin_init', 'tma_register_settings' );

// Add admin menu.
function tma_admin_menu() {
    add_options_page(
        'TWG Announcement',
        'TWG Announcement Settings',
        'manage_options',
        'tma-admin-plugin',
        'tma_options_page'
    );
}
add_action( 'admin_menu', 'tma_admin_menu' );

// Admin settings page.
function tma_options_page() {
    ?>
    <div class="wrap">
        <h2>TWG Announcement Settings</h2>
        <hr />
    </div>

    <form method="post" action="options.php">
        <?php
        settings_fields( 'tma_settings_group' );
        do_settings_sections( 'tma_settings_group' );
        wp_nonce_field( 'tma_update_options', 'tma_nonce' );
        ?>

        <table class="form-table">
            <tr valign="top">
                <th scope="row">Enable:</th>
                <td>
                    <select name="tma_enable">
                        <option value="no" <?php selected( get_option( 'tma_enable' ), 'no' ); ?>>No</option>
                        <option value="yes" <?php selected( get_option( 'tma_enable' ), 'yes' ); ?>>Yes</option>
                    </select>
                </td>
            </tr>

            <tr valign="top">
                <th scope="row">Announcement Title:</th>
                <td><input type="text" name="tma_title" value="<?php echo esc_attr( get_option( 'tma_title' ) ); ?>" style="width: 90%;" /></td>
            </tr>

            <tr valign="top">
                <th scope="row">Announcement Description:</th>
                <td><textarea name="tma_desc" style="width: 90%;"><?php echo esc_textarea( get_option( 'tma_desc' ) ); ?></textarea></td>
            </tr>

            <tr valign="top">
                <th scope="row">Button Text:</th>
                <td><input type="text" name="tma_btn" value="<?php echo esc_attr( get_option( 'tma_btn' ) ); ?>" style="width: 90%;" /></td>
            </tr>

            <tr valign="top">
                <th scope="row">Button Link:</th>
                <td><input type="text" name="tma_link" value="<?php echo esc_url( get_option( 'tma_link' ) ); ?>" style="width: 90%;" /></td>
            </tr>

            <tr valign="top">
                <th scope="row">Paragraph Number for Ad Placement:</th>
                <td><input type="number" name="tma_p" value="<?php echo absint( get_option( 'tma_p', 1 ) ); ?>" min="1" /></td>
            </tr>
        </table>

        <?php submit_button(); ?>
    </form>
    <?php
}
?>
