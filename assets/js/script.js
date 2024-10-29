jQuery( function() {
    APIKI_WP_FAQ.init();
} )

var APIKI_WP_FAQ = {

    init : function() {
        this.text_title_wrap();
        this.text_title_column();
    },

    text_title_wrap : function() {
        jQuery( "#titlewrap label" ).text( objectI18n.title_placeholder );
    },
    
    text_title_column : function() {
        jQuery( ".wp-list-table thead th a span" ).first().text( objectI18n.title_table_column );
        jQuery( ".wp-list-table tfoot th a span" ).first().text( objectI18n.title_table_column );
    }
};