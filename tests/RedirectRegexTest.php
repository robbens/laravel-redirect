<?php

namespace Robbens\LaravelRedirect\Tests;

use Robbens\LaravelRedirect\Exceptions\RedirectException;
use Robbens\LaravelRedirect\Models\Redirect;

class RedirectRegexTest extends TestCase
{

    /** @test */
    public function it_can_redirect_a_wildcard()
    {
        Redirect::create([
            'from' => '/home/(.*)',
            'to' => '/home-new/$1',
            'regex' => true,
        ]);

        $this->get('/home/foo')
            ->assertRedirect('/home-new/foo');

        $this->get('/home/foo?bar=baz')
            ->assertRedirect('/home-new/foo?bar=baz');

        $this->get('/home/foo/bar')
            ->assertRedirect('/home-new/foo/bar');
    }

    /** @test */
    public function it_can_redirect_a_nested_wildcard()
    {
        Redirect::create([
            'from' => '/ugly/long/slug/(.*)',
            'to' => '/clean/locations/$1',
            'regex' => true,
        ]);

        $this->get('/ugly/long/slug/new-york')
            ->assertRedirect('/clean/locations/new-york');
    }

    /** @test */
    public function it_does_not_redirect_a_wildcard_without_regex()
    {
        Redirect::create([
            'from' => '/home/(.*)',
            'to' => '/home-new/$1',
        ]);

        $this->get('/home/foo')->assertStatus(404);
    }
}
