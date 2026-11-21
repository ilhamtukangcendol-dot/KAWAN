<?php
/**
 * KAWAN CI/CD Standalone Deploy Webhook
 * Accessible directly at: https://kawan.unique-monsters.my.id/deploy.php?secret=kawan_deploy_secret_2026
 */

header('Content-Type: application/json; charset=utf-8');

$expectedSecret = 'kawan_deploy_secret_2026';
$providedSecret = $_GET['secret'] ?? $_POST['secret'] ?? $_SERVER['HTTP_X_DEPLOY_SECRET'] ?? null;

if ($providedSecret !== $expectedSecret) {
    http_response_code(403);
    echo json_encode([
        'status' => 'error',
        'message' => 'Unauthorized: Invalid or missing deployment secret.',
        'hint' => 'Add ?secret=' . $expectedSecret . ' to the URL'
    ], JSON_PRETTY_PRINT);
    exit;
}

// Find repository root
$repoDir = dirname(__DIR__, 2); // from BE/public -> BE -> root
if (!file_exists($repoDir . '/.git')) {
    $repoDir = dirname(__DIR__); // BE as root
}

$output = [];

if (file_exists($repoDir . '/.git') && function_exists('exec')) {
    $gitCmd = "cd " . escapeshellarg($repoDir) . " && git fetch origin main 2>&1 && git reset --hard origin/main 2>&1";
    exec($gitCmd, $gitOutput, $exitCode);
    $output['git'] = implode("\n", $gitOutput);
    $output['git_code'] = $exitCode;
} else {
    $output['git'] = 'Git directory not found or exec disabled.';
}

// Clear Laravel compiled views directly from filesystem
$viewCacheDir = dirname(__DIR__) . '/storage/framework/views';
if (is_dir($viewCacheDir)) {
    $files = glob($viewCacheDir . '/*.php');
    $deleted = 0;
    if ($files) {
        foreach ($files as $file) {
            if (is_file($file)) {
                @unlink($file);
                $deleted++;
            }
        }
    }
    $output['views_cleared'] = "$deleted compiled view files removed.";
}

// Also clear cache and route files
$frameworkCache = dirname(__DIR__) . '/bootstrap/cache';
if (is_dir($frameworkCache)) {
    @unlink($frameworkCache . '/config.php');
    @unlink($frameworkCache . '/routes-v7.php');
    @unlink($frameworkCache . '/packages.php');
    @unlink($frameworkCache . '/services.php');
    $output['bootstrap_cache_cleared'] = true;
}

echo json_encode([
    'status' => 'success',
    'timestamp' => date('Y-m-d H:i:s'),
    'message' => 'Deployment executed successfully!',
    'output' => $output
], JSON_PRETTY_PRINT);

