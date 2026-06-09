<?php
session_start();
$contentFile = __DIR__ . '/../data/content.json';
$data = json_decode(file_get_contents($contentFile), true);

$admin_pass_hash = password_hash('91827364', PASSWORD_DEFAULT); // In a real app, this hash should be stored in an env file or database
// Load env variables
$env = file_exists(__DIR__ . '/../.env') ? parse_ini_file(__DIR__ . '/../.env') : [];
$env_admin_pass_hash = $env['ADMIN_PASSWORD_HASH'] ?? '$2y$12$HKqJAbQTRPuCLgoS6GoqmOhgu3Fb6TWB6Ha5hLJWDkPh8aDRq7bcC'; // Hash for '91827364'

if (isset($_POST['login'])) {
    // Basic CSRF check for login (simplistic for demo purposes, proper app needs unique tokens)
    if (password_verify($_POST['password'], $env_admin_pass_hash)) {
        $_SESSION['loggedin'] = true;
        // Generate CSRF token upon login
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        header("Location: index.php");
        exit;
    }
    else $error = "Invalid Password";
}

if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: index.php");
    exit;
}

if (!isset($_SESSION['loggedin'])): ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login | Rizky Portfolio</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #2563eb;
            --primary-hover: #1d4ed8;
            --bg: #f8fafc;
            --text-main: #0f172a;
            --text-muted: #64748b;
            --white: #ffffff;
            --border: #e2e8f0;
        }
        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            background: var(--bg); 
            display: flex; 
            justify-content: center; 
            align-items: center; 
            height: 100vh; 
            margin: 0; 
            color: var(--text-main);
        }
        .login-card { 
            background: var(--white); 
            padding: 40px; 
            border-radius: 24px; 
            box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1), 0 10px 10px -5px rgba(0,0,0,0.04); 
            width: 100%;
            max-width: 400px; 
            border: 1px solid var(--border);
        }
        .login-header { text-align: center; margin-bottom: 32px; }
        .login-header .icon {
            width: 60px;
            height: 60px;
            background: rgba(37, 63, 235, 0.1);
            color: var(--primary);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            margin: 0 auto 16px;
        }
        h2 { margin: 0; font-size: 24px; font-weight: 700; }
        p.subtitle { color: var(--text-muted); margin: 8px 0 0; font-size: 14px; }
        .form-group { margin-bottom: 20px; }
        label { display: block; font-size: 14px; font-weight: 600; margin-bottom: 8px; color: var(--text-main); }
        .input-wrapper { position: relative; }
        .input-wrapper i { position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: var(--text-muted); }
        input { 
            width: 100%; 
            padding: 12px 12px 12px 42px; 
            border: 1px solid var(--border); 
            border-radius: 12px; 
            box-sizing: border-box;
            font-family: inherit;
            font-size: 15px;
            transition: all 0.2s;
        }
        input:focus { outline: none; border-color: var(--primary); box-shadow: 0 0 0 4px rgba(37, 63, 235, 0.1); }
        button { 
            width: 100%; 
            padding: 14px; 
            background: var(--primary); 
            color: white; 
            border: none; 
            border-radius: 12px; 
            cursor: pointer; 
            font-weight: 600; 
            font-size: 16px;
            transition: all 0.2s;
            margin-top: 10px;
        }
        button:hover { background: var(--primary-hover); transform: translateY(-1px); }
        .error-msg {
            background: #fef2f2;
            color: #dc2626;
            padding: 12px;
            border-radius: 8px;
            font-size: 14px;
            margin-bottom: 20px;
            border: 1px solid #fecaca;
            display: flex;
            align-items: center;
            gap: 8px;
        }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="login-header">
            <div class="icon"><i class="fas fa-lock"></i></div>
            <h2>Welcome Back</h2>
            <p class="subtitle">Enter your password to access CMS</p>
        </div>
        <?php if(isset($error)): ?>
            <div class="error-msg">
                <i class="fas fa-exclamation-circle"></i>
                <?php echo $error; ?>
            </div>
        <?php endif; ?>
        <form method="POST">
            <div class="form-group">
                <label>Password</label>
                <div class="input-wrapper">
                    <i class="fas fa-key"></i>
                    <input type="password" name="password" placeholder="••••••••" required autofocus>
                </div>
            </div>
            <button type="submit" name="login">Sign In</button>
        </form>
    </div>
</body>
</html>
<?php exit; endif;

// Logic for saving data
function verify_csrf() {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("CSRF token validation failed.");
    }
}

