<?php

namespace DynamicContentDisplay\Services;

use DynamicContentDisplay\Traits\Singleton;

/**
 * Cookie service - handles cookie operations
 */
class CookieService
{
    use Singleton;

    /**
     * Set display mode cookie (for sr=up parameter)
     *
     * @param string $mode The display mode
     */
    public function set_display_mode_cookie(string $mode): void
    {
        setcookie('dcd_display_mode', sanitize_text_field($mode), time() + (24 * 60 * 60), '/');
    }

    /**
     * Save tab order cookie for specific project
     *
     * @param int $post_id Project post ID
     * @param string $tab_order Tab order string
     */
    public function save_tab_order_cookie(int $post_id, string $tab_order): void
    {
        $cookie_name = 'dcd_tab_order_' . $post_id;
        $cookie_value = sanitize_text_field($tab_order);
        setcookie($cookie_name, $cookie_value, time() + (30 * 24 * 60 * 60), '/');
    }

    /**
     * Get tab order from cookie for specific project
     *
     * @param int $post_id Project post ID
     * @return array Tab order array or empty array
     */
    public function get_tab_order_from_cookie(int $post_id): array
    {
        $cookie_name = 'dcd_tab_order_' . $post_id;
        
        if (isset($_COOKIE[$cookie_name]) && !empty($_COOKIE[$cookie_name])) {
            $cookie_value = sanitize_text_field($_COOKIE[$cookie_name]);
            return explode(' ', $cookie_value);
        }
        
        return array();
    }

    /**
     * Check if tab order cookie exists for project
     *
     * @param int $post_id Project post ID
     * @return bool True if cookie exists
     */
    public function has_tab_order_cookie(int $post_id): bool
    {
        $cookie_name = 'dcd_tab_order_' . $post_id;
        return isset($_COOKIE[$cookie_name]) && !empty($_COOKIE[$cookie_name]);
    }
}