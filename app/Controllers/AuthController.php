<?php namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;
use CodeIgniter\HTTP\ResponseInterface;

class AuthController extends BaseController
{
    protected $userModel;
    protected $session;

    public function __construct()
    {
        $this->session = \Config\Services::session();
        $this->userModel = model('UserModel');
    }

    public function register()
    {
        if ($this->request->getMethod() === 'POST' || strtoupper($this->request->getMethod()) === 'POST') {
            return $this->storeRegister();
        }

        return view('auth/register');
    }

    private function storeRegister()
    {
        try {
            $this->response->setHeader('Content-Type', 'application/json');

            $data = [
                'name'     => $this->request->getPost('name'),
                'email'    => $this->request->getPost('email'),
                'password' => $this->request->getPost('password'),
            ];

            $passwordConfirm = $this->request->getPost('password_confirm');
            if ($data['password'] !== $passwordConfirm) {
                log_message('debug', 'Password mismatch detected');
                return $this->response->setStatusCode(422)->setJSON([
                    'success' => false,
                    'errors' => [
                        'password_confirm' => 'As senhas não conferem.'
                    ]
                ]);
            }

            if (!$this->userModel->validate($data)) {
                $errors = $this->userModel->errors();
                log_message('error', 'Validation errors: ' . json_encode($errors));
                return $this->response->setStatusCode(422)->setJSON([
                    'success' => false,
                    'errors' => $errors
                ]);
            }

            if ($this->userModel->save($data)) {
                log_message('info', 'User registered: ' . $data['email']);
                
                $user = $this->userModel->findByEmail($data['email']);
                $this->session->set([
                    'logged_in' => true,
                    'user_id' => $user['id'],
                    'user_email' => $user['email'],
                    'user_name' => $user['name']
                ]);
                
                return $this->response->setStatusCode(200)->setJSON([
                    'success' => true,
                    'message' => 'Usuário registrado com sucesso!',
                    'redirect' => '/dashboard'
                ]);
            } else {
                $errors = $this->userModel->errors();
                log_message('error', 'Save error: ' . json_encode($errors));
                return $this->response->setStatusCode(500)->setJSON([
                    'success' => false,
                    'errors' => $errors ?? ['general' => 'Erro ao salvar usuário. Tente novamente.']
                ]);
            }
        } catch (\Exception $e) {
            log_message('error', 'Exception in storeRegister: ' . $e->getMessage());
            return $this->response->setStatusCode(500)->setJSON([
                'success' => false,
                'errors' => ['general' => 'Erro ao processar registro. Tente novamente.']
            ]);
        }
    }

    public function login()
    {
        if ($this->session->get('logged_in')) {
            return redirect()->to('/dashboard');
        }
        
        if ($this->request->getMethod() === 'POST' || strtoupper($this->request->getMethod()) === 'POST') {
            return $this->storeLogin();
        }

        return view('auth/login');
    }

    private function storeLogin()
    {
        try {
            $this->response->setHeader('Content-Type', 'application/json');

            $email = $this->request->getPost('email');
            $password = $this->request->getPost('password');
            $remember = $this->request->getPost('remember');

            $user = $this->userModel->findByEmail($email);

            if (!$user || !$this->userModel->verifyPassword($password, $user['password'])) {
                return $this->response->setStatusCode(401)->setJSON([
                    'success' => false,
                    'errors' => ['email' => 'Email ou senha incorretos.']
                ]);
            }

            $sessionData = [
                'user_id'    => $user['id'],
                'user_name'  => $user['name'],
                'user_email' => $user['email'],
                'logged_in'  => true,
            ];
            $this->session->set($sessionData);

            if ($remember) {
                $token = bin2hex(random_bytes(32)); 
                $this->userModel->update($user['id'], ['remember_token' => $token]);

                $cookieDuration = 3600 * 24 * 30;
                setcookie('remember_user_token', $token, time() + $cookieDuration, '/');
            }
            
            return $this->response->setStatusCode(200)->setJSON([
                'success' => true,
                'message' => 'Bem-vindo(a) ' . $user['name'] . '!',
                'redirect' => '/dashboard'
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Exception in storeLogin: ' . $e->getMessage());
            return $this->response->setStatusCode(500)->setJSON([
                'success' => false,
                'errors' => ['general' => 'Erro ao processar login. Tente novamente.']
            ]);
        }
    }

    public function logout()
    {
        if (isset($_COOKIE['remember_user_token'])) {
            $this->userModel->update($this->session->get('user_id'), ['remember_token' => null]);
            setcookie('remember_user_token', '', time() - 3600, '/');
        }
        
        $this->session->destroy();
        return redirect()->to(url_to('AuthController::login'))->with('success', 'Você foi desconectado.');
    }
}
