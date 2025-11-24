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
        if ($this->request->getMethod() === 'post') {
            return $this->storeRegister();
        }

        return view('auth/register');
    }

    private function storeRegister()
    {
        $data = [
            'name'     => $this->request->getPost('name'),
            'email'    => $this->request->getPost('email'),
            'password' => $this->request->getPost('password'),
        ];

        $passwordConfirm = $this->request->getPost('password_confirm');
        if ($data['password'] !== $passwordConfirm) {
            return redirect()->back()->withInput()->with('error', 'As senhas não conferem.');
        }

        if ($this->userModel->save($data)) {
            return redirect()->to(url_to('AuthController::login'))->with('success', 'Usuário registrado com sucesso! Faça login.');
        } else {
            $errors = $this->userModel->errors();
            return redirect()->back()->withInput()->with('errors', $errors);
        }
    }

    public function login()
    {
        if ($this->session->get('logged_in')) {
            return redirect()->to('/dashboard');
        }
        
        if ($this->request->getMethod() === 'post') {
            return $this->storeLogin();
        }

        return view('auth/login');
    }

    private function storeLogin()
    {
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');
        $remember = $this->request->getPost('remember');

        $user = $this->userModel->findByEmail($email);

        if (!$user || !$this->userModel->verifyPassword($password, $user['password'])) {
            return redirect()->back()->withInput()->with('error', 'Email ou senha incorretos.');
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
        
        return redirect()->to('/dashboard')->with('success', 'Bem-vindo(a) ' . $user['name'] . '!');
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
