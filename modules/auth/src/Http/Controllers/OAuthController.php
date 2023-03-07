<?php

namespace Ecommify\Auth\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;

class OAuthController extends Controller
{
    /**
     * Get provider authentication url
     *
     * @param string $provider
     * @param string $company
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAuthenticationUrl($provider, $company, Request $request)
    {
        $authUrl = Socialite::driver($provider)
            ->stateless()
            ->with(array_merge(['state' => $company], $request->all()))
            ->getAuthenticationUrl($company);

        return response()->json([
            'auth_url' => $authUrl
        ]);
    }

    /**
     * Obtain the user information from provider.
     *
     * @param string $provider
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function handleProviderCallback($provider, Request $request)
    {
        $driver = Socialite::driver($provider);

        try {
            $tokenResponse = $driver->stateless()->user()->accessTokenResponseBody;
        } catch (Exception $e) {
            Log::error($e->getMessage());
            $tokenResponse = [
                'error' => true
            ];
        }

        return view('auth::oauth-callback', [
            'response' => $tokenResponse
        ]);
    }
}
