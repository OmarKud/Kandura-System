<?php

use App\Http\Middleware\CheckAdmin;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Spatie\Permission\Exceptions\UnauthorizedException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


return Application::configure(basePath: dirname(__DIR__))
  ->withRouting(
    web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
    commands: __DIR__.'/../routes/console.php',
    health: '/up',
  )
  ->withMiddleware(function (Middleware $middleware) {
     $middleware->alias([
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
        ]);
    //
  })
  ->withExceptions(function (Exceptions $exceptions) {
    
$exceptions->render(function (Exception $e,  $request) {
            // For API routes - return JSON
            if ($request->is('api/*')) {
                return response()->json([
                    'message' => $e->getMessage(),
                    'data' => null,
                ], 404);
            }

            // For web routes - return custom view
            // return response()->view('errors.404', [
            //     'message' => 'Page not found',
            // ], 404);
        });
        $exceptions->render(function (UnauthorizedException $e, Request $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage(),
                    'required' => $e->getRequiredRoles()
                        ?? $e->getRequiredPermissions(),
                ], 403);
            }
        });
    })->create();