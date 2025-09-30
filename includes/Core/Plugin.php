<?php

namespace DynamicContentDisplay\Core;

use DynamicContentDisplay\Traits\Singleton;
use DynamicContentDisplay\Services\CookieService;
use DynamicContentDisplay\Services\TabOrderingService;

/**
 * Main plugin class that orchestrates all functionality
 */
class Plugin
{
    use Singleton;

    /**
     * CookieService instance
     *
     * @var CookieService
     */
    private $cookie_service;

    /**
     * TabOrderingService instance
     *
     * @var TabOrderingService
     */
    private $tab_ordering_service;

    /**
     * Initialize plugin
     */
    protected function init(): void
    {
        $this->init_services();
        $this->init_hooks();
    }

    /**
     * Initialize services
     */
    private function init_services(): void
    {
        $this->cookie_service = CookieService::get_instance();
        $this->tab_ordering_service = TabOrderingService::get_instance();
    }

    /**
     * Initialize WordPress hooks
     */
    private function init_hooks(): void
    {
        add_action('init', array($this, 'check_url_parameters'));
    }

    /**
     * Check URL parameters and handle them
     */
    public function check_url_parameters(): void
    {
        if (isset($_GET['sr'])) {
            $sr_value = sanitize_text_field($_GET['sr']);

            if ($sr_value === 'up') {
                $this->cookie_service->set_display_mode_cookie('up');
            }
        }
    }
}