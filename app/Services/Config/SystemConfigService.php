<?php

namespace App\Services\Config;

/**
 * Singleton Pattern: SystemConfigService
 * Manages configuration values across the application.
 * Prevents multiple instantiations to save memory and ensure a single source of truth.
 */
class SystemConfigService
{
    private static ?SystemConfigService $instance = null;
    private array $settings = [];

    /**
     * Private constructor to prevent direct instantiation.
     */
    private function __construct()
    {
        // Load default configuration
        $this->settings = [
            'app_version' => '1.0.0-beta',
            'maintenance_mode' => false,
            'max_event_quota' => 500,
            'allow_ticket_refund' => false
        ];
    }

    /**
     * Prevent cloning.
     */
    private function __clone() {}

    /**
     * Prevent unserializing.
     */
    public function __wakeup()
    {
        throw new \Exception("Cannot unserialize singleton.");
    }

    /**
     * Get the single instance of this class.
     *
     * @return SystemConfigService
     */
    public static function getInstance(): SystemConfigService
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Get a config setting.
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function get(string $key, $default = null)
    {
        return $this->settings[$key] ?? $default;
    }

    /**
     * Set a config setting.
     *
     * @param string $key
     * @param mixed $value
     */
    public function set(string $key, $value): void
    {
        $this->settings[$key] = $value;
    }
}
