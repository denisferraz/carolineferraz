<?php
// Script de instalação do CRM Profissional
require_once("config/database.php");

$install_success = false;
$error_message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Criar tabelas
        $sql_tables = "
        CREATE TABLE IF NOT EXISTS `companies` (
          `token_emp` VARCHAR(50) NOT NULL PRIMARY KEY,
          `name` VARCHAR(255) NOT NULL,
          `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

        CREATE TABLE IF NOT EXISTS `contacts` (
          `id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
          `token_emp` VARCHAR(50) NOT NULL,
          `name` VARCHAR(255) NOT NULL,
          `phone_number` VARCHAR(20),
          `stage` ENUM('Novo', 'Em Contato', 'Negociação', 'Fechado', 'Perdido') DEFAULT 'Novo',
          `last_contact` DATETIME DEFAULT CURRENT_TIMESTAMP,
          `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
          `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
          INDEX `idx_token_emp` (`token_emp`),
          INDEX `idx_stage` (`stage`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

        CREATE TABLE IF NOT EXISTS `messages` (
          `id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
          `token_emp` VARCHAR(50) NOT NULL,
          `phone_number` VARCHAR(20) DEFAULT NULL,
          `contact_name` VARCHAR(100) DEFAULT NULL,
          `message_content` TEXT DEFAULT NULL,
          `received_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
          `processed` BOOLEAN DEFAULT FALSE,
          INDEX `idx_token_emp` (`token_emp`),
          INDEX `idx_processed` (`processed`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

        CREATE TABLE IF NOT EXISTS `interactions` (
          `id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
          `token_emp` VARCHAR(50) NOT NULL,
          `contact_id` INT(11) NOT NULL,
          `message_content` TEXT NOT NULL,
          `origin` ENUM('client', 'company') NOT NULL,
          `interaction_date` DATETIME DEFAULT CURRENT_TIMESTAMP,
          INDEX `idx_token_emp` (`token_emp`),
          INDEX `idx_contact_id` (`contact_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

        CREATE TABLE IF NOT EXISTS `users` (
          `id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
          `token_emp` VARCHAR(50) NOT NULL,
          `username` VARCHAR(50) NOT NULL UNIQUE,
          `password_hash` VARCHAR(255) NOT NULL,
          `name` VARCHAR(255),
          `email` VARCHAR(255),
          `role` ENUM('admin', 'user') DEFAULT 'user',
          `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
          INDEX `idx_token_emp` (`token_emp`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ";

        // Executar criação das tabelas
        $pdo->exec($sql_tables);

        // Criar empresa padrão
        $token_emp = 'demo_' . uniqid();
        $stmt = $pdo->prepare("INSERT INTO companies (token_emp, name) VALUES (?, ?)");
        $stmt->execute([$token_emp, 'Empresa Demo']);

        // Criar usuário administrador
        $admin_username = $_POST['admin_username'] ?? 'admin';
        $admin_password = $_POST['admin_password'] ?? 'admin123';
        $admin_name = $_POST['admin_name'] ?? 'Administrador';
        $admin_email = $_POST['admin_email'] ?? 'admin@crm.com';

        $password_hash = password_hash($admin_password, PASSWORD_DEFAULT);
        
        $stmt = $pdo->prepare("INSERT INTO users (token_emp, username, password_hash, name, email, role) VALUES (?, ?, ?, ?, ?, 'admin')");
        $stmt->execute([$token_emp, $admin_username, $password_hash, $admin_name, $admin_email]);

        // Criar alguns contatos de exemplo
        $sample_contacts = [
            ['Maria Silva Santos', '71992604877', 'Em Contato'],
            ['João Santos Lima', '71992110787', 'Negociação'],
            ['Ana Costa Ferreira', '71996244528', 'Fechado'],
            ['Carlos Oliveira', '71987654321', 'Novo'],
            ['Fernanda Costa', '71999888777', 'Perdido']
        ];

        foreach ($sample_contacts as $contact) {
            $stmt = $pdo->prepare("INSERT INTO contacts (token_emp, name, phone_number, stage) VALUES (?, ?, ?, ?)");
            $stmt->execute([$token_emp, $contact[0], $contact[1], $contact[2]]);
        }

        $install_success = true;
        
    } catch (Exception $e) {
        $error_message = "Erro na instalação: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Instalação - CRM Profissional</title>
    <link rel="stylesheet" href="assets/css/health_theme.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background-color: var(--health-bg-secondary);
        }
        .install-container {
            max-width: 500px;
            width: 100%;
            padding: var(--space-6);
            background-color: var(--health-bg-card);
            border-radius: var(--health-radius);
            box-shadow: var(--health-shadow-lg);
        }
        .install-title {
            text-align: center;
            margin-bottom: var(--space-6);
            color: var(--health-text-primary);
        }
        .success-message {
            background-color: #dcfce7;
            color: #166534;
            padding: var(--space-4);
            border-radius: var(--health-radius-sm);
            margin-bottom: var(--space-4);
            border-left: 4px solid var(--health-success);
        }
        .error-message {
            background-color: #fee2e2;
            color: #991b1b;
            padding: var(--space-4);
            border-radius: var(--health-radius-sm);
            margin-bottom: var(--space-4);
            border-left: 4px solid var(--health-danger);
        }
    </style>
</head>
<body>
    <div class="install-container">
        <h2 class="install-title">
            <i class="fas fa-heartbeat" style="color: var(--health-primary);"></i>
            Instalação do CRM Profissional
        </h2>

        <?php if ($install_success): ?>
            <div class="success-message">
                <h3><i class="fas fa-check-circle"></i> Instalação Concluída!</h3>
                <p>O CRM foi instalado com sucesso. Você pode fazer login com as credenciais criadas.</p>
                <p><strong>Token da Empresa:</strong> <?php echo htmlspecialchars($token_emp); ?></p>
                <p><strong>Usuário:</strong> <?php echo htmlspecialchars($admin_username); ?></p>
            </div>
            <a href="login.php" class="health-btn health-btn-primary health-btn-full">
                <i class="fas fa-sign-in-alt"></i>
                Fazer Login
            </a>
        <?php elseif ($error_message): ?>
            <div class="error-message">
                <h3><i class="fas fa-exclamation-triangle"></i> Erro na Instalação</h3>
                <p><?php echo htmlspecialchars($error_message); ?></p>
            </div>
            <button onclick="location.reload()" class="health-btn health-btn-warning health-btn-full">
                <i class="fas fa-redo"></i>
                Tentar Novamente
            </button>
        <?php else: ?>
            <form method="POST">
                <div class="health-form-group">
                    <label for="admin_username" class="health-label">Usuário Administrador:</label>
                    <input type="text" id="admin_username" name="admin_username" class="health-input" value="admin" required>
                </div>
                
                <div class="health-form-group">
                    <label for="admin_password" class="health-label">Senha do Administrador:</label>
                    <input type="password" id="admin_password" name="admin_password" class="health-input" value="admin123" required>
                </div>
                
                <div class="health-form-group">
                    <label for="admin_name" class="health-label">Nome Completo:</label>
                    <input type="text" id="admin_name" name="admin_name" class="health-input" value="Administrador" required>
                </div>
                
                <div class="health-form-group">
                    <label for="admin_email" class="health-label">E-mail:</label>
                    <input type="email" id="admin_email" name="admin_email" class="health-input" value="admin@crm.com" required>
                </div>
                
                <button type="submit" class="health-btn health-btn-primary health-btn-full">
                    <i class="fas fa-download"></i>
                    Instalar CRM
                </button>
            </form>
            
            <div style="margin-top: var(--space-4); padding: var(--space-3); background-color: var(--health-bg-secondary); border-radius: var(--health-radius-sm);">
                <h4 style="margin-bottom: var(--space-2);">O que será instalado:</h4>
                <ul style="margin-left: var(--space-4); color: var(--health-text-secondary);">
                    <li>Tabelas do banco de dados</li>
                    <li>Usuário administrador</li>
                    <li>Empresa de demonstração</li>
                    <li>Contatos de exemplo</li>
                </ul>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>

