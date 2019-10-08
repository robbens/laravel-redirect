<?php

namespace Robbens\LaravelRedirect;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Robbens\LaravelRedirect\Skeleton\SkeletonClass
 */
class LaravelRedirectFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'laravel-redirect';
    }
}
