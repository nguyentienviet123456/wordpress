<?php
if (!class_exists('Funiter_Shortcode_Iconbox')) {
    class Funiter_Shortcode_Iconbox extends Funiter_Shortcode
    {
        /**
         * Shortcode name.
         *
         * @var  string
         */
        public $shortcode = 'iconbox';
        /**
         * Default $atts .
         *
         * @var  array
         */
        public $default_atts = array();

        public function output_html($atts, $content = null)
        {
            $atts = function_exists('vc_map_get_attributes') ? vc_map_get_attributes('funiter_iconbox', $atts) : $atts;
            // Extract shortcode parameters.
            extract($atts);
            $css_class = array('funiter-iconbox');
            $css_class[] = $atts['style'];
            $css_class[] = $atts['el_class'];
            $class_editor = isset($atts['css']) ? vc_shortcode_custom_css_class($atts['css'], ' ') : '';
            $css_class[] = apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $class_editor, 'funiter_iconbox', $atts);
            $icon = $atts['icon_' . $atts['type']];
            // Enqueue needed icon font.
            vc_icon_element_fonts_enqueue($atts['type']);
            ob_start();
            ?>
            <div class="<?php echo esc_attr(implode(' ', $css_class)); ?>">
                <div class="iconbox-inner">
                    <?php if ($icon): ?>
                        <div class="icon"><span class="<?php echo esc_attr($icon) ?>"></span></div>
                    <?php endif; ?>
                    <div class="content">
                        <?php if ($atts['title'] && ($atts['style'] == 'style-01' || $atts['style'] == 'style-02' || $atts['style'] == 'style-04'|| $atts['style'] == 'style-05')): ?>
                            <h4 class="title"><?php echo esc_html($atts['title']); ?></h4>
                        <?php endif; ?>
                        <?php if ($atts['text_content']): ?>
                            <p class="text"><?php echo wp_specialchars_decode($atts['text_content']); ?></p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php
            $html = ob_get_clean();

            return apply_filters('Funiter_Shortcode_Iconbox', $html, $atts, $content);
        }
    }

    new Funiter_Shortcode_Iconbox();
}