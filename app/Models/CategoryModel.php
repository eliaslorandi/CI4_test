<?php namespace App\Models;

use CodeIgniter\Model;

class CategoryModel extends Model
{
    protected $table              = 'categories';
    protected $primaryKey         = 'id';
    protected $useAutoIncrement   = true;
    protected $returnType         = 'array';
    protected $useSoftDeletes     = false;
    protected $protectFields      = true;
    protected $allowedFields      = ['name'];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at'; // Mesmo que useSoftDeletes seja false

    // Validation
    protected $validationRules      = [
        'name' => 'required|string|max_length[100]|is_unique[categories.name,id,{id}]|min_length[3]',
    ];
    protected $validationMessages   = [
        'name' => [
            'required'   => 'O nome da categoria é obrigatório.',
            'min_length' => 'O nome deve ter pelo menos 3 caracteres.',
            'max_length' => 'O nome não pode exceder 100 caracteres.',
            'is_unique'  => 'Esta categoria já existe.',
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
    
    // --- Métodos de Consulta ---

    public function getCategoriesWithTasksCount()
    {
        // Esta consulta é ideal para exibir a lista de categorias.
        return $this->select('categories.*, COUNT(tasks.id) as tasks_count')
            ->join('tasks', 'tasks.category_id = categories.id', 'left')
            ->groupBy('categories.id')
            ->orderBy('categories.name', 'ASC')
            ->findAll();
    }

    public function getCategoryWithTasks(int $categoryId)
    {
        $category = $this->find($categoryId);
        if ($category) {
            // Instancia o TaskModel para buscar as tarefas associadas
            $taskModel = model('TaskModel'); 
            $category['tasks'] = $taskModel->getTasksByCategory($categoryId);
        }
        return $category;
    }

    public function getByName(string $name)
    {
        return $this->where('name', $name)->first();
    }
}