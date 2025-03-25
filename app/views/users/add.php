<!DOCTYPE html>
<html>
<head>
    <title>Add New User</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex items-center justify-center">
        <div class="bg-white p-8 rounded-lg shadow-lg max-w-md w-full">
            <h1 class="text-2xl font-bold mb-6 text-center">Add New User</h1>
            
            <?php if (isset($error)): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <form action="/PHP/Brief3/public/add_user.php" method="POST" class="space-y-4">
                <div>
                    <label class="block text-gray-700 mb-2">Username</label>
                    <input type="text" name="username" required 
                           class="w-full px-3 py-2 border rounded-lg">
                </div>
                
                <div>
                    <label class="block text-gray-700 mb-2">Email</label>
                    <input type="email" name="email" required 
                           class="w-full px-3 py-2 border rounded-lg">
                </div>
                
                <div>
                    <label class="block text-gray-700 mb-2">Password</label>
                    <input type="password" name="password" required 
                           class="w-full px-3 py-2 border rounded-lg">
                </div>
                
                <div>
                    <label class="block text-gray-700 mb-2">Role</label>
                    <select name="role_id" required class="w-full px-3 py-2 border rounded-lg">
                        <option value="2">Client</option>
                        <option value="1">Administrator</option>
                    </select>
                </div>

                <button type="submit" 
                        class="w-full bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">
                    Add User
                </button>
            </form>
            
            <a href="/PHP/Brief3/public/dashboard.php" 
               class="block text-center mt-4 text-blue-500 hover:text-blue-600">
                Back to Dashboard
            </a>
        </div>
    </div>
</body>
</html>