<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Image_Size;
use Elementor\Utils;

class Advance_Table_Widget extends Widget_Base {

    public function get_name() {
        return 'advance_table';
    }

    public function get_title() {
        return esc_html__( 'Advance Table', 'advance-table-for-elementor' );
    }

    public function get_icon() {
        return 'eicon-table';
    }

    public function get_categories() {
        return [ 'basic' ];
    }

    // Load the CSS file registered in the main file
    public function get_style_depends() {
        return [ 'advance-table-css' ];
    }

    protected function register_controls() {

        // ==========================================
        // 1. TABLE SETTINGS TAB
        // ==========================================
        $this->start_controls_section('section_table_settings', [
            'label' => esc_html__( 'Table Settings', 'advance-table-for-elementor' ),
        ]);

        $this->add_control('table_layout', [
            'label' => esc_html__( 'Table Layout', 'advance-table-for-elementor' ),
            'type' => Controls_Manager::SELECT,
            'options' => [ 
                'auto' => esc_html__( 'Auto', 'advance-table-for-elementor' ), 
                'fixed' => esc_html__( 'Fixed', 'advance-table-for-elementor' ) 
            ],
            'default' => 'fixed',
        ]);

        $this->add_responsive_control('table_width', [
            'label' => esc_html__( 'Table Width (%)', 'advance-table-for-elementor' ),
            'type' => Controls_Manager::NUMBER,
            'default' => 100,
            'selectors' => ['{{WRAPPER}} .atfe-table' => 'width: {{VALUE}}%;'],
        ]);

        $this->add_responsive_control('table_min_width', [
            'label' => esc_html__( 'Table Min Width (px)', 'advance-table-for-elementor' ),
            'type' => Controls_Manager::SLIDER,
            'range' => [ 'px' => [ 'min' => 300, 'max' => 1500 ] ],
            'description' => esc_html__( 'Set min-width on mobile (e.g., 600px) to enable horizontal scrolling.', 'advance-table-for-elementor' ),
            'selectors' => ['{{WRAPPER}} .atfe-table' => 'min-width: {{SIZE}}{{UNIT}};'],
        ]);

        $this->add_control('table_border_width', [
            'label' => esc_html__( 'Border Width', 'advance-table-for-elementor' ), 'type' => Controls_Manager::NUMBER, 'default' => 1,
        ]);
        
        $this->add_control('table_border_color', [
            'label' => esc_html__( 'Border Color', 'advance-table-for-elementor' ), 'type' => Controls_Manager::COLOR, 'default' => '#ddd',
        ]);

        $this->add_control('sticky_header', [
            'label' => esc_html__( 'Sticky Header', 'advance-table-for-elementor' ), 'type' => Controls_Manager::SWITCHER, 'default' => 'yes',
        ]);

        $this->end_controls_section();

        // ==========================================
        // 2. CONTENT REPEATER
        // ==========================================
        $this->start_controls_section('section_content', [
            'label' => esc_html__( 'Table Content', 'advance-table-for-elementor' ),
        ]);

        $repeater = new Repeater();

        $repeater->add_control('new_row', [
            'label' => esc_html__( 'Start New Row?', 'advance-table-for-elementor' ),
            'type' => Controls_Manager::SWITCHER,
            'return_value' => 'yes',
            'separator' => 'before',
            'style_transfer' => true,
        ]);

        $repeater->add_control('column_type', [
            'label' => esc_html__( 'Cell Type', 'advance-table-for-elementor' ),
            'type' => Controls_Manager::SELECT,
            'options' => [ 'td' => 'Data', 'th' => 'Header' ],
            'default' => 'td',
        ]);

        // --- ADVANCED CONTENT ---
        $repeater->add_control('enable_advanced', [
            'label' => esc_html__( 'Enable Advanced Content?', 'advance-table-for-elementor' ),
            'type' => Controls_Manager::SWITCHER,
            'default' => '',
        ]);

        $repeater->add_control('cell_image', [
            'label' => esc_html__( 'Image / Logo', 'advance-table-for-elementor' ),
            'type' => Controls_Manager::MEDIA,
            'condition' => [ 'enable_advanced' => 'yes' ],
        ]);

        $repeater->add_control('cell_title', [
            'label' => esc_html__( 'Title', 'advance-table-for-elementor' ),
            'type' => Controls_Manager::TEXT,
            'default' => '',
            'condition' => [ 'enable_advanced' => 'yes' ],
        ]);

        $repeater->add_control('column_content', [
            'label' => esc_html__( 'Content / Description', 'advance-table-for-elementor' ),
            'type' => Controls_Manager::TEXTAREA,
            'default' => 'Content',
        ]);

        $repeater->add_control('cell_btn_text', [
            'label' => esc_html__( 'Button Text', 'advance-table-for-elementor' ),
            'type' => Controls_Manager::TEXT,
            'condition' => [ 'enable_advanced' => 'yes' ],
        ]);

        $repeater->add_control('cell_btn_link', [
            'label' => esc_html__( 'Button Link', 'advance-table-for-elementor' ),
            'type' => Controls_Manager::URL,
            'placeholder' => 'https://your-link.com',
            'condition' => [ 'enable_advanced' => 'yes' ],
        ]);

        // --- POSITIONING / ORDERING CONTROL ---
        $repeater->add_control('heading_order', [
            'label' => esc_html__( 'Element Order (1-4)', 'advance-table-for-elementor' ),
            'type' => Controls_Manager::HEADING,
            'separator' => 'before',
            'condition' => [ 'enable_advanced' => 'yes' ],
        ]);

        $repeater->add_control('order_image', [
            'label' => esc_html__( 'Image Position', 'advance-table-for-elementor' ),
            'type' => Controls_Manager::NUMBER, 'default' => 1, 'min' => 1, 'max' => 10,
            'condition' => [ 'enable_advanced' => 'yes' ],
        ]);

        $repeater->add_control('order_title', [
            'label' => esc_html__( 'Title Position', 'advance-table-for-elementor' ),
            'type' => Controls_Manager::NUMBER, 'default' => 2, 'min' => 1, 'max' => 10,
            'condition' => [ 'enable_advanced' => 'yes' ],
        ]);

        $repeater->add_control('order_content', [
            'label' => esc_html__( 'Content Position', 'advance-table-for-elementor' ),
            'type' => Controls_Manager::NUMBER, 'default' => 3, 'min' => 1, 'max' => 10,
            'condition' => [ 'enable_advanced' => 'yes' ],
        ]);

        $repeater->add_control('order_btn', [
            'label' => esc_html__( 'Button Position', 'advance-table-for-elementor' ),
            'type' => Controls_Manager::NUMBER, 'default' => 4, 'min' => 1, 'max' => 10,
            'condition' => [ 'enable_advanced' => 'yes' ],
        ]);
        // --------------------------------------

        $repeater->add_control('custom_width', [
            'label' => esc_html__( 'Width (%) - 1st Row Only', 'advance-table-for-elementor' ),
            'type' => Controls_Manager::NUMBER, 'separator' => 'before',
        ]);

        $repeater->add_control('column_rowspan', [ 'label' => 'Rowspan', 'type' => Controls_Manager::NUMBER, 'default' => 1 ]);
        $repeater->add_control('column_colspan', [ 'label' => 'Colspan', 'type' => Controls_Manager::NUMBER, 'default' => 1 ]);
        
        $repeater->add_control('column_alignment', [
            'label' => 'Align', 'type' => Controls_Manager::SELECT,
            'options' => [ 'left' => 'Left', 'center' => 'Center', 'right' => 'Right' ], 'default' => 'left',
        ]);
        
        $repeater->add_control('vertical_alignment', [
            'label' => 'V. Align', 'type' => Controls_Manager::SELECT,
            'options' => [ 'top' => 'Top', 'middle' => 'Middle', 'bottom' => 'Bottom' ], 'default' => 'middle',
        ]);

        $repeater->add_control('column_bg', [ 'label' => 'BG Color', 'type' => Controls_Manager::COLOR ]);
        $repeater->add_control('column_color', [ 'label' => 'Text Color', 'type' => Controls_Manager::COLOR ]);

        $this->add_control('table_cells', [
            'label' => esc_html__( 'Table Cells', 'advance-table-for-elementor' ),
            'type' => Controls_Manager::REPEATER,
            'fields' => $repeater->get_controls(),
            'title_field' => '{{{ cell_title ? cell_title : column_content }}}',
        ]);

        $this->end_controls_section();


        // ==========================================
        // 3. STYLE TAB
        // ==========================================
        
        // --- Header Styles ---
        $this->start_controls_section('section_style_header', [
            'label' => esc_html__( 'Header Styles (TH)', 'advance-table-for-elementor' ), 'tab' => Controls_Manager::TAB_STYLE,
        ]);
        $this->add_control('header_bg_color', [
            'label' => esc_html__( 'Background', 'advance-table-for-elementor' ), 'type' => Controls_Manager::COLOR, 'default' => '#f1f1f1',
            'selectors' => ['{{WRAPPER}} .atfe-table th' => 'background-color: {{VALUE}};']
        ]);
        $this->add_control('header_text_color', [
            'label' => esc_html__( 'Text Color', 'advance-table-for-elementor' ), 'type' => Controls_Manager::COLOR, 'default' => '#333',
            'selectors' => ['{{WRAPPER}} .atfe-table th' => 'color: {{VALUE}};']
        ]);
        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name' => 'header_typography', 'selector' => '{{WRAPPER}} .atfe-table th'
        ]);
        $this->end_controls_section();

        // --- Body Styles ---
        $this->start_controls_section('section_style_body', [
            'label' => esc_html__( 'Body Styles (TD)', 'advance-table-for-elementor' ), 'tab' => Controls_Manager::TAB_STYLE,
        ]);
        $this->add_control('body_bg_color', [
            'label' => esc_html__( 'Background', 'advance-table-for-elementor' ), 'type' => Controls_Manager::COLOR, 'default' => '#fff',
            'selectors' => ['{{WRAPPER}} .atfe-table td' => 'background-color: {{VALUE}};']
        ]);
        $this->add_control('body_text_color', [
            'label' => esc_html__( 'Text Color', 'advance-table-for-elementor' ), 'type' => Controls_Manager::COLOR, 'default' => '#555',
            'selectors' => ['{{WRAPPER}} .atfe-table td' => 'color: {{VALUE}};']
        ]);
        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name' => 'body_typography', 'selector' => '{{WRAPPER}} .atfe-table td'
        ]);
        $this->add_responsive_control('cell_padding', [
            'label' => esc_html__( 'Padding', 'advance-table-for-elementor' ), 'type' => Controls_Manager::DIMENSIONS,
            'default' => ['top'=>15,'right'=>15,'bottom'=>15,'left'=>15,'unit'=>'px','isLinked'=>true],
            'selectors' => ['{{WRAPPER}} .atfe-table th, {{WRAPPER}} .atfe-table td' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};']
        ]);
        $this->end_controls_section();

        // --- Advanced Content Styles ---
        $this->start_controls_section('section_style_advanced', [
            'label' => esc_html__( 'Advanced Content', 'advance-table-for-elementor' ), 'tab' => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('adv_gap', [
            'label' => esc_html__( 'Gap Between Elements', 'advance-table-for-elementor' ),
            'type' => Controls_Manager::SLIDER,
            'range' => [ 'px' => [ 'min' => 0, 'max' => 50 ] ],
            'default' => [ 'unit' => 'px', 'size' => 8 ],
            'selectors' => [ '{{WRAPPER}} .atfe-content-wrapper' => 'gap: {{SIZE}}{{UNIT}};' ],
        ]);

        $this->add_control('adv_image_width', [
            'label' => esc_html__( 'Image Width (px)', 'advance-table-for-elementor' ),
            'type' => Controls_Manager::SLIDER,
            'range' => [ 'px' => [ 'min' => 10, 'max' => 200 ] ],
            'selectors' => [ '{{WRAPPER}} .atfe-image' => 'width: {{SIZE}}{{UNIT}};' ],
        ]);

        $this->add_control('adv_title_color', [
            'label' => esc_html__( 'Title Color', 'advance-table-for-elementor' ), 'type' => Controls_Manager::COLOR, 'default' => '#000',
            'selectors' => [ '{{WRAPPER}} .atfe-title' => 'color: {{VALUE}};' ],
        ]);
        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name' => 'adv_title_typography', 'label' => 'Title Typography', 'selector' => '{{WRAPPER}} .atfe-title'
        ]);

        $this->add_control('heading_btn_style', [
            'label' => esc_html__( 'Button Style', 'advance-table-for-elementor' ), 'type' => Controls_Manager::HEADING, 'separator' => 'before',
        ]);
        $this->add_control('btn_bg_color', [
            'label' => esc_html__( 'Button BG', 'advance-table-for-elementor' ), 'type' => Controls_Manager::COLOR, 'default' => '#333',
            'selectors' => [ '{{WRAPPER}} .atfe-button' => 'background-color: {{VALUE}};' ],
        ]);
        $this->add_control('btn_text_color', [
            'label' => esc_html__( 'Button Text', 'advance-table-for-elementor' ), 'type' => Controls_Manager::COLOR, 'default' => '#fff',
            'selectors' => [ '{{WRAPPER}} .atfe-button' => 'color: {{VALUE}};' ],
        ]);
        $this->add_responsive_control('btn_padding', [
            'label' => esc_html__( 'Padding', 'advance-table-for-elementor' ), 'type' => Controls_Manager::DIMENSIONS,
            'selectors' => ['{{WRAPPER}} .atfe-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};']
        ]);
        $this->add_control('btn_border_radius', [
            'label' => esc_html__( 'Radius', 'advance-table-for-elementor' ), 'type' => Controls_Manager::SLIDER,
            'selectors' => [ '{{WRAPPER}} .atfe-button' => 'border-radius: {{SIZE}}{{UNIT}};' ],
        ]);

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        $layout_style = $settings['table_layout'] === 'fixed' ? 'table-layout: fixed;' : 'table-layout: auto;';
        
        $table_style = sprintf(
            'border: %spx solid %s; %s',
            $settings['table_border_width'],
            $settings['table_border_color'],
            $layout_style
        );

        echo '<div class="atfe-table-wrapper">';
        echo "<table class='atfe-table' style='{$table_style}'>";

        if ( ! empty( $settings['table_cells'] ) ) {
            $row_open = false;
            foreach ( $settings['table_cells'] as $index => $item ) {
                
                if ( $index === 0 || $item['new_row'] === 'yes' ) {
                    if ( $row_open ) echo '</tr>';
                    echo '<tr>';
                    $row_open = true;
                }

                $tag = $item['column_type'];
                // Header Sticky Logic Class
                $sticky_class = ($tag === 'th' && !empty($settings['sticky_header'])) ? 'atfe-sticky-header' : '';

                $colspan = $item['column_colspan'] > 1 ? "colspan='{$item['column_colspan']}'" : "";
                $rowspan = !empty($item['column_rowspan']) && $item['column_rowspan'] > 1 ? "rowspan='{$item['column_rowspan']}'" : "";
                
                // Alignment mapping for flexbox
                $h_align = !empty($item['column_alignment']) ? $item['column_alignment'] : 'left';
                $flex_align = 'flex-start';
                if ($h_align === 'center') $flex_align = 'center';
                if ($h_align === 'right') $flex_align = 'flex-end';

                $styles = [];
                $styles[] = "border: 1px solid " . $settings['table_border_color'];
                $styles[] = "text-align: {$h_align}"; // For fallback text
                if (!empty($item['vertical_alignment'])) $styles[] = "vertical-align: {$item['vertical_alignment']}";
                if (!empty($item['column_bg'])) $styles[] = "background-color: {$item['column_bg']}";
                if (!empty($item['column_color'])) $styles[] = "color: {$item['column_color']}";
                if (!empty($item['custom_width'])) $styles[] = "width: {$item['custom_width']}%";

                $style_attr = 'style="' . implode('; ', $styles) . '"';

                echo "<{$tag} class='{$sticky_class}' {$colspan} {$rowspan} {$style_attr}>";

                if ( $item['enable_advanced'] === 'yes' ) {
                    // Flex container for ordering
                    echo '<div class="atfe-content-wrapper" style="align-items:'.$flex_align.';">';
                    
                    // 1. Image
                    if ( ! empty( $item['cell_image']['url'] ) ) {
                        $img_order = isset($item['order_image']) ? $item['order_image'] : 1;
                        echo '<div style="order:'.$img_order.'; width:100%;">'; // Wrapper for order
                        echo '<img class="atfe-image" src="' . esc_url( $item['cell_image']['url'] ) . '" alt="table-img">';
                        echo '</div>';
                    }

                    // 2. Title
                    if ( ! empty( $item['cell_title'] ) ) {
                        $title_order = isset($item['order_title']) ? $item['order_title'] : 2;
                        echo '<div class="atfe-title" style="order:'.$title_order.';">' . esc_html( $item['cell_title'] ) . '</div>';
                    }

                    // 3. Content
                    if ( ! empty( $item['column_content'] ) ) {
                        $content_order = isset($item['order_content']) ? $item['order_content'] : 3;
                        echo '<div class="atfe-body" style="order:'.$content_order.';">' . $item['column_content'] . '</div>';
                    }

                    // 4. Button
                    if ( ! empty( $item['cell_btn_text'] ) ) {
                        $btn_order = isset($item['order_btn']) ? $item['order_btn'] : 4;
                        
                        $link_attr = 'href="#"';
                        if ( ! empty( $item['cell_btn_link']['url'] ) ) {
                            $link_attr = 'href="' . esc_url( $item['cell_btn_link']['url'] ) . '"';
                            if ( $item['cell_btn_link']['is_external'] ) $link_attr .= ' target="_blank"';
                            if ( $item['cell_btn_link']['nofollow'] ) $link_attr .= ' rel="nofollow"';
                        }
                        
                        echo '<div style="order:'.$btn_order.'; width:100%;">';
                        echo '<a class="atfe-button" ' . $link_attr . '>' . esc_html( $item['cell_btn_text'] ) . '</a>';
                        echo '</div>';
                    }

                    echo '</div>'; // End wrapper
                } else {
                    echo $item['column_content'];
                }

                echo "</{$tag}>";
            }
            if ( $row_open ) echo '</tr>';
        }
        echo "</table></div>";
    }
}