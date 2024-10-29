<?php
if ( !function_exists( 'add_action' ) ) {
    $wp_root = realpath( dirname( __FILE__ ) . '/../../../..');

    if ( file_exists( $wp_root . '/wp-load.php' ) )
        require_once $wp_root . '/wp-load.php';
    else
        require_once $wp_root . '/wp-config.php';
}
?>
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title>Apiki WP FAQ</title>
        <script language="javascript" type="text/javascript" src="<?php echo get_bloginfo( 'url' ); ?>/wp-includes/js/tinymce/tiny_mce_popup.js"></script>
        <script language="javascript" type="text/javascript" src="<?php echo get_bloginfo( 'url' ); ?>/wp-includes/js/tinymce/utils/form_utils.js"></script>
        <script language="javascript" type="text/javascript" src="<?php echo WP_PLUGIN_URL . '/apiki-wp-faq/assets/js/tinymce.js?v=' . filemtime( WP_PLUGIN_DIR . '/apiki-wp-faq/assets/js/tinymce.js' ); ?>"></script>
        <base target="_self" />
    </head>
    <body id="link" onload="tinyMCEPopup.executeOnLoad('init();');document.body.style.display='';" style="display: none">
        <form id="apiki_wp_faq_form" action="#">
            <table border="0" cellpadding="4" cellspacing="0">
                <tr>
                    <td nowrap="nowrap">
                        <label for="apiki_wp_faq_categories">
                            <?php _e( 'Show questions by category', $apiki_wp_faq->text_domain ); ?>:
                        </label>
                    </td>
                    <td>
                        <select id="apiki_wp_faq_categories" name="faq_category" style="width: 200px">                            
                            <?php $apiki_wp_faq->display_dropdown_categories(); ?>                    
                        </select>
                    </td>
                </tr>
                <tr>
                    <td nowrap="nowrap">
                        <label for="apiki_wp_faq_limit">
                            <?php _e( 'Number of questions to show', $apiki_wp_faq->text_domain ); ?>:
                        </label>
                    </td>
                    <td>
                        <select id="apiki_wp_faq_limit" name="faq_limit" style="width: 200px">
                            <option value="-1"><?php _e( 'All faqs', $apiki_wp_faq->text_domain ); ?></option>
                            <option value="5">5</option>
                            <option value="10">10</option>
                            <option value="15">15</option>
                            <option value="20">20</option>
                            <option value="25">25</option>
                            <option value="30">30</option>
                            <option value="35">35</option>
                            <option value="40">40</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td nowrap="nowrap">
                        <label for="apiki_wp_faq_orderby">
                            <?php _e( 'Order By', $apiki_wp_faq->text_domain ); ?>:
                        </label>
                    </td>
                    <td>
                        <select id="apiki_wp_faq_orderby" name="faq_orderby" style="width: 200px">
                            <option value="date"><?php _e( 'Creation date', $apiki_wp_faq->text_domain ); ?></option>
                            <option value="title"><?php _e( 'Question title', $apiki_wp_faq->text_domain ); ?></option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td nowrap="nowrap"></td>
                    <td>
                        <input type="radio" id="apiki_wp_faq_order_asc" name="faq_order" value="asc" checked="checked" /> <label for="apiki_wp_faq_order_asc" title="A-Z"><?php _e( 'Ascending', $apiki_wp_faq->text_domain ); ?></label>
                        <input type="radio" id="apiki_wp_faq_order_desc" name="faq_order" value="desc" /> <label for="apiki_wp_faq_order_desc" title="Z-A"><?php _e( 'Descending', $apiki_wp_faq->text_domain ); ?></label>
                    </td>
                </tr>
            </table>
            <div class="mceActionPanel">
                <p>
                    <div style="float: left">
                        <input type="button" id="cancel" name="cancel" value="<?php _e( "Cancel", $apiki_wp_faq->text_domain ); ?>" onclick="tinyMCEPopup.close();" />
                    </div>
                    <div style="float: right">
                        <input type="submit" id="insert" name="insert" value="<?php _e( "Insert", $apiki_wp_faq->text_domain ); ?>" onclick="insertApikiWPShortcode();" />
                    </div>
                </p>
            </div>
        </form>
    </body>
</html>