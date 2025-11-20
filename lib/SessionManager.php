<?php

class SessionManager
{
    private static ?SessionManager $instance = null;

    private function __construct()
    {
        // Lancer la session si elle n’est pas encore active
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public static function getInstance(): SessionManager
    {
        if (self::$instance === null) {
            self::$instance = new SessionManager();
        }
        return self::$instance;
    }

    public function set(string $key, mixed $value): void
    {
        $_SESSION[$key] = $value;
    }

    public function get(string $key): mixed
    {
        return $_SESSION[$key] ?? null;
    }

    public function isAdmin(): bool
    {
        return $_SESSION["role"] === "admin";
    }

    public function destroy(): void
    {
        session_unset();
        session_destroy();
    }

    private function __clone() {}
    public function __wakeup() {}
}
