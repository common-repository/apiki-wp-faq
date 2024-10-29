<?php
/**
 * Plugin Name: Apiki WP FAQ
 * Plugin URI: http://apps.apiki.com
 * Description: The Apiki WP FAQ plugin makes for an easy insertion of frequently asked questions on your WordPress.
 * Version: 1.0.5
 * Author: Apiki WordPress
 * Author URI: http://www.apiki.com
 */

/* Copyright 2011 Apiki WordPress

   This program is a free software; you can redistribute it and/or modify
   it under the terms of the GNU General Public License as published by
   the Free Software Foundation; either version 2 of the License, or
   (at your option) any later version.

   This program is distributed in the hope that it will be useful,
   but WITHOUT ANY WARRANTY; without even the implied warranty of
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
   GNU General Public License for more details.

   You should have received a copy of the GNU General Public License
   along with this program; if not, write to the Free Software
   Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

// Avoid to load this file directly
if ( isset( $_SERVER['SCRIPT_FILENAME'] ) and basename( __FILE__ ) == basename( $_SERVER['SCRIPT_FILENAME'] ) )
    exit();

class Apiki_WP_FAQ {
    
    /**
     * Text domain
     * 
     * @since 1.0
     * @var string Text domain 
     */
    public $text_domain = 'apiki-wp-faq';
    
    /**
     * Capability for manage this plugin
     * 
     * @since 1.0
     * @var string Capability
     */
    public $capability = 'manage_apiki_wp_faq';
    
    /**
     * Post type for FAQ
     * 
     * @since 1.0
     * @var string Post type 
     */
    public $post_type = 'apiki-wp-faq';
    
    /**
     * Category taxonomy for FAQ
     * 
     * @since 1.0
     * @var string Taxonomy
     */
    public $category_taxonomy = 'apiki-wp-faq-category';
    
    /**
     * Shortode
     * 
     * @since 1.0
     * @var string Shortcode slug
     */
    private $_shortcode = 'apiki-wp-faq';
    
    /**
     * Construct method
     * 
     * @since 1.0
     * @return void
     */
    public function __construct() 
    {
        add_action( 'activate_apiki-wp-faq/apiki-wp-faq.php', array( &$this,'install' ) ) ;
        add_action( 'init', array( &$this, 'textdomain' ) );
        add_action( 'init', array( &$this, 'create_post_type' ) );
        add_action( 'init', array( &$this, 'create_taxonomies' ) );
        add_action( 'init', array( &$this, 'create_sample_faq' ) );
        add_action( 'save_post', array( &$this, 'flush_rules' ) );
        add_action( 'publish_post', array( &$this, 'flush_rules' ) );
        add_action( 'admin_init', array( &$this, 'tinymce_button' ) );
        add_action( 'widgets_init', array( &$this, 'widget' ) );
        add_action( 'admin_print_scripts', array( &$this, 'javascript' ) );
        add_action( 'admin_print_styles', array( &$this, 'stylesheet' ) );
        add_filter( 'post_updated_messages', array( &$this, 'updated_messages' ) );
        add_shortcode( $this->_shortcode, array( &$this, 'shortcode' ) );
    }
    
    /**
     * Install method
     * 
     * @since 1.0
     * @return void
     */
    public function install()
    {
        $this->_add_capability( $this->capability, 'administrator' );
    }
    
    /**
     * Loads the plugin text domain for the plugin localization. Runs with
     * admin_init hook.
     * 
     * @since 1.0
     * @return void
     */
    public function textdomain()
    {   
        load_plugin_textdomain( $this->text_domain, false, '/apiki-wp-faq/assets/languages' );
    }
    
    /**
     * Widget
     * 
     * @since 1.0
     * @return void
     */
    public function widget()
    {
        require_once WP_PLUGIN_DIR . '/apiki-wp-faq/apiki-wp-faq-widget.php';
        
        register_widget( 'Apiki_WP_FAQ_Widget' );
    }
    
    /**
     * Create Apiki WP FAQ Post Type
     * 
     * @since 1.0
     * @return void
     */
    public function create_post_type()
    {
        register_post_type( $this->post_type, array(
            'labels' => array(
                'name'                  => __( 'FAQ', $this->text_domain ),
                'add_new'               => __( 'Add New', $this->text_domain ),
                'add_new_item'          => __( 'Add New Question', $this->text_domain ),
                'edit_item'             => __( 'Edit Question', $this->text_domain ),
                'new_item'              => __( 'New Question', $this->text_domain ),
                'view_item'             => __( 'View Question', $this->text_domain ),
                'search_items'          => __( 'Search Questions', $this->text_domain ),
                'not_found'             => __( 'No Questions found', $this->text_domain ),
                'not_found_in_trash'    => __( 'No Questions found in trash', $this->text_domain ),
                'all_items'             => __( 'All Questions', $this->text_domain )
            ),
            'public'        => true,
            'show_ui'       => true,   
            'supports'      => array( 'title', 'editor', 'thumbnail' ),
            'menu_icon'     => WP_PLUGIN_URL . '/apiki-wp-faq/assets/images/apiki-wp-faq-16.png',
            'menu_position' => null,
            'rewrite'       => array( 'slug' => 'faq' )
        ) );
    }
    
    /**
     * Create taxonomies for apiki wp faq post type
     * 
     * @since 1.0
     * @return void
     */
    public function create_taxonomies()
    {
        register_taxonomy( $this->category_taxonomy, $this->post_type,
            array(
                'hierarchical'  => true,
                'rewrite'       => false
            )
        );        
    }
    
    /**
     * Create sample FAQ
     * 
     * @since 1.0.2
     * @return int|WP_Error Question ID created or WP Error
     */
    public function create_sample_faq()
    {
        $option_name    = 'apiki_wp_faq_sample_faq';
        $option_value   = get_option( $option_name );
        
        if( $option_value and $option_value == 'created' )
            return;
        
        $faq = $this->get_faq();
        
        if( !empty( $faq ) )
            return;
        
        $faq_id = wp_insert_post( array(
            'post_title'    => __( 'Sample Question?', $this->text_domain ),
            'post_content'  => __( 'Here you can add an answer as well as add a content in posts and pages.', $this->text_domain ),
            'post_status'   => 'publish',
            'post_type'     => $this->post_type           
        ) );
        
        if( $faq_id and !is_wp_error( $faq_id ) )
            update_option( $option_name, 'created' );
        
        return $faq_id;
    }
    
    /**
     * Flush rules for faq/question when uses custom structure of permalinks
     * 
     * @since 1.0.2
     * @global object $wp_rewrite WordPress Rewrite Object
     * @return void
     */
    public function flush_rules()
    {
        global $wp_rewrite;
        
   	$wp_rewrite->flush_rules();
    }
    
    /**
     * FAQ updated messages
     * 
     * @global object $post
     * @global int $post_ID
     * @param array $messages
     * @return array 
     */
    public function updated_messages( $messages ) 
    {
        global $post, $post_ID;

        $messages[$this->post_type] = array(
            0 => '',
            1 => sprintf( __( 'Question updated. <a href="%s">View Question</a>', $this->text_domain ), esc_url( get_permalink($post_ID) ) ),
            2 => __( 'Custom field updated.', $this->text_domain ),
            3 => __( 'Custom field deleted.', $this->text_domain ),
            4 => __( 'Question updated.', $this->text_domain ),
            5 => isset( $_GET['revision'] ) ? sprintf( __( 'Question restored to revision from %s', $this->text_domain ), wp_post_revision_title( (int)$_GET['revision'], false ) ) : false,
            6 => sprintf( __( 'Question published. <a href="%s">View Question</a>', $this->text_domain ), esc_url( get_permalink($post_ID) ) ),
            7 => __( 'Question saved.', $this->text_domain ),
            8 => sprintf( __( 'Question submitted. <a target="_blank" href="%s">Preview question</a>', $this->text_domain ), esc_url( add_query_arg( 'preview', 'true', get_permalink( $post_ID ) ) ) ),
            9 => sprintf( __( 'Question scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview question</a>', $this->text_domain ), date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ), esc_url( get_permalink( $post_ID ) ) ),
            10 => sprintf( __( 'Question draft updated. <a target="_blank" href="%s">Preview question</a>', $this->text_domain ), esc_url( add_query_arg( 'preview', 'true', get_permalink( $post_ID ) ) ) ),
        );

        return $messages;
    }
    
    /**
     * Call the hooks for add the faq TinyMce button in the WordPress editor.
     * Runs with init hook.
     * 
     * @since 1.0
     * @return void
     */
    public function tinymce_button()
    {
        if ( $this->_is_faq_admin_page() ) 
            return;

        add_filter( 'mce_buttons',          array( &$this, 'tinymce_register_button') , 5 );
        add_filter( 'mce_external_plugins', array( &$this, 'tinymce_register_plugin'),  5 );
    }
    
    /**
     * Register the button in the array of buttons in the tinymce bar. Runs with
     * mce_buttons hook.
     *
     * @since 1.0
     * @param array $buttons The original array contains all buttons in tinymce
     * @return array Buttons
     */
    public function tinymce_register_button( $buttons )
    {        
        array_push( $buttons, 'separator', 'apiki_wp_faq' );
        
        return $buttons;
    }

    /**
     * Register the TinyMCE javascript plugin in the array os plugins. Runs with
     * mce_external_plugin hook.
     *
     * @since 1.0
     * @param array $plugins The original array contains all plugins
     * @return Plugins
     */
    public function tinymce_register_plugin( $plugins )
    {
        $plugins['apiki_wp_faq'] = WP_PLUGIN_URL . '/apiki-wp-faq/assets/js/mce-plugin.js';
        
        return $plugins;
    }
    
    /**
     * Enqueue javascript
     * 
     * @since 1.0
     * @return void
     */
    public function javascript()
    {
        if( $this->_is_faq_admin_page() ) :
            wp_enqueue_script( 'apiki-wp-faq-script', WP_PLUGIN_URL . '/apiki-wp-faq/assets/js/script.js', array( 'jquery' ), filemtime( WP_PLUGIN_DIR . '/apiki-wp-faq/assets/js/script.js' ), true );
            wp_localize_script( 'apiki-wp-faq-script', 'objectI18n', 
                array( 
                    'title_placeholder'  => __( 'Enter question here', $this->text_domain ),
                    'title_table_column' => __( 'Question', $this->text_domain )
                ) 
            );
        endif;
    }
    
    /**
     * Enqueue style
     * 
     * @since 1.0
     * @return void
     */
    public function stylesheet()
    {
        if( $this->_is_faq_admin_page() )
            wp_enqueue_style( 'apiki-wp-faq-stylesheet', WP_PLUGIN_URL . '/apiki-wp-faq/assets/css/style.css', null,  filemtime( WP_PLUGIN_DIR . '/apiki-wp-faq/assets/css/style.css' ) );
    }
    
    /**
     * Reads the shortcode and show FAQs
     * 
     * @since 1.0
     * @param array $args Params
     */
    public function shortcode( $args )
    {
        ob_start();
        
        echo $this->display_faq($args);
        
        $output_string = ob_get_contents();
        
        ob_end_clean();
        
        return $output_string;
    }
    
    /**
     * Show FAQ
     * 
     * @since 1.0
     * @param array $args Options
     * @return void
     */
    public function display_faq( $args = array() )
    {
        $faq = $this->get_faq( $args );
        
        $output = '<div class="apiki-wp-faq-show">';
        
        if( empty( $faq ) || is_wp_error( $faq ) ) :
            $output .= __( 'No FAQ found.', $this->text_domain );
        else :
            $output .= '<ul>';
        
            foreach( (array)$faq as $_faq )
                $output .= sprintf( '<li><a href="%1$s" title="%2$s">%2$s</a></li>', get_permalink( $_faq->ID ), $_faq->post_title );
            
            $output .= '</ul>';
        endif;
        
        $output .= '</div>';
        
        echo $output;
    }
    
    /**
     * Display dropdown categories
     * 
     * @since 1.0
     * @return void
     */
    public function display_dropdown_categories( $selected = '' )
    {
        $categories = get_terms( $this->category_taxonomy, 'hide_empty=0' );
                
        if ( !empty( $categories ) && !is_wp_error( $categories ) ) :
            printf( '<option value="">%s</option>',__( 'All categories', $this->text_domain ) );
            foreach ( (array)$categories as $category )
                printf( '<option value="%s"%s>%s</option>', $category->slug, ( $selected == $category->slug ) ? ' selected="selected"' : '', $category->name );
        else :
            printf( '<option value="">%s</option>', __( 'No category until the moment.', $this->text_domain ) );
        endif;
    }
    
    
    /**
     * Get FAQ
     * 
     * @since 1.0.2
     * @param array $args Options
     * @return type 
     */
    public function get_faq( $args = array() )
    {
        $defaults = array(
            'category'  => '',
            'limit'     => '-1',
            'orderby'   => 'date',
            'order'     => 'desc'
        );
        
        $args       = wp_parse_args( $args, $defaults );
        $faq_query  = $this->_build_faq_query( $args );
        $faq        = get_posts( $faq_query );
        
        return $faq;
    }
   
    
    /**
     * Build FAQ query
     * 
     * @since 1.0
     * @param array $args Params
     * @return string FAQ query 
     */
    private function _build_faq_query( $args )
    {
        extract( $args, EXTR_SKIP );

        $faq_query = "post_type=$this->post_type&numberposts=$limit&orderby=$orderby&order=$order&suppress_filters=0";

        if ( !empty( $category ) ) 
            $faq_query .= "&$this->category_taxonomy=$category";

        return $faq_query;        
    }
    
    /**
     * Check if is FAQ admin page
     * 
     * @global object $post Post
     * @since 1.0
     * @return bool True if is page, otherwise false 
     */
    private function _is_faq_admin_page()
    {
        global $post;
        
        if ( !is_admin() ) 
            return false;

        if ( isset( $post ) && !empty( $post ) )
            return ( $post->post_type == $this->post_type );

        if ( isset( $_GET['post_type'] ) )
            return ( $_GET['post_type'] == $this->post_type );

        if ( isset( $_GET['post'] ) )
            return ( get_post_field( 'post_type' , $_GET['post'] ) == $this->post_type );
    }
    
    /**
     * Add capability
     * 
     * @since 1.0
     * @param string $capability Capability name
     * @param string $role Role name
     */
    private function _add_capability( $capability, $role = 'administrator' )
    {
        $role = get_role( $role );
        
        if( !$role->has_cap( $capability ) )
            $role->add_cap( $capability );
    }
}

// Instance of Apiki WP FAQ
$apiki_wp_faq = new Apiki_WP_FAQ();