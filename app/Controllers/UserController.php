<?php namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;
use CodeIgniter\HTTP\ResponseInterface;

class UserController extends BaseController
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

        return view('users/register');
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
            return redirect()->to(url_to('UserController::login'))->with('success', 'Usuário registrado com sucesso! Faça login.');
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

        return view('users/login');
    }

    private function storeLogin()
    {
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');
        $remember = $this->request->getPost('remember'); //checkbox Lembrar-me

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

            //cookie persistente por 30 dias
            $cookieDuration = 3600 * 24 * 30;
            setcookie('remember_user_token', $token, time() + $cookieDuration, '/');
        }
        
        return redirect()->to('/dashboard')->with('success', 'Bem-vindo(a) ' . $user['name'] . '!');
    }

    public function logout()
    {
        // Se o usuário tinha um cookie "Lembrar-me", ele deve ser removido.
        if (isset($_COOKIE['remember_user_token'])) {
            //O user_id deve estar na sessão para que esta linha funcione
            $this->userModel->update($this->session->get('user_id'), ['remember_token' => null]);
            
            // Remove o cookie do navegador
            setcookie('remember_user_token', '', time() - 3600, '/');
        }
        
        $this->session->destroy();
        return redirect()->to(url_to('UserController::login'))->with('success', 'Você foi desconectado.');
    }

    // Métodos abaixo serão protegidos pelo AuthFilter
    
    public function profile()
    {
        // VERIFICAÇÃO DE AUTENTICAÇÃO REMOVIDA PELO AuthFilter
        
        $userId = $this->session->get('user_id');
        $user = $this->userModel->find($userId);

        return view('users/profile', ['user' => $user]);
    }

    public function edit()
    {
        $userId = $this->session->get('user_id');
        $user = $this->userModel->find($userId);

        return view('users/edit', ['user' => $user]);
    }

    public function update()
    {
        $userId = $this->session->get('user_id');

        $data = [
            'id'    => $userId,
            'name'  => $this->request->getPost('name'),
            'email' => $this->request->getPost('email'),
        ];

        // senha só é atualizada se preenchida
        if ($this->request->getPost('password')) {
            $passwordConfirm = $this->request->getPost('password_confirm');
            if ($this->request->getPost('password') !== $passwordConfirm) {
                return redirect()->back()->with('error', 'As senhas não conferem.');
            }
            // A senha será hasheada automaticamente no Model (hook beforeUpdate)
            $data['password'] = $this->request->getPost('password');
        }

        if ($this->userModel->save($data)) {
            // Atualiza sessão
            $this->session->set([
                'user_name'  => $data['name'],
                'user_email' => $data['email'],
            ]);

            return redirect()->to(url_to('UserController::profile'))->with('success', 'Perfil atualizado com sucesso!');
        } else {
            $errors = $this->userModel->errors();
            return redirect()->back()->withInput()->with('errors', $errors);
        }
    }

    public function delete()
    {
        $userId = $this->session->get('user_id');
        $password = $this->request->getPost('password');

        $user = $this->userModel->find($userId);
        
        if (!$this->userModel->verifyPassword($password, $user['password'])) {
            return redirect()->back()->with('error', 'Senha incorreta.');
        }

        if ($this->userModel->delete($userId)) {
            $this->session->destroy();
            
            // Remove o cookie "Lembrar-me" se existir
            if (isset($_COOKIE['remember_user_token'])) {
                setcookie('remember_user_token', '', time() - 3600, '/');
            }
            
            return redirect()->to('/')->with('success', 'Conta deletada com sucesso.');
        } else {
            return redirect()->back()->with('error', 'Erro ao deletar conta.');
        }
    }
}