<?php

namespace App\Providers;


use App\Models\Sanctum\PersonalAccessToken;
use App\Models\V2\User;
use Illuminate\Auth\AuthManager;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Http\Request;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        /** @var $auth AuthManager */

        $auth = $this->app['auth'];
        $auth->viaRequest('api', function (Request $request) {

            if ($request->bearerToken()) {
                $token = PersonalAccessToken::findToken($request->bearerToken());

                if ($token) {
                    $info = User::query()
                        ->where([
                            ['id', $token->getAttribute('tokenable_id')],
                            ['status', true]
                        ])
                        ->distinct()->first();

                    if ($info) {
                        $info->withAccessToken($token);

                        return $info;
                    }
                }
            }

            return null;
        });


    }
}
