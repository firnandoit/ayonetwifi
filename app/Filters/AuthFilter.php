<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthFilter implements FilterInterface
{
    /**
     * Do whatever processing this filter needs to do.
     * By default it should not return anything during
     * normal execution. However, when an abnormal state
     * is found, it should return an instance of
     * CodeIgniter\HTTP\Response. If it does, script
     * execution will end and that Response will be
     * sent back to the client, allowing for error pages,
     * redirects, etc.
     *
     * @param RequestInterface $request
     * @param array|null       $arguments
     *
     * @return RequestInterface|ResponseInterface|string|void
     */
   public function before(RequestInterface $request, $arguments = null)
{
    // 1. Cek Login
    if (!session()->get('logged_in')) {
        return redirect()->to('/login');
    }

    $role = session()->get('role');
    $uri = service('uri');
    $segment = $uri->getTotalSegments() > 0 ? $uri->getSegment(1) : '';

    // 2. Proteksi Khusus Dashboard
    // Jika Customer memaksa masuk ke URL /dashboard
    if ($segment == 'dashboard' && $role === 'customer') {
        return redirect()->to('/customer/tagihan');
    }

    // 3. Pengecekan Hak Akses Dinamis (Berdasarkan Tabel DB)
    if ($segment && !in_array($segment, ['logout', 'profile'])) {
        $db = \Config\Database::connect();
        $hasAccess = $db->table('role_permissions')
                        ->join('menus', 'menus.id_menu = role_permissions.id_menu')
                        ->where('role_permissions.role', $role)
                        ->where('menus.slug', $segment)
                        ->countAllResults();

        if ($hasAccess == 0) {
            // Kita lempar ke halaman 404 sesuai keinginan Anda
            // Ini akan memanggil file app/Views/errors/html/error_404.php
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
    }
}
    /**
     * Allows After filters to inspect and modify the response
     * object as needed. This method does not allow any way
     * to stop execution of other after filters, short of
     * throwing an Exception or Error.
     *
     * @param RequestInterface  $request
     * @param ResponseInterface $response
     * @param array|null        $arguments
     *
     * @return ResponseInterface|void
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        //
    }
}
