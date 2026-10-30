<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/** Chặn truy cập nếu quyền của tài khoản không nằm trong danh sách. Dùng: ->middleware('role:ROLE_ADMIN,ROLE_STAFF'). */
class EnsureRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->route('login');
        }

        abort_unless(in_array($user->Quyen, $roles, true), 403, 'Bạn không có quyền truy cập trang này.');

        return $next($request);
    }
}
