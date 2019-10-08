<?php

namespace Robbens\LaravelRedirect\Middleware;

use App\Models\Redirect;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

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

        return redirect($redirect->to, $redirect->status ?? Response::HTTP_MOVED_PERMANENTLY);
    }

    protected function getRedirectInfo(Request $request)
    {
        $uri = $request->path();
        $uriWithQueryParams = trim($request->getRequestUri(), '/');

        $redirect = Redirect::where('from', urldecode($uriWithQueryParams))
            ->orWhere('from', urldecode($uri))
            ->first();

        return $redirect;
    }
}
