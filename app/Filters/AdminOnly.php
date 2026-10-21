<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * The back office (Xilin_ns_admin/*, Config/Routes.php): without a signed-in admin, go to the admin sign-in page.
 *
 * Its pages also change data on GET links (delete, copy, ...), which CSRF tokens don't cover, so a link followed from
 * another site (Sec-Fetch-Site: cross-site) only gets the admin home page.
 */
class AdminOnly implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        if (! session('name') || ! session('id') || ! session('group_id')) {
            return redirect()->to('/Xilin_ns_admin/login');
        }

        if ($request->getHeaderLine('Sec-Fetch-Site') === 'cross-site') {
            return redirect()->to('/Xilin_ns_admin/index');
        }

        return null;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        return null;
    }
}
