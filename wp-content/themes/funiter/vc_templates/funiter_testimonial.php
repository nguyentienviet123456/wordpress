<?php
if (!defined('ABSPATH')) {
    die('-1');
}
/**
 * Shortcode attributes
 * @var $atts
 * Shortcode class
 * @var $this "Funiter_Testimonial"
 */
if (!class_exists('Funiter_Shortcode_Testimonial')) {
    class Funiter_Shortcode_Testimonial extends Funiter_Shortcode
    {
        /**
         * Shortcode name.
         *
         * @var  string
         */
        public $shortcode = 'testimonial';

        static public function add_css_generate($atts)
        {
            $atts = function_exists('vc_map_get_attributes') ? vc_map_get_attributes('funiter_testimonial', $atts) : $atts;
            // Extract shortcode parameters.
            extract($atts);
            $css = '';

            return apply_filters('Funiter_Shortcode_Testimonial_css', $css, $atts);
        }

        public function output_html($atts, $content = null)
        {
            $atts = function_exists('vc_map_get_attributes') ? vc_map_get_attributes('funiter_testimonial', $atts) : $atts;
            extract($atts);
            $css_class = array('funiter-testimonial');
            $css_class[] = $atts['style'];
            $css_class[] = $atts['type_color'];
            $css_class[] = $atts['el_class'];
            $class_editor = isset($atts['css']) ? vc_shortcode_custom_css_class($atts['css'], ' ') : '';
            $css_class[] = apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $class_editor, 'funiter_testimonial', $atts);
            $testimonial_link = vc_build_link($atts['link']);
            if ($testimonial_link['url']) {
                $link_url = $testimonial_link['url'];
                $link_target = $testimonial_link['target'];
            } else {
                $link_target = '_self';
                $link_url = '#';
            }
            ob_start();
            ?>
            <div class="<?php echo esc_attr(implode(' ', $css_class)); ?>">
                <div class="testimonial-inner">
                    <div class="testimonial-wrap equal-elem">
                        <?php if ($atts['rating']) : ?>
                            <div class="rating <?php echo esc_attr($atts['rating']); ?>"><span></span></div>
                        <?php endif; ?>
                        <?php if ($atts['title']) : ?>
                            <div class="title"><?php echo esc_html($atts['title']); ?></div>
                        <?php endif; ?>
                        <?php if ($atts['desc']) : ?>
                            <div class="content"><?php echo wp_specialchars_decode($atts['desc']); ?></div>
                        <?php endif; ?>
                    </div>
                    <div class="testimonial-info">
                        <?php if ($atts['image']) :
                            $thumb = apply_filters('funiter_resize_image', $atts['image'], 77, 77, true, true); ?>
                            <div class="thumb">
                                <?php echo wp_specialchars_decode($thumb['img']); ?>
                            </div>
                        <?php endif; ?>
                        <div class="intro">
                            <?php if ($atts['name']) : ?>
                                <h3 class="name">
                                    <a href="<?php echo esc_url($link_url); ?>"
                                       target="<?php echo esc_attr($link_target); ?>">
                                        <?php echo esc_html($atts['name']); ?>
                                    </a>
                                </h3>
                            <?php endif; ?>
                            <?php if ($atts['position']): ?>
                                <div class="position">
                                    <?php echo esc_html($atts['position']); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php
            $html = ob_get_clean();

            return apply_filters('Funiter_Shortcode_Testimonial', $html, $atts, $content);
        }
    }

    new Funiter_Shortcode_Testimonial();
}