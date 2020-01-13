<?php

namespace Robbens\LaravelRedirect\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Robbens\LaravelRedirect\Models\Redirect;

class RedirectsMissingPages
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        if (!$this->shouldRedirect($response)) {
            return $response;
        }

        $redirectResponse = $this->redirectResponse($request);

        return $redirectResponse ?? $response;
    }

    protected function shouldRedirect($response): bool
    {
        return $response->getStatusCode() === Response::HTTP_NOT_FOUND;
    }

    protected function redirectResponse(Request $request)
    {
        $redirect = $this->getRedirectInfo($request);

        if (!$redirect) {
            return null;
        }

        $to = trim($redirect->to, '/');

        return redirect($to, $redirect->status ?? Response::HTTP_MOVED_PERMANENTLY);
    }

    protected function getRedirectInfo(Request $request)
    {
        $uri = $request->path();
        $uriWithQueryParams = $uri . '?' . $request->getQueryString();

        $redirect = Redirect::where('from', urldecode($uriWithQueryParams))
            ->orWhere('from', $uriWithQueryParams)
            ->first();

        if (!$redirect) {
            // Check without querystring
            $redirect = Redirect::where('from', urldecode($uri))
                ->orWhere('from', $uri)
                ->first();
        }

        return $redirect;
    }
}