if (isset($_POST['save_general'])) {
    verify_csrf();
    $data['en']['hero-name'] = htmlspecialchars($_POST['hero-name-en']);
    $data['id']['hero-name'] = htmlspecialchars($_POST['hero-name-id']);
    $data['en']['hero-desc'] = htmlspecialchars($_POST['hero-desc-en']);
    $data['id']['hero-desc'] = htmlspecialchars($_POST['hero-desc-id']);
    $data['en']['contact-email'] = htmlspecialchars($_POST['contact-email']);
    $data['id']['contact-email'] = htmlspecialchars($_POST['contact-email']);
    file_put_contents($contentFile, json_encode($data, JSON_PRETTY_PRINT), LOCK_EX);
    $success = "General settings updated!";
}

    if (isset($_POST['save_chatbot'])) {
        verify_csrf();
        
        // Load existing env to keep password hash
        $envFile = __DIR__ . '/../.env';
        $envData = file_exists($envFile) ? parse_ini_file($envFile) : [];
        
        // Update API key in env if provided, otherwise keep existing
        $newApiKey = htmlspecialchars($_POST['api_key']);
        if (!empty($newApiKey)) {
            $envData['DEEPSEEK_API_KEY'] = $newApiKey;
            $envContent = "";
            foreach ($envData as $key => $value) {
                $envContent .= "{$key}={$value}\n";
            }
            file_put_contents($envFile, $envContent, LOCK_EX);
        }
        
        $data['chatbot']['model_id'] = htmlspecialchars($_POST['model_id']);
        $data['chatbot']['system_prompt'] = htmlspecialchars($_POST['system_prompt']);
        file_put_contents($contentFile, json_encode($data, JSON_PRETTY_PRINT), LOCK_EX);
        $success = "Chatbot configuration updated!";
    }

if (isset($_POST['add_portfolio'])) {
    verify_csrf();
    $new_id = count($data['portfolio']) + 1;
    $data['portfolio'][] = [
        "id" => $new_id,
        "title_id" => htmlspecialchars($_POST['p_title_id']),
        "title_en" => htmlspecialchars($_POST['p_title_en']),
        "desc_id" => htmlspecialchars($_POST['p_desc_id']),
        "desc_en" => htmlspecialchars($_POST['p_desc_en'])
    ];
    file_put_contents($contentFile, json_encode($data, JSON_PRETTY_PRINT), LOCK_EX);
    $success = "Portfolio item added!";
}

