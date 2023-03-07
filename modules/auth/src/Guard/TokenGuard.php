<?php

namespace Ecommify\Auth\Guard;

use Illuminate\Auth\TokenGuard as BaseTokenGuard;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

class TokenGuard extends BaseTokenGuard
{
    /**
     * Get the currently authenticated user.
     *
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function user()
    {
        // If we've already retrieved the user for the current request we can just
        // return it back immediately. We do not want to fetch the user data on
        // every call to this method because that would be tremendously slow.
        if (!is_null($this->user)) {
            return $this->user;
        }

        $user = null;

        $token = $this->getTokenForRequest();

        if (!empty($token)) {
            $user = $this->provider->createModel()
                ->newQuery()
                ->whereHas('tokens', function (Builder $builder) use ($token) {
                    $builder->where('token_value', $token)
                        ->where('expire_at', '>', Carbon::now()->getTimestamp());
                })
                ->first();
        }

        return $this->user = $user;
    }
}
