<?php

namespace Robbens\LaravelRedirect\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Robbens\LaravelRedirect\Contracts\RedirectModelContract;

class Redirect extends Model implements RedirectModelContract
{
    // Disable Laravel's mass assignment protection
    protected $guarded = [];

    public function setFromAttribute($value)
    {
        $this->attributes['from'] = trim(parse_url($value)['path'], '/');;
    }

    public function setToAttribute($value)
    {
        $this->attributes['to'] = trim(parse_url($value)['path'], '/');;
    }
}