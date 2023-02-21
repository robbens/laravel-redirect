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
            'to' => 'redirect-to/url',
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

    /** @test */
    public function it_can_save_a_query_string()
    {
        $redirect = Redirect::create([
            'from' => '/home?lang=en',
            'to' => '/en/home',
        ]);

        $this->assertEquals('home?lang=en', $redirect->from);
    }

    /** @test */
    public function it_can_redirect_a_query_string()
    {
        Redirect::create([
            'from' => '/home?lang=en&page=2',
            'to' => '/en/home?page=2',
        ]);

        $this->get('/home?lang=en&page=2')
            ->assertRedirect('/en/home?page=2');
    }

    /** @test */
    public function it_can_redirect_a_internal_link()
    {
        Redirect::create([
            'from' => '/contact-us/',
            'to' => '/home/#contact-us',
        ]);

        $this->get('/contact-us')
            ->assertRedirect('/home#contact-us');
    }

    /** @test */
    public function it_can_redirect_to_absolute_url()
    {
        Redirect::create([
            'from' => '/contact-us/',
            'to' => 'https://google.com',
        ]);

        $this->get('/contact-us')
            ->assertRedirect('https://google.com');
    }

    /** @test */
    public function it_can_not_redirect_from_absolute_url()
    {
        Redirect::create([
            'from' => 'https://google.com/foo',
            'to' => '/contact-us',
        ]);

        $this->get('/foo')
            ->assertRedirect('/contact-us');
    }
}
