<?php

namespace Robbens\LaravelRedirect\Contracts;

interface RedirectModelContract
{
    /**
     * @param string $value
     */
    public function setFromAttribute(string $value): void;

    /**
     * @param string $value
     */
    public function setToAttribute(string $value): void;
}
