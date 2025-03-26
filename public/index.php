<?php
session_start();
require_once '../app/config/Database.php';
require_once '../app/models/User.php';
require_once '../app/controllers/UserController.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>TheProwess_Mall</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-teal-900">
    <div>
        <h1 class="text-3xl font-bold text-center text-white my-2">&rAarr; BRIEF_3 - DARLY TCHATCHOUANG &lAarr;</h1>
    </div>
    <div class="min-h-screen flex flex-col items-center justify-center">
        <div class="bg-white p-8 rounded-lg shadow-lg max-w-md w-full">
            <h1 class="text-3xl font-bold text-center text-gray-800 mb-8">&rAarr; TheProwess Mall &lAarr; <br>  Bienvenue au systeme de Gestion d'Utilisateurs</h1>
            
            <?php if (!isset($_SESSION['user_id'])): ?>
                <div class="space-y-4">
                    <a href="/PHP/Brief3/public/login.php" 
                       class="block w-full bg-blue-800 hover:bg-blue-600 text-white font-bold py-3 px-4 rounded text-center">
                        Login / Connexion
                    </a>
                    <a href="/PHP/Brief3/public/register.php" 
                       class="block w-full bg-green-800 hover:bg-green-600 text-white font-bold py-3 px-4 rounded text-center">
                        Register / Inscription
                    </a>
                </div>
            <?php else: ?>
                <div class="space-y-4">
                    <p class="text-center text-gray-600 mb-4">
                        Bon retour: Welcome back, <?php echo htmlspecialchars($_SESSION['username']); ?>!
                    </p>
                    <a href="/PHP/Brief3/public/dashboard.php" 
                       class="block w-full bg-indigo-500 hover:bg-indigo-600 text-white font-bold py-3 px-4 rounded text-center">
                        Go to Dashboard (tableau de bord)
                    </a>
                    <a href="/PHP/Brief3/public/logout.php" 
                       class="block w-full bg-red-500 hover:bg-red-600 text-white font-bold py-3 px-4 rounded text-center">
                        Logout / Deconnexion
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>