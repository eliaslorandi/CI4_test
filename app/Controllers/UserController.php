<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;
use CodeIgniter\HTTP\ResponseInterface;

class UserController extends BaseController
{
    protected $userModel;
    protected $session;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->session = session();
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
            return redirect()->back()->with('error', 'As senhas não conferem.');
        }

        if ($this->userModel->save($data)) {
            return redirect()->to('/user/login')->with('success', 'Usuário registrado com sucesso!');
        } else {
            $errors = $this->userModel->errors();
            return redirect()->back()->withInput()->with('errors', $errors);
        }
    }

    public function login()
    {
        if ($this->request->getMethod() === 'post') {
            return $this->storeLogin();
        }

        return view('users/login');
    }

    private function storeLogin()
    {
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        $user = $this->userModel->findByEmail($email);

        if (!$user) {
            return redirect()->back()->with('error', 'Email ou senha incorretos.');
        }

        if (!$this->userModel->verifyPassword($password, $user['password'])) {
            return redirect()->back()->with('error', 'Email ou senha incorretos.');
        }

        $this->session->set([
            'user_id'   => $user['id'],
            'user_name' => $user['name'],
            'user_email' => $user['email'],
            'logged_in' => true,
        ]);

        return redirect()->to('/dashboard')->with('success', 'Bem-vindo ' . $user['name'] . '!');
    }

    public function logout()
    {
        $this->session->destroy();
        return redirect()->to('/user/login')->with('success', 'Você foi desconectado.');
    }

    //verificação de autenticação
    public function profile()
    {
        if (!$this->session->get('logged_in')) {
            return redirect()->to('/user/login');
        }

        $userId = $this->session->get('user_id');
        $user = $this->userModel->find($userId);

        return view('users/profile', ['user' => $user]);
    }

    public function edit()
    {
        if (!$this->session->get('logged_in')) {
            return redirect()->to('/user/login');
        }

        $userId = $this->session->get('user_id');
        $user = $this->userModel->find($userId);

        return view('users/edit', ['user' => $user]);
    }

    public function update()
    {
        if (!$this->session->get('logged_in')) {
            return redirect()->to('/user/login');
        }

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
            $data['password'] = $this->request->getPost('password');
        }

        if ($this->userModel->save($data)) {
            // Atualiza sessão
            $this->session->set([
                'user_name'  => $data['name'],
                'user_email' => $data['email'],
            ]);

            return redirect()->to('/user/profile')->with('success', 'Perfil atualizado com sucesso!');
        } else {
            $errors = $this->userModel->errors();
            return redirect()->back()->withInput()->with('errors', $errors);
        }
    }

    public function delete()
    {
        if (!$this->session->get('logged_in')) {
            return redirect()->to('/user/login');
        }

        $userId = $this->session->get('user_id');
        $password = $this->request->getPost('password');

        $user = $this->userModel->find($userId);

        if (!$this->userModel->verifyPassword($password, $user['password'])) {
            return redirect()->back()->with('error', 'Senha incorreta.');
        }

        if ($this->userModel->delete($userId)) {
            $this->session->destroy();
            return redirect()->to('/')->with('success', 'Conta deletada com sucesso.');
        } else {
            return redirect()->back()->with('error', 'Erro ao deletar conta.');
        }
    }
}
