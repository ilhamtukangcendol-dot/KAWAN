<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

class DeployWebhookController extends Controller
{
    /**
     * Secret key deployment default
     */
    protected const DEFAULT_SECRET = 'kawan_deploy_secret_2026';

    /**
     * Handle incoming deployment webhook from GitHub Actions or GitHub Webhook
     */
    public function deploy(Request $request)
    {
        $expectedSecret = env('DEPLOY_SECRET', self::DEFAULT_SECRET);
        $providedSecret = $request->input('secret') 
            ?? $request->header('X-Deploy-Secret')
            ?? $request->header('X-Gitlab-Token');

        // Validasi GitHub webhook signature jika dikirim
        $hubSignature = $request->header('X-Hub-Signature-256');
        $isSignatureValid = false;
        if ($hubSignature && function_exists('hash_hmac')) {
            $payload = $request->getContent();
            $knownHash = 'sha256=' . hash_hmac('sha256', $payload, $expectedSecret);
            $isSignatureValid = hash_equals($knownHash, $hubSignature);
        }

        // Cek autentikasi token atau signature
        if ($providedSecret !== $expectedSecret && !$isSignatureValid) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized: Token rahasia deploy tidak cocok atau tidak disertakan.',
                'hint' => 'Sertakan ?secret=' . self::DEFAULT_SECRET . ' pada URL webhook Anda.'
            ], 403);
        }

        $results = [
            'status' => 'success',
            'timestamp' => now()->toIso8601String(),
            'app_env' => config('app.env'),
            'actions' => []
        ];

        // 1. Tentukan path repository git
        $gitDir = null;
        if (file_exists(base_path('../.git'))) {
            $gitDir = realpath(base_path('..'));
        } elseif (file_exists(base_path('.git'))) {
            $gitDir = realpath(base_path());
        }

        // 2. Jalankan git pull jika fungsi eksekusi sistem tersedia
        $gitOutput = 'Git command not executed';
        if ($gitDir && (function_exists('shell_exec') || function_exists('exec'))) {
            try {
                // Set path git standar jika diperlukan
                $command = "cd " . escapeshellarg($gitDir) . " && git fetch origin main 2>&1 && git reset --hard origin/main 2>&1";
                if (function_exists('shell_exec')) {
                    $gitOutput = shell_exec($command);
                } else {
                    $outArr = [];
                    exec($command, $outArr);
                    $gitOutput = implode("\n", $outArr);
                }
                $results['actions']['git_pull'] = [
                    'status' => 'success',
                    'path' => $gitDir,
                    'output' => trim($gitOutput ?? '')
                ];
            } catch (\Throwable $e) {
                $results['actions']['git_pull'] = [
                    'status' => 'warning',
                    'error' => $e->getMessage()
                ];
            }
        } else {
            $results['actions']['git_pull'] = [
                'status' => 'skipped',
                'reason' => $gitDir ? 'shell_exec/exec disabled on server' : '.git folder not found'
            ];
        }

        // 3. Bersihkan View Cache
        try {
            Artisan::call('view:clear');
            $results['actions']['view_clear'] = trim(Artisan::output());
        } catch (\Throwable $e) {
            $results['actions']['view_clear'] = 'Error: ' . $e->getMessage();
        }

        // 4. Bersihkan Route Cache
        try {
            Artisan::call('route:clear');
            $results['actions']['route_clear'] = trim(Artisan::output());
        } catch (\Throwable $e) {
            $results['actions']['route_clear'] = 'Error: ' . $e->getMessage();
        }

        // 5. Bersihkan Config Cache
        try {
            Artisan::call('config:clear');
            $results['actions']['config_clear'] = trim(Artisan::output());
        } catch (\Throwable $e) {
            $results['actions']['config_clear'] = 'Error: ' . $e->getMessage();
        }

        // 6. Jalankan Migrasi jika diminta (?migrate=1)
        if ($request->input('migrate') == '1' || $request->input('migrate') == 'true') {
            try {
                Artisan::call('migrate', ['--force' => true]);
                $results['actions']['migration'] = trim(Artisan::output());
            } catch (\Throwable $e) {
                $results['actions']['migration'] = 'Error: ' . $e->getMessage();
            }
        }

        // Catat di log
        Log::info('CI/CD Auto-Deploy triggered successfully', [
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'actions' => $results['actions']
        ]);

        return response()->json($results);
    }
}

