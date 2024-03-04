<?php

namespace Robbens\LaravelRedirect\Models;

/**
 * Regular expression helper
 */
class RedirectRegex
{
    private $pattern;
    private $case;

    public function __construct($pattern, $caseInsensitive = false)
    {
        $this->pattern = rawurldecode($pattern);
        $this->case = $caseInsensitive;
    }

    /**
     * Does $target match the regex pattern, applying case insensitivity if set.
     *
     * Note: if the pattern is invalid it will not match
     *
     * @param string $target Text to match the regex against.
     * @return boolean match
     */
    public function isMatch(string $target): bool
    {
        return preg_match($this->getRegex(), $target) > 0;
    }

    private function encodePath($path)
    {
        return str_replace(' ', '%20', $path);
    }

    private function encodeQuery($path)
    {
        return str_replace(' ', '+', $path);
    }

    /**
     * Regex replace the current pattern with $replace_pattern, applied to $target
     *
     * Note: if the pattern is invalid it will return $target
     *
     * @param string $replacePattern The regex replace pattern.
     * @param string $target Text to match the regex against.
     * @return string Replaced text
     */
    public function replace(string $replacePattern, string $target): string
    {
        $regex = $this->getRegex();
        $result = preg_replace($regex, $replacePattern, $target);

        if (is_null($result)) {
            return $target;
        }

        // Space encode the target
        $split = explode('?', $result);
        if (count($split) === 2) {
            $result = implode('?', [$this->encodePath($split[0]), $this->encodeQuery($split[1])]);
        } else {
            $result = $this->encodePath($result);
        }

        return $result;
    }

    private function getRegex(): string
    {
        $atEscaped = str_replace('@', '\\@', $this->pattern);
        $case = '';

        if ($this->isIgnoreCase()) {
            $case = 'i';
        }

        return '@' . $atEscaped . '@s' . $case;
    }

    public function isIgnoreCase()
    {
        return $this->case;
    }
}
