<?php
if (!defined('ABSPATH')) {
    die('-1');
}
/**
 * Shortcode attributes
 * @var $atts
 * Shortcode class
 * @var $this "Funiter_Categorywrap"
 */
if (!class_exists('Funiter_Shortcode_Categorywrap')) {

    class Funiter_Shortcode_Categorywrap extends Funiter_Shortcode
    {
        /**
         * Shortcode name.
         *
         * @var  string
         */
        public $shortcode = 'categorywrap';


        /**
         * Default $atts .
         *
         * @var  array
         */
        public $default_atts = array();

        public function output_html($atts, $content = null)
        {
            $atts = function_exists('vc_map_get_attributes') ? vc_map_get_attributes('funiter_categorywrap', $atts) : $atts;
            // Extract shortcode parameters.
            extract($atts);
            $css_class = array('funiter-categorywrap');
            $css_class[] = $atts['el_class'];
            $css_class[] = $atts['style'];
            $class_editor = isset($atts['css']) ? vc_shortcode_custom_css_class($atts['css'], ' ') : '';
            $css_class[] = apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $class_editor, 'funiter_category', $atts);
            ob_start();
            ?>
            <div class="<?php echo esc_attr(implode(' ', $css_class)); ?>">
                <div class="category-head">
                    <?php if ($atts['title'] && $atts['style'] == 'style-01') : ?>
                        <h3 class="funiter-title">
                            <?php echo esc_html($atts['title']); ?>
                        </h3>
                    <?php endif; ?>
                    <?php if (trim($atts['taxonomy']) != ''):
                        $taxs = explode(',', $atts['taxonomy']); ?>
                        <ul class="category-list">
                            <?php foreach ($taxs as $tax): ?>
                                <?php $term = get_term_by('slug', $tax, 'product_cat');
                                if (!is_wp_error($term) && !empty($term)):
                                    $url = get_term_link($term->term_id, 'product_cat'); ?>
                                    <li>
                                        <a href="<?php echo esc_url($url); ?>">
                                            <?php echo esc_html($term->name); ?>
                                        </a>
                                    </li>
                                <?php endif; ?>
                            <?php endforeach; ?>
                            <li class="dropdown">
                                <a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown">
                                    <span class="fa fa-ellipsis-v"></span>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-right"></ul>
                            </li>
                        </ul>
                    <?php endif; ?>
                </div>
                <div class="category-container">
                    <?php echo wpb_js_remove_wpautop($content); ?>
                </div>
            </div>
            <?php
            $html = ob_get_clean();
            return apply_filters('Funiter_Shortcode_Categorywrap', $html, $atts, $content);
        }
    }

    new Funiter_Shortcode_Categorywrap();
}