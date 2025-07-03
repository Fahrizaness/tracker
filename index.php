<?php
session_start();

require_once 'config/database.php';

// Routing sederhana
$request = $_SERVER['REQUEST_URI'];
$base_path = '/tumbuh1%';
$request = str_replace($base_path, '', $request);

switch ($request) {
    case '/':
    case '':
        require 'controllers/HomeController.php';
        $controller = new HomeController();
        $controller->index();
        break;
    
    // Auth Routes
    case '/login':
        require 'controllers/AuthController.php';
        $controller = new AuthController();
        $controller->login();
        break;
    case '/register':
        require 'controllers/AuthController.php';
        $controller = new AuthController();
        $controller->register();
        break;
    case '/logout':
        require 'controllers/AuthController.php';
        $controller = new AuthController();
        $controller->logout();
        break;
    
    // Ibadah Tracker
    case '/ibadah':
        require 'controllers/IbadahController.php';
        $controller = new IbadahController();
        $controller->index();
        break;
    case '/ibadah/create':
        require 'controllers/IbadahController.php';
        $controller = new IbadahController();
        $controller->create();
        break;
    case '/ibadah/store':
        require 'controllers/IbadahController.php';
        $controller = new IbadahController();
        $controller->store();
        break;
    case '/ibadah/report':
        require 'controllers/IbadahController.php';
        $controller = new IbadahController();
        $controller->report();
        break;
    case '/ibadah/delete':
        require 'controllers/IbadahController.php';
        $controller = new IbadahController();
        $controller->delete();
        break;
// Route untuk edit dengan query string
    case '/ibadah/edit':
        require 'controllers/IbadahController.php';
        $controller = new IbadahController();
        $controller->edit();
        break;

    // Route untuk proses update
    case '/ibadah/update':
        require 'controllers/IbadahController.php';
        $controller = new IbadahController();
        $controller->update();
        break;
    
    // Mood Tracker
    case '/mood':
        require 'controllers/MoodController.php';
        $controller = new MoodController();
        $controller->index();
        break;
    case '/mood/create':
        require 'controllers/MoodController.php';
        $controller = new MoodController();
        $controller->create();
        break;
    case '/mood/store':
        require 'controllers/MoodController.php';
        $controller = new MoodController();
        $controller->store();
        break;
    case '/mood/report':
        require 'controllers/MoodController.php';
        $controller = new MoodController();
        $controller->report();
        break;
    
    // Finance Tracker
    case '/finance':
        require 'controllers/FinanceController.php';
        $controller = new FinanceController();
        $controller->index();
        break;
    case '/finance/create':
        require 'controllers/FinanceController.php';
        $controller = new FinanceController();
        $controller->create();
        break;
    case '/finance/store':
        require 'controllers/FinanceController.php';
        $controller = new FinanceController();
        $controller->store();
        break;
    case '/finance/report':
        require 'controllers/FinanceController.php';
        $controller = new FinanceController();
        $controller->report();
        break;
    case '/challenge':
        require 'controllers/ChallengeController.php';
        $controller = new ChallengeController();
        $controller->index();
        break;

    case '/challenge/complete':
        require 'controllers/ChallengeController.php';
        $controller = new ChallengeController();
        $controller->complete();
        break;

    case '/challenge/history':
        require 'controllers/ChallengeController.php';
        $controller = new ChallengeController();
        $controller->history();
        break;
    default:
        http_response_code(404);
        echo "Halaman tidak ditemukan.";
        break;
}