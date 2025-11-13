<?php

namespace App\Models;

use CodeIgniter\Model;

class CategoryModel extends Model
{
    protected $table            = 'categories';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['name'];

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
        'name' => 'required|string|max_length[100]|is_unique[categories.name]',
    ];
    protected $validationMessages   = [
        'name' => [
            'required' => 'O nome da categoria é obrigatório.',
            'max_length' => 'O nome não pode exceder 100 caracteres.',
            'is_unique' => 'Esta categoria já existe.',
        ],
    ];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];









    
    /**
     * Get all categories with their tasks count
     */
    public function getCategoriesWithTasksCount()
    {
        return $this->select('categories.*, COUNT(tasks.id) as tasks_count')
            ->join('tasks', 'tasks.category_id = categories.id', 'left')
            ->groupBy('categories.id')
            ->findAll();
    }

    /**
     * Get category with all tasks
     */
    public function getCategoryWithTasks(int $categoryId)
    {
        $category = $this->find($categoryId);
        if ($category) {
            $taskModel = new TaskModel();
            $category['tasks'] = $taskModel->where('category_id', $categoryId)->findAll();
        }
        return $category;
    }

    /**
     * Find category by name
     */
    public function getByName(string $name)
    {
        return $this->where('name', $name)->first();
    }
}


