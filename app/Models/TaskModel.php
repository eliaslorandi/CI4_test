<?php namespace App\Models;

use CodeIgniter\Model;

class TaskModel extends Model
{
    protected $table              = 'tasks';
    protected $primaryKey         = 'id';
    protected $useAutoIncrement   = true;
    protected $returnType         = 'array';
    protected $useSoftDeletes     = false;
    protected $protectFields      = true;
    
    protected $allowedFields      = ['title', 'description', 'is_completed', 'user_id', 'category_id', 'due_date'];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [
        'title'        => 'required|string|min_length[5]|max_length[150]|is_unique[tasks.title,id,{id}]',
        'description'  => 'permit_empty|string',
        'is_completed' => 'permit_empty|in_list[0,1]',
        'user_id'      => 'required|integer|greater_than[0]',
        'category_id'  => 'required|integer|greater_than[0]',
        'due_date'     => 'permit_empty|valid_date[Y-m-d H:i:s]',
    ];
    protected $validationMessages   = [
        'title' => [
            'required'   => 'O título da tarefa é obrigatório.',
            'min_length' => 'O título deve ter pelo menos 5 caracteres.',
            'max_length' => 'O título não pode exceder 150 caracteres.',
            'is_unique'  => 'Já existe outra tarefa com este título.',
        ],
        'user_id' => [
            'required'     => 'O usuário é obrigatório.',
            'integer'      => 'O usuário deve ser um número inteiro.',
            'greater_than' => 'Usuário inválido.',
        ],
        'category_id' => [
            'required'     => 'A categoria é obrigatória.',
            'integer'      => 'A categoria deve ser um número inteiro.',
            'greater_than' => 'Categoria inválida.',
        ],
        'due_date' => [
            'valid_date' => 'A data de vencimento deve estar no formato Y-m-d H:i:s.',
        ],
    ];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];
    
    // --- Métodos de Consulta ---
    
    public function getTasksByUser(int $userId)
    {
        return $this->where('user_id', $userId)->findAll();
    }

    public function getTasksByCategory(int $categoryId)
    {
        return $this->where('category_id', $categoryId)->findAll();
    }

    public function getCompletedTasks()
    {
        return $this->where('is_completed', 1)->findAll();
    }

    public function getPendingTasks()
    {
        return $this->where('is_completed', 0)->findAll();
    }

    public function getCompletedTasksByUser(int $userId)
    {
        return $this->where('user_id', $userId)
            ->where('is_completed', 1)
            ->findAll();
    }

    public function getPendingTasksByUser(int $userId)
    {
        return $this->where('user_id', $userId)
            ->where('is_completed', 0)
            ->findAll();
    }

    public function getTasksWithUserAndCategory(int $userId = null)
    {
        $query = $this->select('tasks.*, users.name as user_name, categories.name as category_name')
            ->join('users', 'users.id = tasks.user_id')
            ->join('categories', 'categories.id = tasks.category_id');

        if ($userId) {
            $query->where('tasks.user_id', $userId);
        }
        
        $query->orderBy('tasks.is_completed', 'ASC')
              ->orderBy('tasks.due_date', 'ASC')
              ->orderBy('tasks.created_at', 'DESC');

        return $query->findAll();
    }

    public function completeTask(int $taskId): bool
    {
        return $this->update($taskId, ['is_completed' => 1]);
    }

    public function uncompleteTask(int $taskId): bool
    {
        return $this->update($taskId, ['is_completed' => 0]);
    }
}