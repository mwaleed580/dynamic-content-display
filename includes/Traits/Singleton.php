<?php

namespace DynamicContentDisplay\Traits;

/**
 * Singleton trait - provides singleton pattern implementation
 */
trait Singleton
{
    /**
     * Singleton instance
     *
     * @var static|null
     */
    private static $instance = null;

    /**
     * Get singleton instance
     *
     * @return static
     */
    public static function get_instance()
    {
        if (null === static::$instance) {
            static::$instance = new static();
        }
        return static::$instance;
    }

    /**
     * Private constructor to prevent direct instantiation
     */
    private function __construct()
    {
        $this->init();
    }

    /**
     * Initialize method - override in implementing classes
     */
    protected function init(): void
    {
        // Override in child classes
    }
}