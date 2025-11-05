<?php
// FILE: app/Http/Middleware/CheckRole.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
  /**
   * Handle an incoming request.
   *
   * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
   */
  public function handle(Request $request, Closure $next, ...$roles): Response
  {
    if (!auth()->check()) {
      return redirect()->route('login');
    }

    $user = auth()->user();

    // Check if user has any of the specified roles
    if (in_array($user->role, $roles)) {
      return $next($request);
    }

    // Redirect based on user role
    return $this->redirectBasedOnRole($user->role);
  }

  private function redirectBasedOnRole($role)
  {
    switch ($role) {
      case 'admin':
        return redirect()->route('admin.dashboard')
          ->with('error', 'Anda tidak memiliki akses ke halaman tersebut');
      case 'ustadz':
        return redirect()->route('ustadz.dashboard')
          ->with('error', 'Anda tidak memiliki akses ke halaman tersebut');
      case 'santri':
        return redirect()->route('santri.dashboard')
          ->with('error', 'Anda tidak memiliki akses ke halaman tersebut');
      default:
        return redirect()->route('login')
          ->with('error', 'Akses ditolak');
    }
  }
}
