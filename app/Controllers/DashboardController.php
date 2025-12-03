<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\TaskModel;

class DashboardController extends Controller
{
    protected $helpers = ['url', 'form', 'session'];

    protected $taskModel;

    public function __construct()
    {
        $this->taskModel = model(TaskModel::class);
    }

    public function index()
    {

        $userId = session()->get('user_id');
        
        $totalTasks = $this->taskModel->countUserTasks($userId);

        $tasksCompleted = $this->taskModel->countCompletedTasks($userId);

        $tasksPending = $totalTasks - $tasksCompleted; 

        $data = [
            'title' => 'Dashboard',
            // Variáveis de estatísticas que o View utilizará
            'total_tasks' => $totalTasks,
            'tasks_completed' => $tasksCompleted,
            'tasks_pending' => $tasksPending, 

        ];

        return view('dashboard/dashboard', $data);
    }
}