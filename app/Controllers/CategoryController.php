<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CategoryModel;
use CodeIgniter\HTTP\ResponseInterface;

class CategoryController extends BaseController
{
    protected $categoryModel;
    protected $session;

    public function __construct()
    {
        $this->categoryModel = new CategoryModel();
        $this->session = session();
    }

    /**
     * Get all categories as JSON
     */
    public function index()
    {
        if (!$this->session->get('logged_in')) {
            return $this->response->setJSON(['error' => 'Não autenticado'])->setStatusCode(401);
        }

        $categories = $this->categoryModel->findAll();

        return $this->response->setJSON([
            'success' => true,
            'data' => $categories,
        ]);
    }

    /**
     * Get categories for dropdown/select
     */
    public function getCategories()
    {
        if (!$this->session->get('logged_in')) {
            return $this->response->setJSON(['error' => 'Não autenticado'])->setStatusCode(401);
        }

        $categories = $this->categoryModel->findAll();

        return $this->response->setJSON([
            'success' => true,
            'data' => $categories,
        ]);
    }

    /**
     * Show create category form
     */
    public function create()
    {
        if (!$this->session->get('logged_in')) {
            return redirect()->to('/user/login');
        }

        if ($this->request->getMethod() === 'post') {
            return $this->store();
        }

        return view('categories/create');
    }

    /**
     * Store new category
     */
    public function store()
    {
        if (!$this->session->get('logged_in')) {
            return redirect()->to('/user/login');
        }

        $data = [
            'name' => $this->request->getPost('name') ?? $this->request->getJSON('name'),
        ];

        if ($this->categoryModel->save($data)) {
            // Se for AJAX, retorna JSON
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Categoria criada com sucesso!',
                    'data' => ['id' => $this->categoryModel->getInsertID(), 'name' => $data['name']],
                ]);
            }
            // Caso contrário, redireciona
            return redirect()->back()->with('success', 'Categoria criada com sucesso!');
        } else {
            // Se for AJAX, retorna erro JSON
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'errors' => $this->categoryModel->errors(),
                ])->setStatusCode(422);
            }
            // Caso contrário, redireciona com erro
            return redirect()->back()->withInput()->with('errors', $this->categoryModel->errors());
        }
    }

    /**
     * Show edit category form
     */
    public function edit(int $id)
    {
        if (!$this->session->get('logged_in')) {
            return redirect()->to('/user/login');
        }

        $category = $this->categoryModel->find($id);

        if (!$category) {
            return redirect()->back()->with('error', 'Categoria não encontrada.');
        }

        if ($this->request->getMethod() === 'post') {
            return $this->update($id);
        }

        return view('categories/edit', ['category' => $category]);
    }

    /**
     * Update category
     */
    public function update(int $id)
    {
        if (!$this->session->get('logged_in')) {
            return redirect()->to('/user/login');
        }

        $category = $this->categoryModel->find($id);

        if (!$category) {
            return redirect()->back()->with('error', 'Categoria não encontrada.');
        }

        $data = [
            'id'   => $id,
            'name' => $this->request->getPost('name') ?? $this->request->getJSON('name'),
        ];

        if ($this->categoryModel->save($data)) {
            // Se for AJAX, retorna JSON
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Categoria atualizada com sucesso!',
                ]);
            }
            // Caso contrário, redireciona
            return redirect()->back()->with('success', 'Categoria atualizada com sucesso!');
        } else {
            // Se for AJAX, retorna erro JSON
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'errors' => $this->categoryModel->errors(),
                ])->setStatusCode(422);
            }
            // Caso contrário, redireciona com erro
            return redirect()->back()->withInput()->with('errors', $this->categoryModel->errors());
        }
    }

    /**
     * Delete category (via AJAX/API)
     */
    public function delete(int $id)
    {
        if (!$this->session->get('logged_in')) {
            return $this->response->setJSON(['error' => 'Não autenticado'])->setStatusCode(401);
        }

        $category = $this->categoryModel->find($id);

        if (!$category) {
            return $this->response->setJSON(['error' => 'Categoria não encontrada'])->setStatusCode(404);
        }

        if ($this->categoryModel->delete($id)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Categoria deletada com sucesso!',
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'error' => 'Erro ao deletar categoria.',
            ])->setStatusCode(500);
        }
    }
}
