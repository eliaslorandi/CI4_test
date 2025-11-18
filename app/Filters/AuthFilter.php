<?php namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\UserModel;

/**
 * Filtro de Autenticação:
 * 1. Verifica se o usuário está logado via Session.
 * 2. Se não estiver, verifica se há um cookie "Lembrar-me" válido.
 * 3. Se o cookie for válido, loga o usuário e continua a requisição.
 * 4. Se não estiver logado nem puder ser lembrado, redireciona para a página de login.
 */
class AuthFilter implements FilterInterface
{
    /**
     * Lógica executada antes do Controller ser chamado.
     *
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
            
            // Busca o usuário pelo token
            $user = $userModel->where('remember_token', $rememberToken)->first();

            if ($user) {
                // Se o token for válido: loga o usuário automaticamente
                $sessionData = [
                    'user_id'    => $user['id'],
                    'user_name'  => $user['name'],
                    'user_email' => $user['email'],
                    'logged_in'  => true,
                ];
                $session->set($sessionData);
                
                // Opcional: Renovação do token (para estender a duração do login)
                // Re-salva o cookie com o tempo de expiração original (30 dias)
                $cookieDuration = 3600 * 24 * 30;
                setcookie('remember_user_token', $rememberToken, time() + $cookieDuration, '/');

                return; // Usuário logado via cookie, continua a requisição.
            }
        }

        // 3. NÃO ESTÁ LOGADO NEM FOI LEMBRADO: Redireciona para o login
        session()->setFlashdata('error', 'Você precisa estar logado para acessar esta página.');
        return redirect()->to(url_to('UserController::login'));
    }

    /**
     * Lógica executada depois que o Controller terminou.
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