<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CategoryModel;
use CodeIgniter\HTTP\ResponseInterface;

class CategoryController extends BaseController
{
    /**
     * @var CategoryModel
     */
    protected $categoryModel;

    public function __construct()
    {
        $this->categoryModel = model(CategoryModel::class);
    }

    /**
     * Lista todas as categorias pertencentes ao usuário logado.
     * @return ResponseInterface
     */
    public function index(): ResponseInterface
    {
        $userId = session()->get('user_id');

        $categories = $this->categoryModel->getCategoriesWithTasksCount($userId);

        return $this->response->setJSON([
            'success' => true,
            'data' => $categories,
        ]);
    }

    /**
     * Armazena uma nova categoria no banco de dados, associando-a ao usuário logado.
     * @return ResponseInterface
     */
    public function store(): ResponseInterface
    {
        $userId = session()->get('user_id');
        
        // Coleta dados compatível com Post e JSON/API
        $data = [
            'user_id'    => $userId, // CRUCIAL para a segurança e validação
            'name'       => $this->request->getPost('name') ?? $this->request->getJSON('name'),
            'color_code' => $this->request->getPost('color_code') ?? $this->request->getJSON('color_code'),
        ];
        
        if ($this->categoryModel->save($data)) {
            $categoryId = $this->categoryModel->getInsertID();

            // Resposta JSON para API/AJAX (Retorna o novo objeto completo)
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Categoria criada com sucesso!',
                    'data' => $this->categoryModel->find($categoryId),
                ]);
            }
            
            return redirect()->back()->with('success', 'Categoria criada com sucesso!');
        } else {
            // Tratamento de Erro de Validação (Status 422 - Unprocessable Entity)
            $errors = $this->categoryModel->errors();
            
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'errors' => $errors,
                ])->setStatusCode(422);
            }
            
            return redirect()->back()->withInput()->with('errors', $errors);
        }
    }

    /**
     * Exibe uma categoria específica, garantindo a posse.
     * @param int $id ID da categoria.
     * @return ResponseInterface
     */
    public function show(int $id): ResponseInterface
    {
        $userId = session()->get('user_id');
        
        $category = $this->categoryModel
            ->where('id', $id)
            ->where('user_id', $userId) 
            ->first();

        if (!$category) {
            return $this->response->setJSON(['error' => 'Categoria não encontrada ou acesso negado.'])->setStatusCode(404);
        }

        return $this->response->setJSON([
            'success' => true,
            'data' => $category
        ]);
    }


    /**
     * Atualiza uma categoria existente, garantindo a posse.
     * @param int $id ID da categoria.
     * @return ResponseInterface
     */
    public function update(int $id): ResponseInterface
    {
        $userId = session()->get('user_id');

        $existingCategory = $this->categoryModel
            ->where('id', $id)
            ->where('user_id', $userId)
            ->first();

        if (!$existingCategory) {
            return $this->response->setJSON(['error' => 'Categoria não encontrada ou acesso negado.'])->setStatusCode(404);
        }

        $data = [
            'id'            => $id,
            'user_id'       => $userId,
            'name'          => $this->request->getPost('name') ?? $this->request->getJSON('name'),
            'color_code'    => $this->request->getPost('color_code') ?? $this->request->getJSON('color_code'),
        ];
        
        if ($this->categoryModel->save($data)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Categoria atualizada com sucesso!',
                'data' => $this->categoryModel->find($id),
            ]);
        } else {
            $errors = $this->categoryModel->errors();
            
            return $this->response->setJSON([
                'success' => false,
                'errors' => $errors,
            ])->setStatusCode(422);
        }
    }

    /**
     * Deleta uma categoria específica, garantindo a posse.
     * @param int $id ID da categoria.
     * @return ResponseInterface
     */
    public function delete(int $id): ResponseInterface
    {
        $userId = session()->get('user_id');

        $category = $this->categoryModel
            ->where('id', $id)
            ->where('user_id', $userId)
            ->first();

        if (!$category) {
            return $this->response->setJSON(['error' => 'Categoria não encontrada ou acesso negado.'])->setStatusCode(404);
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