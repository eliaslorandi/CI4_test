<?php namespace App\Models;

use CodeIgniter\Model;

class CategoryModel extends Model
{
    protected $table          = 'categories';
    protected $primaryKey     = 'id';
    protected $useAutoIncrement = true;
    protected $returnType     = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields  = true;
    
    // CORREÇÃO: Adicionamos 'user_id' e 'color_code' para salvar a posse e o dado opcional
    protected $allowedFields  = ['user_id', 'name', 'color_code']; 

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules = [
        'user_id'    => 'required|integer', 
        'name'       => 'required|string|max_length[100]|min_length[3]|is_unique[categories.name,id,{id},user_id,{user_id}]', 
        'color_code' => 'permit_empty|max_length[7]', // Ex: #FFFFFF (7 caracteres)
    ];
    
    protected $validationMessages = [
        'user_id' => [
            'required' => 'O ID do usuário é obrigatório.',
        ],
        'name' => [
            'required'   => 'O nome da categoria é obrigatório.',
            'min_length' => 'O nome deve ter pelo menos 3 caracteres.',
            'max_length' => 'O nome não pode exceder 100 caracteres.',
            'is_unique'  => 'Você já possui uma categoria com este nome.',
        ],
    ];
    protected $skipValidation     = false;
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

    /**
     * Retorna categorias do usuário com a contagem de tarefas.
     * @param int $userId O ID do usuário logado.
     * @return array
     */
    public function getCategoriesWithTasksCount(int $userId)
    {
        // FILTRO DE AUTORIZAÇÃO: Filtra apenas as categorias do usuário.
        return $this->select('categories.*, COUNT(tasks.id) as tasks_count')
            ->join('tasks', 'tasks.category_id = categories.id', 'left')
            ->where('categories.user_id', $userId) // Filtro crucial
            ->groupBy('categories.id')
            ->orderBy('categories.name', 'ASC')
            ->findAll();
    }

    /**
     * Retorna uma categoria específica e suas tarefas.
     * @param int $categoryId O ID da categoria.
     * @param int $userId O ID do usuário logado (para autorização).
     * @return array|null
     */
    public function getCategoryWithTasks(int $categoryId, int $userId)
    {
        $category = $this->where('id', $categoryId)
                         ->where('user_id', $userId)
                         ->first();
                         
        if ($category) {
            $taskModel = model('TaskModel'); 
            $category['tasks'] = $taskModel->where('category_id', $categoryId)
                                            ->where('user_id', $userId)
                                            ->findAll();
        }
        return $category;
    }

    /**
     * Retorna uma categoria pelo nome, garantindo que pertença ao usuário.
     * @param string $name O nome da categoria.
     * @param int $userId O ID do usuário logado.
     * @return array|null
     */
    public function getByName(string $name, int $userId)
    {
        return $this->where('name', $name)
                    ->where('user_id', $userId)
                    ->first();
    }
}