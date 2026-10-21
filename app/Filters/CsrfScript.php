<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * Adds public/common/js/csrf.js to every HTML page, so its forms and AJAX calls carry the CSRF token the csrf filter
 * checks (Config/Filters.php) without changing each view.
 */
class CsrfScript implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        return null;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        $type = $response->getHeaderLine('Content-Type');
        if ($type !== '' && stripos($type, 'text/html') === false) {
            return null;
        }

        // whole pages only; a fragment loaded into a page is covered by that page's script
        $body = $response->getBody();
        if (! is_string($body) || ! preg_match('/<head(\s[^>]*)?>/i', $body, $m, PREG_OFFSET_CAPTURE)) {
            return null;
        }

        $at = $m[0][1] + strlen($m[0][0]);

        return $response->setBody(substr($body, 0, $at) . '<script src="/common/js/csrf.js?v=1"></script>' . substr($body, $at));
    }
}
