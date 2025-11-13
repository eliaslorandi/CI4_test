<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = ['name', 'email', 'password', 'remember_token', 'email_verified_at'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $dateFormat = 'datetime';
    protected $validationRules = [
        'name'     => 'required|string|max_length[100]',
        'email'    => 'required|valid_email|is_unique[users.email]',
        'password' => 'required|string|min_length[8]|max_length[255]',
    ];
    protected $validationMessages = [
        'name' => [
            'required' => 'O nome é obrigatório.',
            'max_length' => 'O nome não pode exceder 100 caracteres.',
        ],
        'email' => [
            'required' => 'O email é obrigatório.',
            'valid_email' => 'O email fornecido é inválido.',
            'is_unique' => 'Este email já está registrado.',
        ],
        'password' => [
            'required' => 'A senha é obrigatória.',
            'min_length' => 'A senha deve ter no mínimo 8 caracteres.',
            'max_length' => 'A senha não pode exceder 255 caracteres.',
        ],
    ];
    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    /**
     * Hash a password before saving
     */
    protected function hashPassword(array $data)
    {
        if (isset($data['data']['password'])) {
            $data['data']['password'] = password_hash($data['data']['password'], PASSWORD_BCRYPT);
        }
        return $data;
    }

    /**
     * Hook before insert
     */
    protected $beforeInsert = ['hashPassword'];

    /**
     * Hook before update
     */
    protected $beforeUpdate = ['hashPassword'];





    
    /**
     * Find user by email
     */
    public function findByEmail(string $email)
    {
        return $this->where('email', $email)->first();
    }

    /**
     * Verify user password
     */
    public function verifyPassword(string $password, string $hashedPassword): bool
    {
        return password_verify($password, $hashedPassword);
    }

    /**
     * Get all users with their tasks count
     */
    public function getUsersWithTasksCount()
    {
        return $this->select('users.*, COUNT(tasks.id) as tasks_count')
            ->join('tasks', 'tasks.user_id = users.id', 'left')
            ->groupBy('users.id')
            ->findAll();
    }

    /**
     * Get user with all tasks
     */
    public function getUserWithTasks(int $userId)
    {
        $user = $this->find($userId);
        if ($user) {
            $taskModel = new TaskModel();
            $user['tasks'] = $taskModel->where('user_id', $userId)->findAll();
        }
        return $user;
    }
}
