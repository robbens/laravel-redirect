<?php

namespace Robbens\LaravelRedirect;

use Robbens\LaravelRedirect\Models\Redirect;

class LaravelRedirect
{
    protected $fromUri;
    protected $toUri;

    public function from($uri)
    {
        $this->fromUri = $uri;

        return $this;
    }

    public function to($uri)
    {
        $this->toUri = $uri;

        return $this->create();
    }

    private function create() : Redirect
    {
        return app('redirect.model')->create([
            'from' => $this->fromUri,
            'to' => $this->toUri,
        ]);
    }
}
