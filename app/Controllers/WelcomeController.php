<?php

    namespace App\Controllers;

    use CodeIgniter\Controller;

    class WelcomeController extends Controller
    {
        public function index()
        {
            return view('welcome');
        }
    }