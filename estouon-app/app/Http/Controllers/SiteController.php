<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Route;

use Inertia\Inertia;
use Inertia\Response;

class SiteController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function index(Request $request): Response
    {
        //gera o caminho das imagens pro front
        $imageUrls = [
            'logo' => asset('/conheca/assets/img/logowhite.png'),
            'favicon' => asset('/conheca/assets/img/favicon.png'),
            'apple-touch-icon' => asset('/conheca/assets/img/apple-touch-icon.png'),
            'hero' => asset('/conheca/assets/img/hero-img.png'),
        ];

        $canLogin = Route::has('login');
        $canRegister = Route::has('register');

        $urlExternas = [
            'estouOn' => url('https://conheca.estouon.app.br/'),
        ];

        return Inertia::render('Conheca/Index', [
            'canLogin' => $canLogin,
            'canRegister' => $canRegister,
            'imageUrls' => $imageUrls,
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
