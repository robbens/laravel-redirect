<?php

namespace Robbens\LaravelRedirect\Tests;

use Robbens\LaravelRedirect\Exceptions\RedirectException;
use Robbens\LaravelRedirect\Models\Redirect;

class RedirectTest extends TestCase
{
    /** @test */
    public function it_redirects_a_request()
    {
        Redirect::create([
            'from' => 'redirect-from',
            'to' => 'redirect-to/url'
        ]);

        $res = $this->get('redirect-from');
        $res->assertRedirect('redirect-to/url');
    }

    /** @test */
    public function it_protects_against_redirecting_to_same_url()
    {
        $this->expectException(RedirectException::class);

        Redirect::create([
            'from' => 'same-url',
            'to' => 'same-url',
        ]);
    }
}
