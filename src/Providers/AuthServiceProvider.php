<?php

namespace ExclusiveDev\Commenist\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use ExclusiveDev\Commenist\Policies\CommentPolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register any application authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        Gate::resource('comments', CommentPolicy::class, [
            'delete' => 'delete',
            'reply' => 'reply',
            'edit' => 'edit',
            'vote' => 'vote',
            'store' => 'store'
        ]);
    }
}