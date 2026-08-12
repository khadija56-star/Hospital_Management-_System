<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

define('APP_NAME', 'Green Life General Hospital');
define('APP_ROOT', realpath(__DIR__ . '/..'));
define(
    'DOC_ROOT_REAL',
    isset($_SERVER['DOCUMENT_ROOT']) ? realpath($_SERVER['DOCUMENT_ROOT']) : false
);

function base_url(): string
{
    if (DOC_ROOT_REAL && strpos(APP_ROOT, DOC_ROOT_REAL) === 0) {
        $relative = trim(
            str_replace('\\', '/', substr(APP_ROOT, strlen(DOC_ROOT_REAL))),
            '/'
        );

        return $relative === '' ? '' : '/' . $relative;
    }

    $script = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '');
    $dir = trim(dirname($script), '/.');

    return $dir ? '/' . $dir : '';
}

function url(string $path = ''): string
{
    $base = rtrim(base_url(), '/');
    $path = ltrim($path, '/');

    return $path ? (($base ?: '') . '/' . $path) : ($base ?: '/');
}

function asset(string $path): string
{
    return url('assets/' . ltrim($path, '/'));
}

function e($v): string
{
    return htmlspecialchars((string) $v, ENT_QUOTES, 'UTF-8');
}

function redirect_to(string $path): void
{
    header('Location: ' . url($path));
    exit;
}

function is_logged_in(): bool
{
    return !empty($_SESSION['admin_logged_in']);
}

function require_login(): void
{
    if (!is_logged_in()) {
        redirect_to('login.php');
    }
}

function status_badge_class(string $status): string
{
    $s = strtolower($status);

    return in_array($s, ['paid', 'completed', 'available', 'normal', 'active'])
        ? 'badge success'
        : (
            in_array($s, ['pending', 'scheduled', 'low stock'])
                ? 'badge warning'
                : 'badge'
        );
}