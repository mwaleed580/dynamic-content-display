<?php

namespace DynamicContentDisplay\Services;

use DynamicContentDisplay\Traits\Singleton;

/**
 * Tab ordering service - handles ACF data and CSS generation
 */
class TabOrderingService
{
    use Singleton;

    /**
     * CookieService instance
     *
     * @var CookieService
     */
    private $cookie_service;

    /**
     * Initialize service
     */
    protected function init(): void
    {
        $this->cookie_service = CookieService::get_instance();
        add_action('wp_head', array($this, 'add_tab_ordering_css'));
    }

    /**
     * Check if tab ordering should be applied
     *
     * @return bool True if conditions are met
     */
    private function should_apply_tab_ordering(): bool
    {
        if (!is_single()) {
            return false;
        }

        $post_type = get_post_type();
        if ($post_type !== 'project') {
            return false;
        }

        $post_id = get_the_ID();
        $has_fe_param = isset($_GET['fe']) && !empty($_GET['fe']) && strpos($_GET['fe'], ' ') !== false;
        $has_saved_order = $this->cookie_service->has_tab_order_cookie($post_id);
        
        return $has_fe_param || $has_saved_order;
    }

    /**
     * Add CSS for tab ordering
     */
    public function add_tab_ordering_css(): void
    {
        if (!$this->should_apply_tab_ordering()) {
            return;
        }

        if (!function_exists('get_field')) {
            return;
        }

        $post_id = get_the_ID();
        $data_tabs = get_field('data_tabs', $post_id);

        if (!$data_tabs || !is_array($data_tabs)) {
            return;
        }

        $desired_order = $this->get_desired_order($post_id);

        if (!empty($desired_order)) {
            $tab_order = $this->process_tabs_for_ordering($data_tabs, $desired_order);
            
            if (!empty($tab_order)) {
                $total_rows = count($data_tabs);
                $this->output_tab_ordering_css($tab_order, $total_rows);
            }
        }
    }

    /**
     * Get desired order from URL parameter or cookie
     *
     * @param int $post_id Current post ID
     * @return array Array of desired tab order
     */
    private function get_desired_order(int $post_id): array
    {
        if (isset($_GET['fe']) && !empty($_GET['fe']) && strpos($_GET['fe'], ' ') !== false) {
            $fe_parameter = sanitize_text_field($_GET['fe']);
            $desired_order = explode(' ', $fe_parameter);
            $this->cookie_service->save_tab_order_cookie($post_id, $fe_parameter);
            return $desired_order;
        }
        
        return $this->cookie_service->get_tab_order_from_cookie($post_id);
    }

    /**
     * Process tabs data and determine ordering
     *
     * @param array $data_tabs ACF repeater field data
     * @param array $desired_order Desired tab order from URL parameter
     * @return array Array with CSS IDs and their order
     */
    private function process_tabs_for_ordering(array $data_tabs, array $desired_order): array
    {
        $processed_tabs = array();

        foreach ($data_tabs as $index => $tab) {
            if (isset($tab['url_parameter'])) {
                $processed_tabs[$index + 1] = $tab['url_parameter'];
            }
        }

        $ordering_map = array();
        $target_order = 1;

        foreach ($desired_order as $desired_param) {
            foreach ($processed_tabs as $css_id => $url_param) {
                if ($url_param === $desired_param) {
                    $ordering_map[$css_id] = $target_order;
                    $target_order++;
                    break;
                }
            }
        }

        foreach ($processed_tabs as $css_id => $url_param) {
            if (!isset($ordering_map[$css_id])) {
                $ordering_map[$css_id] = $target_order;
                $target_order++;
            }
        }

        return $ordering_map;
    }



    /**
     * Output CSS for tab ordering
     *
     * @param array $tab_order Array with CSS IDs and their order
     * @param int $total_rows Total number of rows in repeater
     */
    private function output_tab_ordering_css(array $tab_order, int $total_rows): void
    {
        echo "<style type='text/css'>\n";
        
        foreach ($tab_order as $css_id => $order) {
            echo ".dce-elementor-rendering-id-{$css_id} { order: {$order}; }\n";
        }
        
        $current_set = 1;
        while (($current_set * $total_rows) < 100) { 
            $offset = $current_set * $total_rows;
            
            foreach ($tab_order as $css_id => $order) {
                $new_css_id = $css_id + $offset;
                echo ".dce-elementor-rendering-id-{$new_css_id} { order: {$order}; }\n";
            }
            
            $current_set++;
        }
        
        echo "</style>\n";
    }
}