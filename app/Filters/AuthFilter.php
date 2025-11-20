<?php namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\UserModel;

class AuthFilter implements FilterInterface
{
    /**
     * @param RequestInterface $request
     * @param array|null       $arguments
     *
     * @return ResponseInterface|void
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = \Config\Services::session();
        
        if ($session->get('logged_in')) {
            return;
        }

        $rememberToken = $request->getCookie('remember_user_token');
        
        if ($rememberToken) {
            $userModel = model('UserModel');
            $user = $userModel->where('remember_token', $rememberToken)->first();

            if ($user) {
                $sessionData = [
                    'user_id'    => $user['id'],
                    'user_name'  => $user['name'],
                    'user_email' => $user['email'],
                    'logged_in'  => true,
                ];
                $session->set($sessionData);
                $cookieDuration = 3600 * 24 * 30;
                setcookie('remember_user_token', $rememberToken, time() + $cookieDuration, '/');

                return;
            }
        }

        // 3. NÃO ESTÁ LOGADO NEM FOI LEMBRADO: Redireciona para o login
        session()->setFlashdata('error', 'Você precisa estar logado para acessar esta página.');
        return redirect()->to(url_to('UserController::login'));
    }

    /**
     * @param RequestInterface $request
     * @param ResponseInterface $response
     * @param array|null $arguments
     * @return void
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Não há lógica 'after' necessária para este filtro de autenticação.
    }
}