<?php
/**
 * Plugin Name: TWG Annoucement
 * Plugin URI: http://gadgets.techlomedia.in/affiliate/
 * Description: This plugin adds annoucement banner in blog post
 * Version: 1.0.0
 * Author: Deepanker verma
 * Author URI: https://thewpguides.com
 * License: GPL2
 */

wp_enqueue_style( 'style1', plugins_url( 'style.css' , __FILE__ ) );

add_filter( 'the_content', 'filter_content' );

function filter_content( $content ){

    if ( is_single()){
        $pn = strip_tags(get_option( 'tma_p' ));
        if($pn=='')
              $pn=1;

        $adtitle= strip_tags(get_option( 'tma_title' ));
        $addesc= strip_tags(get_option( 'tma_desc' ));
        $adurl= strip_tags(get_option( 'tma_link' ));
        $enable= strip_tags(get_option( 'tma_enable' ));
        $btntext= strip_tags(get_option( 'tma_btn' ));
        if($btntext=='')
        {
            $btntext = 'Click Here';
        }
        
        if($enable=='yes')
        {
            $ad_code = '<div class="tma-wrapper">
                            <div class="tma-text">
                                <h4>'.$adtitle.'</h4>
                                <span>'.$addesc.'</span>
                            </div>
                            <div class="tma-button">
                                <a class="butn gtm-in-article-block" href="'.$adurl.'" target="_blank">'.$btntext.'</a>
                            </div>
                        </div>';

            if(!is_amp_endpoint())
            {
               $closing_p = '</p>';
                $paragraphs = explode( $closing_p, $content );
                foreach ($paragraphs as $index => $paragraph) {
                    if ( trim( $paragraph ) ) {
                        $paragraphs[$index] .= $closing_p;
                    }
                    if ( $pn == $index + 1 ) {
                        $paragraphs[$index] .= $ad_code;
                    }
                }
                 
                $content = implode( '', $paragraphs );
            }
        }
       
      }
        return $content;
}

add_action( 'admin_menu', 'tma_admin' );
 
function tma_admin() {
    add_options_page(
        'TWG Annoucement',
        'TWG Annoucement Settings',
        'manage_options',
        'yma-admin-plugin',
        'tma_options_page'
    );
}

function tma_options_page() {

if ( isset( $_POST['info_update'] ) ) {
    
    if(is_numeric($_POST['tma_p']))
    {
        $tmpCode1 = htmlentities( strip_tags(stripslashes( $_POST['tma_title'] )) , ENT_COMPAT );
		update_option( 'tma_title', $tmpCode1 );
		
		$tmpCode2 = htmlentities( strip_tags(stripslashes( $_POST['tma_desc'] )) , ENT_COMPAT );
		update_option( 'tma_desc', $tmpCode2 );
		
		$tmpCode3 = htmlentities( strip_tags(stripslashes( $_POST['tma_link'] )) , ENT_COMPAT );
		update_option( 'tma_link', $tmpCode3 );
        
		$tmpCode4 = htmlentities( strip_tags(stripslashes( $_POST['tma_enable'] )) , ENT_COMPAT );
		update_option( 'tma_enable', $tmpCode4 );
		
		$tmpCode6 = htmlentities( strip_tags(stripslashes( $_POST['tma_btn'] )) , ENT_COMPAT );
		update_option( 'tma_btn', $tmpCode6 );

		$tmpCode5 = htmlentities( strip_tags(stripslashes( $_POST['tma_p'] )) , ENT_COMPAT );
		update_option( 'tma_p', $tmpCode5 );
        
        echo '<div id="message" class="updated fade"><p><strong>';
		echo 'Options Updated!';
		echo '</strong></p></div>';
    }
    else
    {
        echo '<div id="message" class="error"><p><strong>';
		echo 'Error in form data';
		echo '</strong></p></div>';
    }
}
    ?>
    <div class="wrap">
        <h2>TWG Annoucement</h2>
        <hr />
    </div>

	    <form method="post" action="<?php echo $_SERVER["REQUEST_URI"]; ?>">
    	    <input type="hidden" name="info_update" id="info_update" value="true" />
    
    	    <fieldset class="options">
    	    <table width="100%" border="0" cellspacing="0" cellpadding="6">
    	       <tr valign="top"><td width="35%" align="left">
    	    <strong>Enable:</strong>
    	    </td><td align="left">
    	    <select name="tma_enable">
    	      <option value="no" <?php  if(get_option( 'tma_enable' )=='no'){ echo "selected"; }; ?>>No</option>
              <option value="yes" <?php  if(get_option( 'tma_enable' )=='yes'){ echo "selected"; }; ?>>Yes</option>
            </select>
    	    </td></tr>
            <tr valign="top"><td width="35%" align="left">
    	    <strong>Announcenent Title:</strong>
    	    </td><td align="left">
    	    <input type="text" name="tma_title" value="<?php echo get_option( 'tma_title' ); ?>" style="width: 90%;" />
    	    </td></tr>
            <tr valign="top"><td width="35%" align="left">
    	    <strong>Announcenent Description:</strong>
    	    </td><td align="left">
    	    <textarea name="tma_desc" style="width: 90%;"><?php echo get_option( 'tma_desc' ); ?></textarea>
    	    </td></tr>
    	    <tr valign="top"><td width="35%" align="left">
    	    <strong>Button Text:</strong>
    	    </td><td align="left">
    	    <input type="text" name="tma_btn" value="<?php echo get_option( 'tma_btn' ); ?>" style="width: 90%;" />
    	    </td></tr>
    	    <tr valign="top"><td width="35%" align="left">
    	    <strong>Button Link:</strong>
    	    </td><td align="left">
    	    <input type="text" name="tma_link" value="<?php echo get_option( 'tma_link' ); ?>" style="width: 90%;" />
    	    </td></tr>
    	    <tr valign="top"><td width="35%" align="left">
    	    <strong>Nummber of paragraph after which you want to put ad:</strong>
    	    </td><td align="left">
    	    <input type="text" name="tma_p" value="<?php echo get_option( 'tma_p' ); ?>" />
    	    </td></tr>
                </table>
                <div class="submit">
    	        <input type="submit" name="info_update" value="<?php _e( 'Save Options' ); ?> &raquo;" />
    	    </div>
	    </form>
    <?php
}
?>