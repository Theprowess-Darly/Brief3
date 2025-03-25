<?php
session_start();
require_once '../app/config/Database.php';
require_once '../app/models/User.php';
require_once '../app/controllers/UserController.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Welcome to User Management System</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex flex-col items-center justify-center">
        <div class="bg-white p-8 rounded-lg shadow-lg max-w-md w-full">
            <h1 class="text-3xl font-bold text-center text-gray-800 mb-8">Welcome to User Management System</h1>
            
            <?php if (!isset($_SESSION['user_id'])): ?>
                <div class="space-y-4">
                    <a href="/PHP/Brief3/public/login.php" 
                       class="block w-full bg-blue-500 hover:bg-blue-600 text-white font-bold py-3 px-4 rounded text-center">
                        Login
                    </a>
                    <a href="/PHP/Brief3/public/register.php" 
                       class="block w-full bg-green-500 hover:bg-green-600 text-white font-bold py-3 px-4 rounded text-center">
                        Register
                    </a>
                </div>
            <?php else: ?>
                <div class="space-y-4">
                    <p class="text-center text-gray-600 mb-4">
                        Welcome back, <?php echo htmlspecialchars($_SESSION['username']); ?>!
                    </p>
                    <a href="/PHP/Brief3/public/dashboard.php" 
                       class="block w-full bg-indigo-500 hover:bg-indigo-600 text-white font-bold py-3 px-4 rounded text-center">
                        Go to Dashboard
                    </a>
                    <a href="/PHP/Brief3/public/logout.php" 
                       class="block w-full bg-red-500 hover:bg-red-600 text-white font-bold py-3 px-4 rounded text-center">
                        Logout
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>