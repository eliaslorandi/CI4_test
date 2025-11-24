<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\TaskModel;
use App\Models\CategoryModel;
use CodeIgniter\HTTP\ResponseInterface;

class TaskController extends BaseController
{
    protected $taskModel;
    protected $categoryModel;

    public function __construct()
    {
        $this->taskModel = model(TaskModel::class);
        $this->categoryModel = model(CategoryModel::class);
    }

    public function index(): ResponseInterface
    {
        $userId = session()->get('user_id');
        $tasks = $this->taskModel
            ->select('tasks.*, categories.name as category_name')
            ->join('categories', 'categories.id = tasks.category_id')
            ->where('tasks.user_id', $userId)
            ->orderBy('tasks.created_at', 'DESC')
            ->findAll();

        return $this->response->setJSON([
            'success' => true,
            'data' => $tasks,
        ]);
    }

    /**
     * @param int $id ID da tarefa.
     * @return ResponseInterface
     */
    public function show(int $id): ResponseInterface
    {
        $userId = session()->get('user_id');

        $task = $this->taskModel
            ->select('tasks.*, categories.name as category_name')
            ->join('categories', 'categories.id = tasks.category_id')
            ->where('tasks.id', $id)
            ->where('tasks.user_id', $userId)
            ->first();

        if (!$task) {
            return $this->response->setJSON([
                'error' => 'Tarefa não encontrada ou acesso negado.',
            ])->setStatusCode(404);
        }

        return $this->response->setJSON([
            'success' => true,
            'data' => $task,
        ]);
    }

    public function create()
    {
        if ($this->request->getMethod() === 'post') {
            return $this->store();
        }

        $userId = session()->get('user_id');
        $categories = $this->categoryModel->findAll();

        return view('tasks/create', ['categories' => $categories]);
    }

    /**
     * @return ResponseInterface
     */
    public function store(): ResponseInterface
    {
        $userId = session()->get('user_id');

        $data = [
            'user_id'      => $userId,
            'title'        => $this->request->getPost('title') ?? $this->request->getJSON('title'),
            'description'  => $this->request->getPost('description') ?? $this->request->getJSON('description'),
            'category_id'  => $this->request->getPost('category_id') ?? $this->request->getJSON('category_id'),
            'due_date'     => $this->request->getPost('due_date') ?? $this->request->getJSON('due_date'),
            'is_completed' => 0,
        ];

        if ($this->taskModel->save($data)) {
            $taskId = $this->taskModel->getInsertID();

            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Tarefa criada com sucesso!',
                    'data' => $this->taskModel->find($taskId),
                ]);
            }

            return redirect()->to(url_to('TaskController::index'))->with('success', 'Tarefa criada com sucesso!');
        } else {
            $errors = $this->taskModel->errors();

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
     * @param int $id ID da tarefa.
     */
    public function edit(int $id)
    {
        $userId = session()->get('user_id');

        $task = $this->taskModel
            ->where('id', $id)
            ->where('user_id', $userId)
            ->first();

        if (!$task) {
            return redirect()->to(url_to('TaskController::index'))->with('error', 'Tarefa não encontrada ou acesso negado.');
        }

        if ($this->request->getMethod() === 'post') {
            return $this->update($id);
        }

        $categories = $this->categoryModel->findAll();

        return view('tasks/edit', ['task' => $task, 'categories' => $categories]);
    }

    /**
     * @param int $id ID da tarefa.
     * @return ResponseInterface
     */
    public function update(int $id): ResponseInterface
    {
        $userId = session()->get('user_id');

        $existingTask = $this->taskModel
            ->where('id', $id)
            ->where('user_id', $userId)
            ->first();

        if (!$existingTask) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'error' => 'Tarefa não encontrada ou acesso negado.',
                ])->setStatusCode(404);
            }

            return redirect()->to(url_to('TaskController::index'))->with('error', 'Tarefa não encontrada ou acesso negado.');
        }

        $data = [
            'id'           => $id,
            'title'        => $this->request->getPost('title') ?? $this->request->getJSON('title'),
            'description'  => $this->request->getPost('description') ?? $this->request->getJSON('description'),
            'category_id'  => $this->request->getPost('category_id') ?? $this->request->getJSON('category_id'),
            'due_date'     => $this->request->getPost('due_date') ?? $this->request->getJSON('due_date'),
        ];

        if ($this->taskModel->save($data)) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Tarefa atualizada com sucesso!',
                    'data' => $this->taskModel->find($id),
                ]);
            }

            return redirect()->to(url_to('TaskController::index'))->with('success', 'Tarefa atualizada com sucesso!');
        } else {
            $errors = $this->taskModel->errors();

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
     * @param int $id ID da tarefa.
     * @return ResponseInterface
     */
    public function delete(int $id): ResponseInterface
    {
        $userId = session()->get('user_id');

        $task = $this->taskModel
            ->where('id', $id)
            ->where('user_id', $userId)
            ->first();

        if (!$task) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'error' => 'Tarefa não encontrada ou acesso negado.',
                ])->setStatusCode(404);
            }

            return redirect()->to(url_to('TaskController::index'))->with('error', 'Tarefa não encontrada ou acesso negado.');
        }

        if ($this->taskModel->delete($id)) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Tarefa deletada com sucesso!',
                ]);
            }

            return redirect()->to(url_to('TaskController::index'))->with('success', 'Tarefa deletada com sucesso!');
        } else {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'error' => 'Erro ao deletar tarefa.',
                ])->setStatusCode(500);
            }

            return redirect()->back()->with('error', 'Erro ao deletar tarefa.');
        }
    }

    /**
     * @param int $id ID da tarefa.
     * @return ResponseInterface
     */
    public function complete(int $id): ResponseInterface
    {
        $userId = session()->get('user_id');

        $task = $this->taskModel
            ->where('id', $id)
            ->where('user_id', $userId)
            ->first();

        if (!$task) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'error' => 'Tarefa não encontrada ou acesso negado.',
                ])->setStatusCode(404);
            }

            return redirect()->back()->with('error', 'Tarefa não encontrada ou acesso negado.');
        }

        if ($this->taskModel->completeTask($id)) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Tarefa marcada como concluída!',
                ]);
            }

            return redirect()->back()->with('success', 'Tarefa marcada como concluída!');
        } else {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'error' => 'Erro ao concluir tarefa.',
                ])->setStatusCode(500);
            }

            return redirect()->back()->with('error', 'Erro ao concluir tarefa.');
        }
    }

    /**
     * @param int $id ID da tarefa.
     * @return ResponseInterface
     */
    public function pending(int $id): ResponseInterface
    {
        $userId = session()->get('user_id');

        $task = $this->taskModel
            ->where('id', $id)
            ->where('user_id', $userId)
            ->first();

        if (!$task) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'error' => 'Tarefa não encontrada ou acesso negado.',
                ])->setStatusCode(404);
            }

            return redirect()->back()->with('error', 'Tarefa não encontrada ou acesso negado.');
        }

        if ($this->taskModel->uncompleteTask($id)) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Tarefa marcada como pendente!',
                ]);
            }

            return redirect()->back()->with('success', 'Tarefa marcada como pendente!');
        } else {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'error' => 'Erro ao marcar como pendente.',
                ])->setStatusCode(500);
            }

            return redirect()->back()->with('error', 'Erro ao marcar como pendente.');
        }
    }
}