if (isset($_POST['delete_portfolio'])) {
    verify_csrf();
    $id = $_POST['delete_id'];
    $data['portfolio'] = array_filter($data['portfolio'], function($item) use ($id) {
        return $item['id'] != $id;
    });
    $data['portfolio'] = array_values($data['portfolio']);
    file_put_contents($contentFile, json_encode($data, JSON_PRETTY_PRINT), LOCK_EX);
    $success = "Portfolio item deleted!";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Rizky Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #2563eb;
            --secondary: #64748b;
            --success: #22c55e;
            --danger: #ef4444;
            --bg: #f1f5f9;
            --sidebar: #0f172a;
            --white: #ffffff;
            --text-main: #1e293b;
            --text-muted: #64748b;
            --border: #e2e8f0;
        }
        * { box-sizing: border-box; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: var(--bg); margin: 0; display: flex; min-height: 100vh; color: var(--text-main); }
        
        /* Sidebar */
        .sidebar { width: 280px; background: var(--sidebar); color: white; padding: 32px 24px; display: flex; flex-direction: column; position: fixed; height: 100vh; }
        .sidebar h2 { font-size: 1.5rem; font-weight: 700; margin: 0 0 40px; display: flex; align-items: center; gap: 12px; }
        .sidebar h2 i { color: var(--primary); }
        .sidebar a { display: flex; align-items: center; gap: 12px; color: #94a3b8; text-decoration: none; padding: 14px 16px; border-radius: 12px; transition: 0.2s; font-weight: 500; margin-bottom: 4px; }
        .sidebar a:hover { background: rgba(255,255,255,0.05); color: white; }
        .sidebar a.active { background: var(--primary); color: white; }
        .sidebar .logout { margin-top: auto; color: #f87171; }
        .sidebar .logout:hover { background: rgba(239, 68, 68, 0.1); }

        /* Content */
        .main-content { flex: 1; margin-left: 280px; padding: 48px; }
        .header-section { margin-bottom: 32px; }
        .header-section h1 { margin: 0; font-size: 28px; font-weight: 700; }
        .header-section p { color: var(--text-muted); margin: 8px 0 0; }

        .card { background: var(--white); padding: 32px; border-radius: 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); border: 1px solid var(--border); margin-bottom: 32px; }
        .card-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; }
        .card-header h2 { margin: 0; font-size: 20px; font-weight: 700; }

        /* Stats */
        .stats-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 24px; margin-bottom: 32px; }
        .stat-card { background: var(--white); padding: 24px; border-radius: 20px; border: 1px solid var(--border); display: flex; align-items: center; gap: 20px; }
        .stat-icon { width: 56px; height: 56px; border-radius: 16px; display: flex; align-items: center; justify-content: center; font-size: 24px; }
        .stat-info h3 { margin: 0; font-size: 14px; color: var(--text-muted); font-weight: 500; }
        .stat-info p { margin: 4px 0 0; font-size: 24px; font-weight: 700; }

        /* Forms */
        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px; }
        .form-group { margin-bottom: 24px; }
        label { display: block; font-weight: 600; font-size: 14px; margin-bottom: 8px; color: var(--text-main); }
        input, textarea { width: 100%; padding: 12px 16px; border: 1px solid var(--border); border-radius: 12px; font-family: inherit; font-size: 15px; transition: 0.2s; }
        input:focus, textarea:focus { outline: none; border-color: var(--primary); box-shadow: 0 0 0 4px rgba(37, 63, 235, 0.1); }
        textarea { min-height: 100px; resize: vertical; }

        .btn { padding: 12px 24px; border-radius: 12px; font-weight: 600; cursor: pointer; transition: 0.2s; border: none; font-size: 15px; display: inline-flex; align-items: center; gap: 8px; }
        .btn-primary { background: var(--primary); color: white; }
        .btn-primary:hover { background: #1d4ed8; transform: translateY(-1px); }
        .btn-add { background: #f1f5f9; color: var(--text-main); border: 1px solid var(--border); }
        .btn-add:hover { background: #e2e8f0; }

        /* Table */
        table { width: 100%; border-collapse: separate; border-spacing: 0; }
        th { text-align: left; padding: 16px; background: #f8fafc; font-size: 13px; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em; border-bottom: 1px solid var(--border); }
        td { padding: 16px; border-bottom: 1px solid var(--border); font-size: 15px; }
        tr:last-child td { border-bottom: none; }
        .action-btns { display: flex; gap: 12px; }
        .delete-btn { color: var(--danger); text-decoration: none; font-weight: 600; font-size: 14px; }
        .delete-btn:hover { text-decoration: underline; }

        /* Alerts */
        .alert { background: #dcfce7; color: #166534; padding: 16px 24px; border-radius: 12px; margin-bottom: 32px; border-left: 4px solid var(--success); display: flex; align-items: center; gap: 12px; font-weight: 500; }
        
        @media (max-width: 1024px) {
            .sidebar { width: 80px; padding: 32px 12px; }
            .sidebar h2 span, .sidebar a span { display: none; }
            .main-content { margin-left: 80px; padding: 32px; }
            .form-row { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <h2><i class="fas fa-terminal"></i> <span>Rizky CMS</span></h2>
        <a href="#stats" class="active"><i class="fas fa-chart-pie"></i> <span>Overview</span></a>
        <a href="#general"><i class="fas fa-sliders-h"></i> <span>General</span></a>
        <a href="#chatbot"><i class="fas fa-robot"></i> <span>Chatbot</span></a>
        <a href="#portfolio"><i class="fas fa-folder-open"></i> <span>Portfolio</span></a>
        <a href="?logout=1" class="logout"><i class="fas fa-sign-out-alt"></i> <span>Logout</span></a>
    </div>

    <div class="main-content">
        <div class="header-section">
            <h1>Dashboard Overview</h1>
            <p>Welcome back! Here's what's happening with your portfolio.</p>
        </div>

        <?php if(isset($success)): ?>
            <div class="alert">
                <i class="fas fa-check-circle"></i>
                <?php echo $success; ?>
            </div>
        <?php endif; ?>

        <div id="stats" class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon" style="background: rgba(37, 63, 235, 0.1); color: var(--primary);">
                    <i class="fas fa-eye"></i>
                </div>
                <div class="stat-info">
                    <h3>Total Views</h3>
                    <?php 
                    $statsFile = __DIR__ . '/../data/stats.json';
                    $statsData = file_exists($statsFile) ? json_decode(file_get_contents($statsFile), true) : ['views' => 0];
                    ?>
                    <p><?php echo number_format($statsData['views']); ?></p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon" style="background: rgba(34, 197, 94, 0.1); color: var(--success);">
                    <i class="fas fa-project-diagram"></i>
                </div>
                <div class="stat-info">
                    <h3>Projects</h3>
                    <p><?php echo count($data['portfolio']); ?></p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon" style="background: rgba(245, 158, 11, 0.1); color: #f59e0b;">
                    <i class="fas fa-server"></i>
                </div>
                <div class="stat-info">
                    <h3>Status</h3>
                    <p style="font-size: 16px; color: var(--success);">Online</p>
                </div>
            </div>
        </div>

        <div id="general" class="card">
            <div class="card-header">
                <h2>General Settings</h2>
            </div>
            <form method="POST">
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                <div class="form-row">
                    <div class="form-group">
                        <label>Hero Name (English)</label>
                        <input type="text" name="hero-name-en" value="<?php echo htmlspecialchars($data['en']['hero-name']); ?>" placeholder="Full Name">
                    </div>
                    <div class="form-group">
                        <label>Hero Name (Indonesia)</label>
                        <input type="text" name="hero-name-id" value="<?php echo htmlspecialchars($data['id']['hero-name']); ?>" placeholder="Nama Lengkap">
                    </div>
                </div>
                <div class="form-group">
                    <label>Contact Email</label>
                    <input type="email" name="contact-email" value="<?php echo htmlspecialchars($data['en']['contact-email']); ?>" placeholder="email@example.com">
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Hero Description (English)</label>
                        <textarea name="hero-desc-en"><?php echo htmlspecialchars($data['en']['hero-desc']); ?></textarea>
                    </div>
                    <div class="form-group">
                        <label>Deskripsi Hero (Indonesia)</label>
                        <textarea name="hero-desc-id"><?php echo htmlspecialchars($data['id']['hero-desc']); ?></textarea>
                    </div>
                </div>
                <button type="submit" name="save_general" class="btn btn-primary">
                    <i class="fas fa-save"></i> Save Changes
                </button>
            </form>
        </div>

        <div id="chatbot" class="card">
            <div class="card-header">
                <h2>Chatbot Configuration (DeepSeek)</h2>
            </div>
            <form method="POST">
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                <div class="form-row">
                    <div class="form-group">
                        <label>DeepSeek API Key (Leave blank to keep current)</label>
                        <input type="password" name="api_key" placeholder="sk-...">
                    </div>
                    <div class="form-group">
                        <label>Model ID</label>
                        <input type="text" name="model_id" value="<?php echo htmlspecialchars($data['chatbot']['model_id']); ?>" placeholder="deepseek-v4-flash">
                    </div>
                </div>
                <div class="form-group">
                    <label>System Prompt</label>
                    <textarea name="system_prompt" style="min-height: 150px;"><?php echo htmlspecialchars($data['chatbot']['system_prompt']); ?></textarea>
                </div>
                <button type="submit" name="save_chatbot" class="btn btn-primary">
                    <i class="fas fa-robot"></i> Update Chatbot
                </button>
            </form>
        </div>

        <div id="portfolio" class="card">
            <div class="card-header">
                <h2>Manage Portfolio</h2>
            </div>
            
            <form method="POST" style="background: #f8fafc; padding: 24px; border-radius: 16px; margin-bottom: 32px; border: 1px solid var(--border);">
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                <h3 style="margin-top:0; font-size: 16px; margin-bottom: 20px;">Add New Project</h3>
                <div class="form-row">
                    <input type="text" name="p_title_en" placeholder="Project Title (EN)" required>
                    <input type="text" name="p_title_id" placeholder="Judul Proyek (ID)" required>
                </div>
                <div class="form-row" style="margin-top: 15px;">
                    <textarea name="p_desc_en" placeholder="Description (EN)" required></textarea>
                    <textarea name="p_desc_id" placeholder="Deskripsi (ID)" required></textarea>
                </div>
                <button type="submit" name="add_portfolio" class="btn btn-add">
                    <i class="fas fa-plus"></i> Add to Portfolio
                </button>
            </form>

            <table>
                <thead>
                    <tr>
                        <th>Project Details</th>
                        <th>Language (ID)</th>
                        <th style="text-align: right;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($data['portfolio'] as $item): ?>
                    <tr>
                        <td>
                            <div style="font-weight: 600;"><?php echo $item['title_en']; ?></div>
                            <div style="font-size: 13px; color: var(--text-muted); margin-top: 4px;"><?php echo substr($item['desc_en'], 0, 80) . '...'; ?></div>
                        </td>
                        <td>
                            <div style="font-weight: 500;"><?php echo $item['title_id']; ?></div>
                        </td>
                        <td style="text-align: right;">
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                                <input type="hidden" name="delete_id" value="<?php echo $item['id']; ?>">
                                <button type="submit" name="delete_portfolio" class="delete-btn" style="background:none; border:none; padding:0; cursor:pointer;" onclick="return confirm('Delete this project?')">
                                    <i class="fas fa-trash-alt"></i> Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        // Simple active state handler
        const navLinks = document.querySelectorAll('.sidebar a');
        navLinks.forEach(link => {
            link.addEventListener('click', function() {
                navLinks.forEach(l => l.classList.remove('active'));
                this.classList.add('active');
            });
        });
    </script>
</body>
</html>
