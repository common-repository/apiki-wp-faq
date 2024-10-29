<?php
// Avoid to load this file directly
if ( isset( $_SERVER['SCRIPT_FILENAME'] ) and basename( __FILE__ ) == basename( $_SERVER['SCRIPT_FILENAME'] ) )
    exit();

class Apiki_WP_FAQ_Widget extends WP_Widget {
    
    /**
     * Construct method
     * 
     * @global object $apiki_wp_faq Apiki WP FAQ
     * @since 1.0
     */
    public function __construct()
    {    
        global $apiki_wp_faq;
        
        $widget_ops = array(
            'classname'     => 'widget_faq',
            'description'   => __( 'Displays the FAQ', $apiki_wp_faq->text_domain )
        );
        
        parent::__construct( 'apiki-wp-faq-widget', __( 'Apiki WP FAQ', $apiki_wp_faq->text_domain ), $widget_ops );
    }
    
    /**
     * Build widget to show
     * 
     * @global object $apiki_wp_faq
     * @since 1.0
     * @param array $_args 
     * @param array $instance
     */
    public function widget( $args, $instance )
    {
        global $apiki_wp_faq;
        
        extract( $args );
        
        $title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );
        $_args = array(
            'category'  => ( $instance['category'] ) ? $instance['category'] : '',
            'limit'     => ( $instance['limit'] )    ? $instance['limit'] : '-1',
            'orderby'   => ( $instance['orderby'] )  ? $instance['orderby'] : 'date',
            'order'     => ( $instance['order'] )    ? $instance['order'] : 'desc'
        );
        
        echo $before_widget;
            
        if( !empty( $title ) )
            printf( '%s%s%s', $before_title, $title, $after_title );
        
        $apiki_wp_faq->display_faq( $_args );
        
        echo $after_widget;
    }
    
    /**
     * Build Widget form
     * 
     * @global object $apiki_wp_faq
     * @since 1.0
     * @param array $instance 
     */
    public function form( $instance )
    {
        global $apiki_wp_faq;
        
        $title      = ( $instance['title'] )    ? $instance['title'] : '';
        $category   = ( $instance['category'] ) ? $instance['category'] : '';
        $limit      = ( $instance['limit'] )    ? $instance['limit'] : '5';
        $orderby    = ( $instance['orderby'] )  ? $instance['orderby'] : 'date';
        $order      = ( $instance['order'] )    ? $instance['order'] : 'desc';
        ?>
        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title', $apiki_wp_faq->text_domain ); ?>:</label>
            <input type="text" value="<?php echo esc_attr( $title ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" id="<?php echo $this->get_field_id( 'title' ); ?>" class="widefat" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'limit' ); ?>"><?php _e( 'Number of questions to show', $apiki_wp_faq->text_domain ); ?>:</label>
            <input type="text" value="<?php echo esc_attr( $limit ); ?>" name="<?php echo $this->get_field_name( 'limit' ); ?>" id="<?php echo $this->get_field_id( 'limit' ); ?>" style="width: 37px" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'category' ); ?>"><?php _e( 'Show questions by category', $apiki_wp_faq->text_domain ); ?>:</label>
            <select id="<?php echo $this->get_field_id( 'category' ); ?>" name="<?php echo $this->get_field_name( 'category' ); ?>" class="widefat">
                <?php $apiki_wp_faq->display_dropdown_categories( $category ); ?>
            </select>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'orderby' ); ?>"><?php _e( 'Order by', $apiki_wp_faq->text_domain ); ?>:</label>
            <select id="<?php echo $this->get_field_id( 'orderby' ); ?>" name="<?php echo $this->get_field_name( 'orderby' ); ?>" class="widefat">
                <option value="date"<?php echo ( $orderby == 'date' ) ? ' selected="selected"' : ''; ?>><?php _e( 'Creation date', $apiki_wp_faq->text_domain ); ?></option>
                <option value="title"<?php echo ( $orderby == 'title' ) ? ' selected="selected"' : ''; ?>><?php _e( 'Question title', $apiki_wp_faq->text_domain ); ?></option>
            </select>
        </p>
        <p>
            <input type="radio" id="<?php echo $this->get_field_id( 'order_asc' ); ?>" name="<?php echo $this->get_field_name( 'order' ); ?>" value="asc" <?php echo ( $order == 'asc' ) ? ' checked="checked"' : ''; ?>/> <label for="<?php echo $this->get_field_id( 'order_asc' ); ?>" title="A-Z"><?php _e( 'Ascending', $apiki_wp_faq->text_domain ); ?></label>
            <input type="radio" id="<?php echo $this->get_field_id( 'order_desc' ); ?>" name="<?php echo $this->get_field_name( 'order' ); ?>" value="desc" <?php echo ( $order == 'desc' ) ? ' checked="checked"' : ''; ?>/> <label for="<?php echo $this->get_field_id( 'order_desc' ); ?>" title="Z-A"><?php _e( 'Descending', $apiki_wp_faq->text_domain ); ?></label>
        </p>
        <?php
    }
    
    /**
     * Update Widget
     * 
     * @since 1.0
     * @param array $new_instance
     * @param array $old_instance
     * @return array Instance 
     */
    public function update( $new_instance, $old_instance )
    {
        $instance['title']      = esc_html( $new_instance['title'] );
        $instance['category']   = esc_html( $new_instance['category'] );
        $instance['limit']      = esc_html( $new_instance['limit'] );
        $instance['orderby']    = esc_html( $new_instance['orderby'] );
        $instance['order']      = esc_html( $new_instance['order'] );
        
        return $instance;
    }
}