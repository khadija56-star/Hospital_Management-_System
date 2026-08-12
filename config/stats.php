<?php
function scalar_count(PDO $pdo, string $table): int { return (int)$pdo->query("SELECT COUNT(*) FROM {$table}")->fetchColumn(); }
function dashboard_stats(PDO $pdo): array {
    return [
        'Patients' => scalar_count($pdo, 'patients'),
        'Doctors' => scalar_count($pdo, 'doctors'),
        'Appointments' => scalar_count($pdo, 'appointments'),
        'Bills' => scalar_count($pdo, 'billing'),
        'Medicines' => scalar_count($pdo, 'pharmacy_items'),
        'Lab Tests' => scalar_count($pdo, 'lab_tests'),
    ];
}
