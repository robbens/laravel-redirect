<?php

namespace Robbens\LaravelRedirect\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Robbens\LaravelRedirect\Models\RedirectRegex;
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
        $path = $request->path();
        $uri = $path;

        if ($queryString = $request->getQueryString()) {
            $uri .= '?' . $queryString;
        }

        $model = config('redirects.model');

        /**
         * Check for redirects
         */
        $redirectModel = $model::where('from', urldecode($uri))
            ->orWhere('from', $uri)
            ->first();

        /**
         * Check for redirects with querystring
         */
        if (!$redirectModel) {
            $redirectModel = $model::where('from', urldecode($path))
                ->orWhere('from', $path)
                ->first();
        }

        /**
         * Check for wildcard redirects
         */
        if (!$redirectModel) {
            $wildcardRedirects = $model::where('regex', true)->get();

            $wildcardRedirects->each(function ($wildcardRedirect) use ($uri, &$redirectModel) {
                $redirectRegex = new RedirectRegex($wildcardRedirect->from);

                if ($redirectRegex->isMatch($uri)) {
                    $redirectModel = $wildcardRedirect;
                    $redirectModel->to = $redirectRegex->replace($redirectModel->to, $uri);

                    return false;
                }

                return true;
            });
        }

        return $redirectModel;
    }
}
