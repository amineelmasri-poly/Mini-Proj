<?php

require_once 'php/config.php';

echo "<h1>Le Café Local - System Diagnostic</h1>";
echo "<style>body{font-family:Arial;padding:20px;} .success{color:green;} .error{color:red;} .info{color:blue;} pre{background:#f4f4f4;padding:10px;}</style>";

echo "<h2>1. PHP Version</h2>";
echo "<p class='success'>✅ PHP Version: " . phpversion() . "</p>";

echo "<h2>2. Database Connection</h2>";
try {
    $conn = getDBConnection();
    echo "<p class='success'>✅ Connected to MySQL successfully!</p>";
    echo "<p class='info'>Database: " . DB_NAME . "</p>";
} catch(Exception $e) {
    echo "<p class='error'>❌ Connection failed: " . $e->getMessage() . "</p>";
    echo "<p class='info'>Check your XAMPP MySQL service is running</p>";
    echo "<p class='info'>Go to: http://localhost/phpmyadmin to verify</p>";
    exit;
}

echo "<h2>3. Database Existence</h2>";
try {
    $stmt = $conn->query("SELECT DATABASE()");
    $db = $stmt->fetch();
    echo "<p class='success'>✅ Using database: " . $db['DATABASE()'] . "</p>";
} catch(Exception $e) {
    echo "<p class='error'>❌ Database error: " . $e->getMessage() . "</p>";
}

echo "<h2>4. Database Tables</h2>";
try {
    $stmt = $conn->query("SHOW TABLES");
    $tables = $stmt->fetchAll();
    
    if (empty($tables)) {
        echo "<p class='error'>❌ No tables found! Database not imported.</p>";
        echo "<p class='info'><strong>Solution:</strong></p>";
        echo "<ol>";
        echo "<li>Go to http://localhost/phpmyadmin</li>";
        echo "<li>Click on 'cafe_local' database</li>";
        echo "<li>Click 'Import' tab</li>";
        echo "<li>Choose file: sql/database.sql</li>";
        echo "<li>Click 'Go'</li>";
        echo "</ol>";
    } else {
        echo "<p class='success'>✅ Tables found:</p>";
        echo "<ul>";
        foreach($tables as $table) {
            echo "<li>" . $table['Tables_in_' . DB_NAME] . "</li>";
        }
        echo "</ul>";
    }
} catch(Exception $e) {
    echo "<p class='error'>❌ Error checking tables: " . $e->getMessage() . "</p>";
}

echo "<h2>5. Admin User</h2>";
try {
    $stmt = $conn->query("SELECT id, username, email, created_at FROM admins");
    $admins = $stmt->fetchAll();
    
    if (empty($admins)) {
        echo "<p class='error'>❌ No admin users found!</p>";
        echo "<p class='info'><strong>Solution - Run this SQL in phpMyAdmin:</strong></p>";
        echo "<pre>";
        echo "USE cafe_local;\n";
        echo "INSERT INTO admins (username, password, email) VALUES \n";
        echo "('admin', '\$2y\$10\$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin@lecafelocal.tn');\n";
        echo "</pre>";
        echo "<p>Or import sql/test-admin.sql</p>";
    } else {
        echo "<p class='success'>✅ Admin users found:</p>";
        echo "<table border='1' cellpadding='5'>";
        echo "<tr><th>ID</th><th>Username</th><th>Email</th><th>Created</th></tr>";
        foreach($admins as $admin) {
            echo "<tr>";
            echo "<td>" . $admin['id'] . "</td>";
            echo "<td>" . $admin['username'] . "</td>";
            echo "<td>" . $admin['email'] . "</td>";
            echo "<td>" . $admin['created_at'] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    }
} catch(Exception $e) {
    echo "<p class='error'>❌ Error checking admins: " . $e->getMessage() . "</p>";
}

echo "<h2>6. Password Hash Test</h2>";
try {
    $stmt = $conn->query("SELECT username, password FROM admins WHERE username = 'admin'");
    $admin = $stmt->fetch();
    
    if ($admin) {
        $testPassword = 'admin123';
        echo "<p class='info'>Testing password: <strong>admin123</strong></p>";
        
        if (password_verify($testPassword, $admin['password'])) {
            echo "<p class='success'>✅ Password verification WORKS! Password is correct.</p>";
            echo "<p class='info'>If you still can't login, try:</p>";
            echo "<ul>";
            echo "<li>Clear browser cache and cookies</li>";
            echo "<li>Try incognito/private browsing</li>";
            echo "<li>Make sure sessions are working (check session.save_path in php.ini)</li>";
            echo "</ul>";
        } else {
            echo "<p class='error'>❌ Password verification FAILED!</p>";
            echo "<p class='info'>The stored password hash doesn't match 'admin123'</p>";
            echo "<p><strong>Fix:</strong> Run this SQL to reset password:</p>";
            echo "<pre>";
            echo "USE cafe_local;\n";
            echo "UPDATE admins SET password = '\$2y\$10\$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi' WHERE username = 'admin';\n";
            echo "</pre>";
        }
    } else {
        echo "<p class='error'>❌ Admin user not found!</p>";
    }
} catch(Exception $e) {
    echo "<p class='error'>❌ Error: " . $e->getMessage() . "</p>";
}

echo "<h2>7. Session Test</h2>";
if (session_status() === PHP_SESSION_ACTIVE) {
    echo "<p class='success'>✅ Sessions are working</p>";
    echo "<p class='info'>Session ID: " . session_id() . "</p>";
} else {
    echo "<p class='error'>❌ Sessions not working</p>";
}

echo "<h2>8. Products in Database</h2>";
try {
    $stmt = $conn->query("SELECT COUNT(*) as count FROM products");
    $result = $stmt->fetch();
    echo "<p class='success'>✅ Products in database: " . $result['count'] . "</p>";
    
    if ($result['count'] == 0) {
        echo "<p class='info'>Import sql/database.sql to add sample products</p>";
    }
} catch(Exception $e) {
    echo "<p class='error'>❌ Error: " . $e->getMessage() . "</p>";
}

echo "<hr>";
echo "<h2>Summary</h2>";
echo "<p><strong>Default Admin Credentials:</strong></p>";
echo "<ul>";
echo "<li>Username: <code>admin</code></li>";
echo "<li>Password: <code>admin123</code></li>";
echo "</ul>";

echo "<p><strong>Admin Login URL:</strong></p>";
echo "<p><a href='php/admin/login.php'>php/admin/login.php</a></p>";

echo "<hr>";
echo "<p><a href='index.html'>← Back to Home</a></p>";
?>
