<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Services\AuthService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class RegisterController extends Controller
{
    public function __construct(private AuthService $auth) {}

    public function create(): View
    {
        return view('customers.auth.register');
    }

    public function store(RegisterRequest $request): RedirectResponse
    {
        $d = $request->validated();
        $r = $this->auth->dangKy($d);

        return redirect()->route('otp.form', ['token' => $r['token'], 'type' => 'register'])
            ->with('info', $this->auth->thongBaoOtp($d['phone'], $r));
    }
}
