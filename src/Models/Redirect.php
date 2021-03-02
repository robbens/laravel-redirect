<?php

namespace Robbens\LaravelRedirect\Models;

use Illuminate\Database\Eloquent\Model;
use Robbens\LaravelRedirect\Contracts\RedirectModelContract;
use Robbens\LaravelRedirect\Exceptions\RedirectException;

class Redirect extends Model implements RedirectModelContract
{
    // Disable Laravel's mass assignment protection
    protected $guarded = [];

    public function setFromAttribute(string $value): void
    {
        $this->attributes['from'] = $this->cleanUrl($value);
    }

    public function setToAttribute(string $value): void
    {
        $this->attributes['to'] = $this->cleanUrl($value);
    }

    protected function cleanUrl(string $url): string
    {
        $parts = parse_url($url);
        $queryString = isset($parts['query']) ? '?' . $parts['query'] : '';
        $fragment = isset($parts['fragment']) ? '#' . $parts['fragment'] : '';

        return trim($parts['path'], '/') . $queryString . $fragment;
    }

    protected static function boot()
    {
        parent::boot();

        static::saving(function (self $model) {
            if (trim(strtolower($model->from), '/') == trim(strtolower($model->to), '/')) {
                throw RedirectException::sameUrls();
            }
        });
    }
}
