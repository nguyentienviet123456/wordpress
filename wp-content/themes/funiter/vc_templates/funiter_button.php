<?php
if (!defined('ABSPATH')) {
    die('-1');
}
if (!class_exists('Funiter_Shortcode_Button')) {
    class Funiter_Shortcode_Button extends Funiter_Shortcode
    {
        /**
         * Shortcode name.
         *
         * @var  string
         */
        public $shortcode = 'button';
        /**
         * Default $atts .
         *
         * @var  array
         */
        public $default_atts = array();

        public function output_html($atts, $content = null)
        {
            $atts = function_exists('vc_map_get_attributes') ? vc_map_get_attributes('funiter_button', $atts) : $atts;
            // Extract shortcode parameters.
            extract($atts);
            $css_class = array('funiter-button');
            $css_class[] = $atts['style'];
            $css_class[] = $atts['el_class'];
            $class_editor = isset($atts['css']) ? vc_shortcode_custom_css_class($atts['css'], ' ') : '';
            $css_class[] = apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $class_editor, 'funiter_button', $atts);
            $button_link = vc_build_link($atts['link']);
            if ($button_link['url']) {
                $link_url = $button_link['url'];
            } else {
                $link_url = '#';
            }
            if ($button_link['target']) {
                $link_target = $button_link['target'];
            } else {
                $link_target = '_self';
            }
            ob_start();
            ?>
            <div class="<?php echo esc_attr(implode(' ', $css_class)); ?>">
                <?php if($button_link['title']) :?>
                    <div class="button-inner">
                        <a class="button" target="<?php echo esc_attr($link_target); ?>" href="<?php echo esc_url($link_url); ?>"><?php echo esc_html($button_link['title']); ?></a>
                    </div>
                <?php endif; ?>
            </div>
            <?php
            $html = ob_get_clean();

            return apply_filters('Funiter_Shortcode_Button', $html, $atts, $content);
        }
    }

    new Funiter_Shortcode_Button();
}