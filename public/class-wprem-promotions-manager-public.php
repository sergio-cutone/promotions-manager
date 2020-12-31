<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       #
 * @since      1.0.0
 *
 * @package    Wprem_Promotions_Manager
 * @subpackage Wprem_Promotions_Manager/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Wprem_Promotions_Manager
 * @subpackage Wprem_Promotions_Manager/public
 * @author     Sergio Cutone <sergio.cutone@yp.ca>
 */
class Wprem_Promotions_Manager_Public
{

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $plugin_name    The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string    $plugin_name       The name of the plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct($plugin_name, $version)
    {

        $this->plugin_name = $plugin_name;
        $this->version = $version;

    }

    /**
     * Register the stylesheets for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_styles()
    {
        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Wprem_Promotions_Manager_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Wprem_Promotions_Manager_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */
        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/wprem-promotions-manager-public.css', array(), $this->version, 'all');
    }

    /**
     * Register the JavaScript for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts()
    {
        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Wprem_Promotions_Manager_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Wprem_Promotions_Manager_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */
        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/wprem-promotions-manager-public-min.js', array('jquery'), $this->version, false);
    }

    // [promo id=xx rnd=true]
    public function promos_shortcode($atts)
    {
        ob_start();

        global $post;
        $a = shortcode_atts(array(
            'rnd' => 0, // Show 1 Random promo? (Boolean)
            'id' => 0, // Single ID of promo
            'exp' => 0, // Show epiry date (Boolean)
        ), $atts);

        $promo['orderby'] = 'date';
        $promo['ppp'] = 999;

        // If Random and No ID (Single Promo Overrides Random)
        if ($a['rnd'] && !$a['id']) {
            $promo['orderby'] = 'rand';
            $promo['ppp'] = 1;
        }

        $args = array(
            'post_status' => array( 'publish' ),
            'post_type' => WPREM_PROMOTIONS_CUSTOM_POST_TYPE,
            'order' => 'DESC',
            'orderby' => $promo['orderby'],
            'posts_per_page' => $promo['ppp'],
        );

        // If ID is selected then update array Post Per Page = 1 & Post ID
        if ($a['id']) {
            $args['p'] = $a['id'];
            $args['posts_per_page'] = 1;
        }

        $promo_out = '<div class="' . WPREM_PROMOTIONS_CLASS . '-container"><div class="' . WPREM_PROMOTIONS_CLASS . '">';
        $promo_query = new WP_Query($args);
        while ($promo_query->have_posts()) {
            $promo_query->the_post();
            $value = get_post_custom(get_the_ID());
            $today = strtotime("today midnight");
            $get_expiry = get_post_meta($post->ID, '_data_expiry_date', true) ? ((float) get_post_meta($post->ID, '_data_expiry_date', true)) : false;
            $expiry_date = new DateTime();
            $expiry_date->setTimestamp($get_expiry);
            // don't show promo if it expired
            if (strtotime($expiry_date->format('l, F j o')) >= $today || !$get_expiry) {
                
                $feature_align = !empty($value['_data_select_cover_img_align'][0]) ? ' text-' . $value['_data_select_cover_img_align'][0] : '';
                $feature_img = !empty( has_post_thumbnail() ) ? '<div class="wprem-feature-image' . $feature_align . '"><img src="' . get_the_post_thumbnail_url() . '"/></div>' : '';
                $cover_position = !empty($value['_data_select_cover_img_position'][0]) ? $value['_data_select_cover_img_position'][0] : '';
                $text_position = !empty($value["_data_select_text_position"][0]) ? $value["_data_select_text_position"][0] : '';
                
                $text_headline = !empty($value['_data_text_headline'][0]) ? $value['_data_text_headline'][0] : '';
                $text_headline_size = !empty($value["_data_text_headline_size"][0]) ? 'font-size:' . $value["_data_text_headline_size"][0] . ';' : '';
                $text_headline_color = !empty($value["_data_text_headline_colour"][0]) ? ' color:' . $value["_data_text_headline_colour"][0] . ';' : '';
                $text_headline_lineheight = !empty($value["_data_text_headline_lineheight"][0]) ? ' line-height:' . $value["_data_text_headline_lineheight"][0] . ';' : '';
                $text_headline_margin = !empty($value["_data_text_headline_margin"][0]) ? ' margin:' . $value["_data_text_headline_margin"][0] . ';' : '' ;
                $text_headline_padding = !empty($value["_data_text_headline_padding"][0]) ? ' padding:' . $value["_data_text_headline_padding"][0] . ';' : '' ;
                $text_headline_class = !empty($value["_data_text_headline_class"][0]) ? ' ' . $value["_data_text_headline_class"][0] : '';
                $headline = !empty($value['_data_text_headline'][0]) ? '<div class="wprem-h1' . $text_headline_class . '" style="' . $text_headline_size . $text_headline_color . $text_headline_lineheight . $text_headline_margin . $text_headline_padding . '">' . $text_headline . '</div>' : '';

                $text_secondary = !empty($value['_data_text_secondary'][0]) ? $value['_data_text_secondary'][0] : '';
                $text_secondary_size = !empty($value["_data_text_secondary_size"][0]) ? 'font-size:' . $value["_data_text_secondary_size"][0] . ';' : '';
                $text_secondary_color = !empty($value["_data_text_secondary_colour"][0]) ? ' color:' . $value["_data_text_secondary_colour"][0] . ';' : '';
                $text_secondary_lineheight = !empty($value["_data_text_secondary_lineheight"][0]) ? ' line-height:' . $value["_data_text_secondary_lineheight"][0] . ';' : '';
                $text_secondary_margin = !empty($value["_data_text_secondary_margin"][0]) ? ' margin:' . $value["_data_text_secondary_margin"][0] . ';' : '' ;
                $text_secondary_padding = !empty($value["_data_text_secondary_padding"][0]) ? ' padding:' . $value["_data_text_secondary_padding"][0] . ';' : '' ;
                $text_secondary_class = !empty($value['_data_text_secondary_class'][0]) ? ' ' . $value['_data_text_secondary_class'][0] : '';
                $secondary = !empty($value["_data_text_secondary"][0]) ? '<div class="wprem-h2' . $text_secondary_class . '" style="' . $text_secondary_size . $text_secondary_color . $text_secondary_lineheight . $text_secondary_margin . $text_secondary_padding . '">' . $text_secondary . '</div>' : '';

                $text_content = !empty($value['_data_text_content'][0]) ? $value['_data_text_content'][0] : '';
                $text_content_size = !empty($value['_data_text_content_size'][0]) ? 'font-size:' . $value['_data_text_content_size'][0] . ';' : '';
                $content_color = !empty($value['_data_content_colour'][0]) ? ' color:' . $value['_data_content_colour'][0] . ';' : '';
                $text_content_lineheight = !empty($value["_data_text_content_lineheight"][0]) ? ' line-height:' . $value["_data_text_content_lineheight"][0] . ';' : '' ;
                $text_content_margin = !empty($value["_data_text_content_margin"][0]) ? ' margin:' . $value["_data_text_content_margin"][0] . ';' : '' ;
                $text_content_padding = !empty($value["_data_text_content_padding"][0]) ? ' padding:' . $value["_data_text_content_padding"][0] . ';' : '' ;
                $text_content_class = !empty($value["_data_text_content_class"][0]) ? ' ' . $value["_data_text_content_class"][0] : '' ;
                $contentTxt = !empty($value["_data_text_content"]) ? '<div class="wprem-p' . $text_content_class . '" style="' . $text_content_size . $content_color . $text_content_lineheight . $text_content_margin . $text_content_padding . '">' . $text_content . '</div>' : '';

                $text = '<div class="wprem-content text-' . $text_position . '">' . $headline . $secondary . $contentTxt . '</div>';

                $txtlink_enabled = (!empty($value['_data_linktxt_io'][0]) == 'on') ? true : false;
                $textlink = $content = '';
                if ($txtlink_enabled) {
                    $txtlink_size = !empty($value['_data_linktxt_text_size'][0]) ? 'font-size:' . $value['_data_linktxt_text_size'][0] . ';' : '' ;
                    $txtlink_weight = !empty($value['_data_linktxt_text_bold'][0]) ? ' font-weight:' . $value['_data_linktxt_text_bold'][0] . ';' : '' ;
                    $txtlink_color = !empty($value['_data_linktxt_text_colour'][0]) ? ' color:' . $value['_data_linktxt_text_colour'][0] . ';' : '' ;
                    $txtlink_ext_enabled = (!empty($value['_data_linktxt_link_io'][0]) == "on") ? true : false;
                    if ($txtlink_ext_enabled) {
                        $txtlink = !empty($value['_data_linktxt_link'][0]) ? $value['_data_linktxt_link'][0]: '#';
                        $txtext = ' target="_blank"';
                    } else {
                        $txtlink = !empty($value['_data_linktxt_link_post_select'][0]) ? '/' . get_post($value['_data_linktxt_link_post_select'][0])->post_name : '#';
                        $txtext = '';
                    }
                    $txtlink_position = !empty($value['_data_linktxt_position'][0]) ? $value['_data_linktxt_position'][0] : '';
                    $txtlink_out = '<a href="' . $txtlink . '" style="' . $txtlink_size . $txtlink_weight . $txtlink_color . '"' . $txtext . '>' . $value['_data_linktxt_text'][0] . '</a>';
                    $textlink = '<div class="wprem-txt-link text-' . $txtlink_position . '">' . $txtlink_out . '</div>';
                }

                $button_enabled = (!empty($value['_data_button_io'][0]) == "on") ? true : false;
                $button = $content = '';
                if ($button_enabled) {
                    $fontweight = !empty($value['_data_select_text_bold'][0]) ? 'font-weight:' . $value['_data_select_text_bold'][0] . ';' : '';
                    $btnsize = !empty($value['_data_button_size'][0]) ? '-' . $value['_data_button_size'][0] : '';
                    $bg = !empty($value['_data_button_bg'][0]) ? 'background:' . $value['_data_button_bg'][0] . ';' : '';
                    $color = !empty($value['_data_button_text_colour'][0]) ? 'color:' . $value['_data_button_text_colour'][0] . ';' : '';
                    $button_radius = (!empty($value['_data_button_radius'][0]) == "on") ? 'border-radius:0;' : '';
                    $btn_position = !empty($value['_data_select_position'][0]) ? $value['_data_select_position'][0] : '';
                    $button_ext_enabled = (!empty($value['_data_ext_button_io'][0]) == "on") ? true : false;
                    if ($button_ext_enabled) {
                        $btnlink = !empty($value['_data_button_link'][0]) ? $value['_data_button_link'][0] : '#';
                        $btnext = ' target="_blank"';
                    } else {
                        $btnlink = !empty($value['_data_post_select'][0]) ? '/' . get_post($value['_data_post_select'][0])->post_name : '#';
                        $btnext = '';
                    }
                    $button_out = '<a href="' . $btnlink . '" class="fl-button btn btn' . $btnsize . '" style="display:inline-block;margin-top:15px;white-space:pre-wrap;' . $fontweight . $bg . $color . $button_radius . '"' . $btnext . '>' . $value['_data_button_text'][0] . '</a>';
                    $button = '<div class="wprem-btn text-' . $btn_position . '">' . $button_out . '</div>';
                }

                $bg_colour = !empty($value['_data_bg_colour'][0]) ? 'background-color:' . $value['_data_bg_colour'][0] . ';' : '';
                $bg_img_data = get_post_meta($post->ID, '_data_bg_image', true);

                $bg_position = !empty($value['_data_select_bg_position'][0]) ? $value['_data_select_bg_position'][0] : '';

                $bg_img = !empty(get_post_meta($post->ID, '_data_bg_image', true)) ? 'background: url(' . wp_get_attachment_url($bg_img_data, 'full') . ') ' . $bg_position . '; background-size:cover; background-attachment:' . $value['_data_select_bg_image'][0] . ';' : '';
                $border_size = !empty($value['_data_select_border_size'][0]) ? $value['_data_select_border_size'][0] : '';
                $border_colour = !empty($value['_data_border_colour'][0]) ? ' ' . $value['_data_border_colour'][0] : '';
                $border = ($border_size != '' || $border_colour != '') ? 'border:' . $border_size . $border_colour . ' solid;' : '';
                $radius = !empty(get_post_meta($post->ID, '_data_border_radius', true)) ? 'border-radius:' . get_post_meta($post->ID, '_data_border_radius', true) . ';' : '';
                $margin = !empty(get_post_meta($post->ID, '_data_margin', true)) ? 'margin:' . get_post_meta($post->ID, '_data_margin', true) . ';' : '';
                $padding = !empty(get_post_meta($post->ID, '_data_padding', true)) ? 'padding:' . get_post_meta($post->ID, '_data_padding', true) . ';' : '';
                $cssClass = !empty(get_post_meta($post->ID, '_data_class', true)) ? ' ' . get_post_meta($post->ID, '_data_class', true) : '';

                $customCss = !empty($value['_data_css'][0]) ? '<style>' . $value['_data_css'][0] . '</style>' : '';
                $customhtml = !empty($value['_data_text_wysiwyg'][0]) ? $value['_data_text_wysiwyg'][0] : '';

                $expiry = ($a['exp'] && $expiry_date) ? '<div class="wprem-expiry">Expires: ' . $expiry_date->format('l, F j o') . '</div>' : '';

                if ($cover_position == 'left' ) {
                    $innerStacking = ' wprem-inner-horizontal';
                    $content = '<div class="col-sm-4 col-img">' . $feature_img . '</div>
                    <div class="col-sm-8 col-promo">' . $text . $textlink . $button . '</div>';
                } else if ( $cover_position == 'right' ) {
                    $innerStacking = ' wprem-inner-horizontal';
                    $content = '<div class="col-sm-8 col-promo">' . $text . $textlink . $button . '</div>
                    <div class="col-sm-4 col-img">' . $feature_img . '</div>';
                } else if ( $cover_position == 'right' && $btn_position == 'right' ) {
                    $innerStacking = ' wprem-inner-horizontal';
                    $content = '<div class="col-sm-8 col-promo">' . $text . $textlink . '</div>
                    <div class="col-sm-4 col-img">' . $feature_img . $button . '</div>';
                } else {
                    $innerStacking = ' wprem-inner-vertical';
                    $content = '<div class="col-sm-12">' . $feature_img . $text  . $textlink . $button . '</div>';
                }

                if ($customhtml) {
                    $content = '<div class="col-sm-12">' . $customhtml . '</div>';
                }

                $promo_out .= '<div class="wprem-inner' . $cssClass . '" style="' . $radius . $border . $margin . $padding . $bg_colour . $bg_img . '"><div class="row' . $innerStacking . '">' . $content . '</div>' . $expiry . $customCss . '</div>';
            }
        }
        echo $promo_out . '</div></div>';
        wp_reset_postdata();
        $out = ob_get_clean();
        return $out;
    }
}