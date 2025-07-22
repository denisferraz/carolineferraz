<?php
require_once("config/database.php");
require_once("config/auth.php");

$page_title = "Login";
$custom_css = "
    body { display: flex; justify-content: center; align-items: center; min-height: 100vh; background-color: var(--health-bg-secondary); }
    .login-container { max-width: 400px; width: 100%; padding: var(--space-6); background-color: var(--health-bg-card); border-radius: var(--health-radius); box-shadow: var(--health-shadow-lg); }
    .login-title { text-align: center; margin-bottom: var(--space-6); color: var(--health-text-primary); }
";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST["username"] ?? "";
    $password = $_POST["password"] ?? "";
    
    // Simulação de autenticação (substituir por consulta ao banco de dados real)
    // Para fins de demonstração, vamos criar um usuário padrão se não existir
    try {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user && verifyPassword($password, $user["password_hash"])) {
            login($user["id"], $user["token_emp"], $user["username"], $user["name"]);
            $_SESSION["toast_message"] = "Login realizado com sucesso!";
            $_SESSION["toast_type"] = "success";
            header("Location: dashboard.php");
            exit;
        } else {
            $_SESSION["toast_message"] = "Usuário ou senha inválidos.";
            $_SESSION["toast_type"] = "danger";
        }
    } catch (PDOException $e) {
        $_SESSION["toast_message"] = "Erro ao tentar fazer login: " . $e->getMessage();
        $_SESSION["toast_type"] = "danger";
    }
}

// Incluir o header sem a sidebar e o main content
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title . ' - ' . SITE_NAME : SITE_NAME; ?></title>
    <link rel="stylesheet" href="assets/css/health_theme.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        <?php if (isset($custom_css)) echo $custom_css; ?>
    </style>
</head>
<body>
    <div class="login-container">
        <h2 class="login-title">
            <i class="fas fa-heartbeat" style="color: var(--health-primary);"></i>
            CRM Saúde
        </h2>
        <form action="login.php" method="POST">
            <div class="health-form-group">
                <label for="username" class="health-label">Usuário:</label>
                <input type="text" id="username" name="username" class="health-input" required>
            </div>
            <div class="health-form-group">
                <label for="password" class="health-label">Senha:</label>
                <input type="password" id="password" name="password" class="health-input" required>
            </div>
            <button type="submit" class="health-btn health-btn-primary health-btn-full">Entrar</button>
        </form>
    </div>
    
    <div id="toast-container" style="position: fixed; top: 20px; right: 20px; z-index: 9999;"></div>
    <script>
        function showToast(message, type = 'info', duration = 5000) {
            const toast = document.createElement('div');
            toast.className = `health-alert health-alert-${type} health-fade-in`;
            toast.style.cssText = `
                margin-bottom: 10px;
                min-width: 300px;
                box-shadow: var(--health-shadow-lg);
                cursor: pointer;
            `;
            
            const icon = {
                success: 'fas fa-check-circle',
                warning: 'fas fa-exclamation-triangle',
                danger: 'fas fa-times-circle',
                info: 'fas fa-info-circle'
            }[type] || 'fas fa-info-circle';
            
            toast.innerHTML = `
                <i class="${icon}"></i>
                <span>${message}</span>
            `;
            
            toast.onclick = () => toast.remove();
            
            document.getElementById('toast-container').appendChild(toast);
            
            setTimeout(() => {
                if (toast.parentNode) {
                    toast.remove();
                }
            }, duration);
        }
        
        <?php if (isset($_SESSION['toast_message'])): ?>
            showToast(
                '<?php echo addslashes($_SESSION['toast_message']); ?>',
                '<?php echo $_SESSION['toast_type'] ?? 'info'; ?>'
            );
            <?php 
            unset($_SESSION['toast_message']);
            unset($_SESSION['toast_type']);
            ?>
        <?php endif; ?>
    </script>
</body>
</html>


