<?php

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function(){
            Route::middleware('web')->group(base_path('routes/admin.php'));
        }
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->trustProxies(at: '*');
        $middleware->append(\App\Http\Middleware\SecurityHeaders::class);
        $middleware->append(\App\Http\Middleware\EnforceSinglePrivilegedSession::class);
        $middleware->validateCsrfTokens(except: [
            'webhooks/paymongo',
        ]);
        $middleware->alias([
            'admin'              => \App\Http\Middleware\IsAdmin::class,
            'customer'           => \App\Http\Middleware\RedirectIfStaffOrAdmin::class,
            'role'               => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission'         => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $renderAdminStatus = static function (Request $request, int $statusCode, ?string $exceptionMessage = null) {
            if (! $request->is('admin') && ! $request->is('admin/*')) {
                return null;
            }

            $defaultMessage = match ($statusCode) {
                404 => 'The requested admin resource was not found.',
                default => 'You are not authorized to perform this action.',
            };
            $responseMessage = trim((string) $exceptionMessage) !== '' ? $exceptionMessage : $defaultMessage;

            if ($request->expectsJson()) {
                return response()->json([
                    'message' => $responseMessage,
                ], $statusCode);
            }

            $previousUrl = url()->previous();
            $currentUrl = $request->fullUrl();
            $fallbackUrl = route('home');
            $targetUrl = ($previousUrl && $previousUrl !== $currentUrl) ? $previousUrl : $fallbackUrl;

            return redirect()->to($targetUrl)->with([
                'toast_type' => 'error',
                'toast_message' => $defaultMessage,
            ]);
        };

        $exceptions->render(function (AuthorizationException $exception, Request $request) use ($renderAdminStatus) {
            return $renderAdminStatus($request, 403, $exception->getMessage());
        });

        $exceptions->render(function (HttpExceptionInterface $exception, Request $request) use ($renderAdminStatus) {
            if (! in_array($exception->getStatusCode(), [403, 404, 505], true)) {
                return null;
            }

            return $renderAdminStatus($request, $exception->getStatusCode(), $exception->getMessage());
        });
    })->create();
