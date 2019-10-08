<?php

namespace Robbens\LaravelRedirect\Contracts;

interface RedirectModelContract
{
    /**
     * @param string $value
     */
    public function setFromAttribute($value);

    /**
     * @param string $value
     */
    public function setToAttribute($value);
}
