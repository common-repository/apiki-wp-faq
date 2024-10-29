function init() {
    tinyMCEPopup.resizeToInnerSize();
}

function insertApikiWPShortcode() {

    var category_element = document.getElementById( 'apiki_wp_faq_categories' );
    var category = ( ( category_element.value != "" ) ? " category=" + category_element.value : "" ) ;

    var limit_element = document.getElementById( 'apiki_wp_faq_limit' );
    var limit = " limit=" + limit_element.value;

    var orderby_element = document.getElementById( 'apiki_wp_faq_orderby' );
    var orderby = " orderby=" + orderby_element.value;

    var form = document.getElementById( 'apiki_wp_faq_form' );
    for ( var i=0; i < form.faq_order.length; i++ ){
        if ( form.faq_order[i].checked ){
            var order = form.faq_order[i].value;
        }
    }        
    order = " order=" + order;

    var shortcode = "[apiki-wp-faq" + category + limit + orderby + order + "]";
    
    window.tinyMCE.execInstanceCommand( 'content', 'mceInsertContent', false, shortcode );
    tinyMCEPopup.editor.execCommand( 'mceRepaint' );
    tinyMCEPopup.close();
    return;
}