<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       #
 * @since      1.0.0
 *
 * @package    Wprem_Promotions_Manager
 * @subpackage Wprem_Promotions_Manager/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Wprem_Promotions_Manager
 * @subpackage Wprem_Promotions_Manager/admin
 * @author     Sergio Cutone <sergio.cutone@yp.ca>
 */
class Wprem_Promotions_Manager_Admin
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
     * @param      string    $plugin_name       The name of this plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct($plugin_name, $version)
    {

        $this->plugin_name = $plugin_name;
        $this->version = $version;

    }

    /**
     * Register the stylesheets for the admin area.
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

        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/wprem-promotions-manager-admin.css', array(), $this->version, 'all');

    }

    /**
     * Register the JavaScript for the admin area.
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

        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/wprem-promotions-manager-admin.js', array('jquery'), $this->version, false);

    }

    public function add_preview_metabox()
    {
        add_meta_box(
            'wprem_promo_preview',
            esc_html__('Promotion Preview', 'example'),
            'wprem_promo_preview_callback',
            'wprem_promotions',
            'advanced',
            'high'
            //$callback_args = null,
        );
        function wprem_promo_preview_callback($post)
        {
            $post_id = get_the_ID();
            $myPost = get_post($post_id);
            $post_created = new DateTime($myPost->post_date_gmt);
            $post_modified = new DateTime($myPost->post_modified_gmt);
            if (abs($post_created->diff($post_modified)->s) <= 1 || get_post_status($post_id) == 'draft') {
                ?>
<div class="wprem-promotions-container">
	<div class="wprem-promotions">
		<div class="wprem-inner" style="">
			<div class="row wprem-inner-vertical">
				<div class="col-sm-4 col-img">
					<div class="wprem-feature-image text-left">
                        <?php
if (has_post_thumbnail()) {
                    the_post_thumbnail();
                }
                ?>
                    </div>
				</div>
				<div class="col-sm-8 col-promo">
					<div class="wprem-content text-left">
						<div class="wprem-h1">Headline Text</div>
						<div class="wprem-h2">Secondary Text</div>
						<div class="wprem-p">Additional Text</div>
					</div>
                    <div class="wprem-txt-link" style="display: none;">
                        <a href="#" style=" font-weight:bold;">Link Text</a>
                    </div>
					<div class="wprem-btn" style="display: none;">
						<a class="fl-button btn btn-md" href="#" style="">Button Text</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
            <?php
} else {
                echo do_shortcode('[wp_promos id="' . get_the_ID() . '"]');
            }
        }
    }

    public function remove_yoast_metabox()
    {
        remove_meta_box('wpseo_meta', WPREM_PROMOTIONS_CUSTOM_POST_TYPE, 'normal');
    }

    public function add_button()
    {
        echo '<a href="#TB_inline?width=480&height=500&inlineId=wp_promo_shortcode" class="button thickbox wp_doin_media_link" id="add_div_shortcode" title="Promotions Options"><span class="dashicons dashicons-smiley alt="Promotions Options"></span></a>';
    }

    public function wp_doin_mce_popup()
    {
        ?>
		<div id="wp_promo_shortcode" style="display:none;">
			<div class="wrap wp_doin_shortcode">
				<div>
					<div style="padding:10px">
						<h3 style="color:#5A5A5A!important; font-family:Georgia,Times New Roman,Times,serif!important; font-size:1.8em!important; font-weight:normal!important;">Promotion Shortcode</h3>
						<p><strong>Single Promotion</strong> will override <strong>Single Random Promotion</strong></p>
						<hr />
						<div class="field-container">
							<div class="label-desc">
								<?php
$args = array(
            'post_type' => WPREM_PROMOTIONS_CUSTOM_POST_TYPE,
        );
        echo '<select id="wp_promo_id"><option value="">- Select Single Promotion -</option>';
        $promotions = get_posts($args);
        foreach ($promotions as $promotion):
            setup_postdata($promotion);
            echo "<option value=" . $promotion->ID . ">" . $promotion->post_title . "</option>";
        endforeach;
        wp_reset_postdata();
        echo "</select>";
        ?>
							</div>
						</div>
						<div class="field-container">
							<div class="label-desc">
								<input type="checkbox" name="random" id="wp_promo_rnd" value="true" />
								<label for="wp_promo_rnd"><strong>Single Random Promotion</strong></label>
							</div>
						</div>
						<div class="field-container">
							<div class="label-desc">
								<input type="checkbox" name="expiry" id="wp_promo_exp" value="true" />
								<label for="wp_promo_exp"><strong>Show Expiry Date</strong></label>
							</div>
						</div>
					</div>
					<hr />
					<div style="padding:15px;">
						<input type="button" class="button-primary" value="Insert Promotion" id="promo-insert" />
						&nbsp;&nbsp;&nbsp;<a class="button" href="#" onclick="tb_remove(); return false;">Cancel</a>
					</div>
				</div>
			</div>
		</div>
		<?php
}

    public function content_types()
    {
        $labels = array(
            'name' => _x('Promotions', 'Post type general name', 'textdomain'),
            'singular_name' => _x('Promotion', 'Post type singular name', 'textdomain'),
            'menu_name' => _x('Promotions', 'Admin Menu text', 'textdomain'),
            'name_admin_bar' => _x('Promotion', 'Add New on Toolbar', 'textdomain'),
            'add_new' => __('Add New', 'textdomain'),
            'add_new_item' => __('Add New Promotion', 'textdomain'),
            'new_item' => __('New Promotion', 'textdomain'),
            'edit_item' => __('Edit Promotion', 'textdomain'),
            'view_item' => __('View Promotion', 'textdomain'),
            'all_items' => __('All Promotions', 'textdomain'),
            'search_items' => __('Search Promotions', 'textdomain'),
            'parent_item_colon' => __('Parent Promotions:', 'textdomain'),
            'not_found' => __('No Promotions found.', 'textdomain'),
            'not_found_in_trash' => __('No Promotions found in Trash.', 'textdomain'),
            'featured_image' => _x('Promotion Cover Image', 'Overrides the “Featured Image” phrase for this post type. Added in 4.3', 'textdomain'),
            'set_featured_image' => _x('Set cover image', 'Overrides the “Set featured image” phrase for this post type. Added in 4.3', 'textdomain'),
            'remove_featured_image' => _x('Remove cover image', 'Overrides the “Remove featured image” phrase for this post type. Added in 4.3', 'textdomain'),
            'use_featured_image' => _x('Use as cover image', 'Overrides the “Use as featured image” phrase for this post type. Added in 4.3', 'textdomain'),
            'archives' => _x('Promotion archives', 'The post type archive label used in nav menus. Default “Post Archives”. Added in 4.4', 'textdomain'),
            'insert_into_item' => _x('Insert into Promotion', 'Overrides the “Insert into post”/”Insert into page” phrase (used when inserting media into a post). Added in 4.4', 'textdomain'),
            'uploaded_to_this_item' => _x('Uploaded to this Promotion', 'Overrides the “Uploaded to this post”/”Uploaded to this page” phrase (used when viewing media attached to a post). Added in 4.4', 'textdomain'),
            'filter_items_list' => _x('Filter Promotions list', 'Screen reader text for the filter links heading on the post type listing screen. Default “Filter posts list”/”Filter pages list”. Added in 4.4', 'textdomain'),
            'items_list_navigation' => _x('Promotions list navigation', 'Screen reader text for the pagination heading on the post type listing screen. Default “Posts list navigation”/”Pages list navigation”. Added in 4.4', 'textdomain'),
            'items_list' => _x('Promotions list', 'Screen reader text for the items list heading on the post type listing screen. Default “Posts list”/”Pages list”. Added in 4.4', 'textdomain'),
        );

        $addons_arr = $tab_arr = $addon_services = $addon_locations = array();

        if (defined('WPREM_SERVICES_CUSTOM_POST_TYPE')) {
            $addon_services = array(
                'id' => '_data_post_services',
                'type' => 'post_checkboxes',
                'label' => 'Add To Service Pages',
                'args' => array(
                    'post_type' => WPREM_SERVICES_CUSTOM_POST_TYPE,
                    'orderby' => 'title',
                    'order' => 'ASC',
                ),
            );
            array_push($addons_arr, $addon_services);
        }

        if (defined('WPREM_LOCATIONS_CUSTOM_POST_TYPE')) {
            $addon_locations = array(
                'id' => '_data_post_locations',
                'type' => 'post_checkboxes',
                'label' => 'Add To Location Pages',
                'args' => array(
                    'post_type' => WPREM_LOCATIONS_CUSTOM_POST_TYPE,
                    'orderby' => 'title',
                    'order' => 'ASC',
                ),
            );
            array_push($addons_arr, $addon_locations);
        }

        $panels_arr = array(
            array(
                'id' => '_data_tabs_panel_3',
                'title' => 'Basic Text',
                'type' => 'accordion',
                'fields' => array(
                    array(
                        'id' => '_data_select_text_position', 'type' => 'select', 'label' => 'Text Alignment',
                        'options' => array('left' => 'Left', 'center' => 'Center', 'right' => 'Right',
                        ),
                    ),
                    array(
                        'id' => '_data_accordion',
                        'type' => 'accordion',
                        'panels' => array(
                            array(
                                'id' => '_data_accordion_panel_1',
                                'title' => 'Headline Text',
                                'fields' => array(
                                    // Fields here
                                    array(
                                        'id' => '_data_text_headline', 'type' => 'text', 'label' => 'Headline Text',
                                    ),
                                    array(
                                        'id' => '_data_text_headline_size', 'type' => 'text', 'label' => 'Headline Size', 'description' => 'Eg: 16px',
                                    ),
                                    array(
                                        'id' => '_data_text_headline_colour', 'label' => 'Headline Text Colour', 'type' => 'color',
                                    ),
                                    array(
                                        'id' => '_data_text_headline_lineheight', 'type' => 'text', 'label' => 'Headline Line Height',
                                    ),
                                    array(
                                        'id' => '_data_text_headline_margin', 'type' => 'text', 'label' => 'Headline Margins', 'description' => '(T / R / B / L)',
                                    ),
                                    array(
                                        'id' => '_data_text_headline_padding', 'type' => 'text', 'label' => 'Headline Padding', 'description' => '(T / R / B / L)',
                                    ),
                                    array(
                                        'id' => '_data_text_headline_class', 'type' => 'text', 'label' => 'Headline CSS Class',
                                    ),
                                ),
                            ),
                            array(
                                'id' => '_data_accordion_panel_2',
                                'title' => 'Secondary Text',
                                'fields' => array(
                                    // Fields here
                                    array(
                                        'id' => '_data_text_secondary', 'type' => 'text', 'label' => 'Secondary Text',
                                    ),
                                    array(
                                        'id' => '_data_text_secondary_size', 'type' => 'text', 'label' => 'Secondary Size', 'description' => 'Eg: 16px',
                                    ),
                                    array(
                                        'id' => '_data_text_secondary_colour', 'label' => 'Secondary Text Colour', 'type' => 'color',
                                    ),
                                    array(
                                        'id' => '_data_text_secondary_lineheight', 'type' => 'text', 'label' => 'Secondary Line Height',
                                    ),
                                    array(
                                        'id' => '_data_text_secondary_margin', 'type' => 'text', 'label' => 'Secondary Margins', 'description' => '(T / R / B / L)',
                                    ),
                                    array(
                                        'id' => '_data_text_secondary_padding', 'type' => 'text', 'label' => 'Secondary Padding', 'description' => '(T / R / B / L)',
                                    ),
                                    array(
                                        'id' => '_data_text_secondary_class', 'type' => 'text', 'label' => 'Secondary CSS Class',
                                    ),
                                ),
                            ),
                            array(
                                'id' => '_data_accordion_panel_3',
                                'title' => 'Additional Text (Optional)',
                                'fields' => array(
                                    // Fields here
                                    array(
                                        'id' => '_data_text_content', 'type' => 'wysiwyg', 'label' => 'Content Text', 'description' => '(Optional)',
                                    ),
                                    array(
                                        'id' => '_data_text_content_size', 'type' => 'text', 'label' => 'Content Text Size', 'description' => 'Eg: 16px',
                                    ),
                                    array(
                                        'id' => '_data_content_colour', 'label' => 'Content Text Colour', 'type' => 'color',
                                    ),
                                    array(
                                        'id' => '_data_text_content_lineheight', 'type' => 'text', 'label' => 'Content Line Height',
                                    ),
                                    array(
                                        'id' => '_data_text_content_margin', 'type' => 'text', 'label' => 'Content Margins', 'description' => '(T / R / B / L)',
                                    ),
                                    array(
                                        'id' => '_data_text_content_padding', 'type' => 'text', 'label' => 'Content Padding', 'description' => '(T / R / B / L)',
                                    ),
                                    array(
                                        'id' => '_data_text_content_class', 'type' => 'text', 'label' => 'Content CSS Class',
                                    ),
                                ),
                            ),
                            array(
                                'id' => '_data_accordion_panel_4',
                                'title' => 'Cover Image Settings',
                                'fields' => array(
                                    // Fields here
                                    array(
                                        'id' => '_data_select_cover_img_position', 'type' => 'select', 'label' => 'Cover Image Position',
                                        'options' => array('center' => 'Center', 'left' => 'Left', 'right' => 'Right',
                                        ),
                                    ),
                                    array(
                                        'id' => '_data_select_cover_img_align', 'type' => 'select', 'label' => 'Cover Image Alignment',
                                        'options' => array('left' => 'Left', 'center' => 'Center', 'right' => 'Right',
                                        ),
                                    ),
                                ),
                            ),
                        ),
                    ),
                ),
            ),
            array(
                'id' => '_data_tabs_panel_2',
                'title' => 'Link Settings',
                'fields' => array(
                    array(
                        'id' => '_data_accordion',
                        'type' => 'accordion',
                        'panels' => array(
                            array(
                                'id' => '_data_accordion_panel_1',
                                'title' => 'Text Link Settings',
                                'fields' => array(
                                    // Fields here
                                    array(
                                        'id' => '_data_linktxt_io', 'label' => 'Enable', 'type' => 'checkbox',
                                    ),
                                    array(
                                        'id' => '_data_linktxt_text', 'label' => 'Link Text', 'type' => 'text',
                                    ),
                                    array(
                                        'id' => '_data_linktxt_text_colour', 'label' => 'Text Colour', 'type' => 'color',
                                    ),
                                    array(
                                        'id' => '_data_linktxt_text_size', 'type' => 'text', 'label' => 'Text Size', 'description' => 'Eg: 16px',
                                    ),
                                    array(
                                        'id' => '_data_linktxt_text_bold', 'type' => 'select', 'label' => 'Text Weight',
                                        'options' => array('normal' => 'Normal', 'bold' => 'Bold',
                                        ),
                                    ),
                                    array(
                                        'id' => '_data_linktxt_position', 'type' => 'select', 'label' => 'Text Position',
                                        'options' => array('left' => 'Left', 'center' => 'Center', 'right' => 'Right',
                                        ),
                                    ),
                                    array(
                                        'id' => '_data_linktxt_link_post_select', 'type' => 'post_select', 'label' => 'Page Link',
                                        'args' => array(
                                            'post_type' => 'page',
                                        ),
                                    ),
                                    array(
                                        'id' => '_data_linktxt_link_io', 'label' => 'Enable External Link', 'type' => 'checkbox',
                                    ),
                                    array(
                                        'id' => '_data_linktxt_link', 'type' => 'text', 'label' => 'External URL',
                                    ),
                                ),
                            ),
                            array(
                                'id' => '_data_accordion_panel_2',
                                'title' => 'Button Settings',
                                'fields' => array(
                                    // Fields here
                                    array(
                                        'id' => '_data_button_io', 'label' => 'Enable', 'type' => 'checkbox',
                                    ),
                                    array(
                                        'id' => '_data_button_text', 'label' => 'Button Text', 'type' => 'text',
                                    ),
                                    array(
                                        'id' => '_data_button_size', 'type' => 'select', 'label' => 'Button Size',
                                        'options' => array('sm' => 'Small', 'md' => 'Medium', 'lg' => 'Large',
                                        ),
                                    ),
                                    array(
                                        'id' => '_data_button_text_colour', 'label' => 'Button Text Colour', 'type' => 'color',
                                    ),
                                    array(
                                        'id' => '_data_select_text_bold', 'type' => 'select', 'label' => 'Button Text Weight',
                                        'options' => array('normal' => 'Normal', 'bold' => 'Bold',
                                        ),
                                    ),
                                    array(
                                        'id' => '_data_select_position', 'type' => 'select', 'label' => 'Button Position',
                                        'options' => array('left' => 'Left', 'center' => 'Center', 'right' => 'Right',
                                        ),
                                    ),
                                    array(
                                        'id' => '_data_button_bg', 'label' => 'Button Background', 'type' => 'color',
                                    ),
                                    array(
                                        'id' => '_data_button_radius', 'type' => 'checkbox', 'label' => 'Disable Rounded Corners',
                                    ),
                                    array(
                                        'id' => '_data_post_select', 'type' => 'post_select', 'label' => 'Page Link',
                                        'args' => array(
                                            'post_type' => 'page',
                                        ),
                                    ),
                                    array(
                                        'id' => '_data_ext_button_io', 'label' => 'Enable External Link', 'type' => 'checkbox',
                                    ),
                                    array(
                                        'id' => '_data_button_link', 'type' => 'text', 'label' => 'External URL',
                                    ),
                                ),
                            ),
                        ),
                    ),
                ),
            ),
            array(
                'id' => '_data_tabs_panel_1',
                'title' => 'Box Style',
                'fields' => array(
                    array(
                        'id' => '_data_bg_colour', 'label' => 'Background Colour', 'type' => 'color',
                    ),
                    array(
                        'id' => '_data_bg_image', 'type' => 'image', 'label' => 'Background Image',
                    ),
                    array(
                        'id' => '_data_select_bg_position', 'type' => 'select', 'label' => 'Background Position',
                        'options' => array('center' => 'Center', 'top' => 'Top', 'bottom' => 'Bottom',
                        ),
                    ),
                    array(
                        'id' => '_data_select_bg_image', 'type' => 'select', 'label' => 'Background Attachment',
                        'options' => array('scroll' => 'Scroll', 'fixed' => 'Fixed',
                        ),
                    ),
                    array(
                        'id' => '_data_select_border_size', 'type' => 'select', 'label' => 'Border Size',
                        'options' => array('0' => 'No Border', '1px' => 'Small', '3px' => 'Medium', '5px' => 'Large', '10px' => 'Extra Large',
                        ),
                    ),
                    array(
                        'id' => '_data_border_colour', 'label' => 'Border Colour', 'type' => 'color',
                    ),
                    array(
                        'id' => '_data_border_radius', 'label' => 'Border Radius', 'type' => 'text',
                    ),
                    array(
                        'id' => '_data_margin', 'label' => 'Box Margin', 'type' => 'text', 'description' => '(T / R / B / L)',
                    ),
                    array(
                        'id' => '_data_padding', 'label' => 'Box Padding', 'type' => 'text', 'description' => '(T / R / B / L)',
                    ),
                    array(
                        'id' => '_data_class', 'label' => 'Box CSS Class', 'type' => 'text',
                    ),
                ),
            ),
            array(
                'id' => '_data_tabs_panel_7',
                'title' => 'Custom CSS',
                'fields' => array(
                    array(
                        'id' => '_data_css', 'type' => 'textarea', 'label' => 'Inline CSS', 'description' => 'Custom Inline CSS',
                    ),
                ),
            ),
            array(
                'id' => '_data_tabs_panel_5',
                'title' => 'Custom HTML',
                'fields' => array(
                    array(
                        'id' => '_data_text_wysiwyg', 'type' => 'wysiwyg', 'label' => 'Custom HTML', 'description' => 'PLEASE NOTE: This overrides Text and Button Settings',
                    ),
                ),
            ),
        );

        if (count($addons_arr) > 0) {
            $tab_arr = array_push($panels_arr, array('id' => '_data_tabs_panel_4', 'title' => 'Extra', 'fields' => $addons_arr));
        }

        $exludefromsearch = (esc_attr(get_option('wprem_searchable_wprem-promotions-manager')) === "1") ? false : true;
        $args = array(
            'exclude_from_search' => $exludefromsearch,
            'public' => false,
            'show_ui' => true,
            'labels' => $labels,
            "menu_icon" => "dashicons-smiley",
            "has_archive" => true,
            'supports' => array('title', 'thumbnail', 'post-formats'),
            'rewrite' => array('with_front' => false),
        );
        $book = register_cuztom_post_type(WPREM_PROMOTIONS_CUSTOM_POST_TYPE, $args);

        //Register a post status expired
        register_post_status('expired', array(
            'label' => _x('Expired ', 'post status label', 'bznrd'),
            'public' => true,
            'label_count' => _n_noop('Expired <span class="count">(%s)</span>', 'Expired <span class="count">(%s)</span>', 'plugin-domain'),
            'post_type' => array(WPREM_PROMOTIONS_CUSTOM_POST_TYPE), // Define one or more post types the status can be applied to.
            'show_in_admin_all_list' => true,
            'show_in_admin_status_list' => true,
            'show_in_metabox_dropdown' => true,
            'show_in_inline_dropdown' => true,
        ));
        //In the custom post edit screen's publish metabox, adding the custom post status in the dropdown and change the "Save Draft" button label if the selected post status is "expired"
        add_action('admin_footer-post.php', function () {
            global $post;
            $complete = '';
            $label = '';
            if ($post->post_type == WPREM_PROMOTIONS_CUSTOM_POST_TYPE) {
                if ($post->post_status == 'expired') {
                    $complete = ' selected=\"selected\"';
                    $label = 'Expired';
                }
                echo '<script type="text/javascript">jQuery(document).ready(function($){
                    $("select#post_status").append("<option value=\"expired\"' . $complete . '>Expired</option>");
                    if( "{$post->post_status}" == "expired" ){
                        $("span#post-status-display").html("' . $label . '");
                        $("input#save-post").val("Save Expired");
                    }
                    var jSelect = $("select#post_status");
                    $("a.save-post-status").on("click", function(){
                        if( jSelect.val() == "expired" ){
                            $("input#save-post").val("Save Expired");
                        }
                    });
                });</script>';
            }
        });
        //Add the custom post status in the quick edit screen of the custom post admin grid
        add_action('admin_footer-edit.php', function () {
            global $post;
            if ($post && $post->post_status == 'wprem_promotions') {
                echo "<script>jQuery(document).ready( function() {jQuery( 'select[name=\"_status\"]' ).append( '<option value=\"expired\">Expired</option>' );});</script>";
            }
        });
        //Display the custom post status total in the custom post admin grid
        add_filter('display_post_states', function ($statuses) {
            global $post;
            if ($post->post_type == 'wprem_promotions') {
                if (get_query_var('post_status') != 'expired') {
                    // not for pages with all posts of this status
                    if ($post->post_status == 'expired') {
                        return array('Expired');
                    }
                }
            }
            return $statuses;
        });

        // Add Expiry Date Column to Post Type
        add_filter('manage_wprem_promotions_posts_columns', 'set_custom_edit_wprem_promotions_columns');
        function set_custom_edit_wprem_promotions_columns($columns)
        {
            $columns['expiry_date'] = __('Expiry Date', 'your_text_domain');
            return $columns;
        }
        // Add the data to the Expiry Date Column
        add_action('manage_wprem_promotions_posts_custom_column', 'custom_wprem_promotions_column', 10, 2);
        function custom_wprem_promotions_column($column, $post_id)
        {
            switch ($column) {
                case 'expiry_date':
                    $timestamp = get_post_meta($post_id, '_data_expiry_date', true);
                    $expire = ($timestamp <= current_time('timestamp', $gmt = 0)) ? 'Expired On' : 'Expires On';
                    echo (!empty($timestamp)) ? $expire . '<br/>' . '<abbr title="' . gmdate("Y/m/d h:i:s a", $timestamp) . '">' . gmdate("Y/m/d", $timestamp) . '</abbr>' : '';
                    break;
            }
        }

        //Change Post Status to Expire if Promotion is Expired
        add_action('admin_init', 'expire_promotions');
        function expire_promotions()
        {
            $args = array(
                'post_type' => 'wprem_promotions',
                'post_status' => 'publish',
                'posts_per_page' => -1,
            );
            $published_posts = get_posts($args);
            $currentdate = current_time('timestamp', $gmt = 0);
            foreach ($published_posts as $post_to_draft) {
                $expirydate = get_post_meta($post_to_draft->ID, '_data_expiry_date', true);
                if (!empty($expirydate) && $expirydate <= $currentdate) {
                    $query = array(
                        'ID' => $post_to_draft->ID,
                        'post_status' => 'expired',
                    );
                    wp_update_post($query, true);
                }
            }
        }

        $box = register_cuztom_meta_box(
            'data',
            WPREM_PROMOTIONS_CUSTOM_POST_TYPE,
            array(
                'title' => 'Promotion Settings',
                'fields' => array(
                    array(
                        'id' => '_data_expiry_date',
                        'type' => 'date',
                        'label' => 'Expiry Date',
                        'html_attributes' => array('autocomplete' => 'off'),
                        'args' => array(
                            'date_format' => 'yy-mm-dd',
                        ),
                    ),
                    array(
                        'id' => '_data_tabs',
                        'type' => 'tabs',
                        'panels' => $panels_arr,
                    ),
                ),
            ),
            'advanced',
            'low'
        );
    }

}
