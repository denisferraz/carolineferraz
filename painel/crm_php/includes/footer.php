    </main>
    
    <!-- Scripts -->
    <script src="assets/js/main.js"></script>
    
    <!-- Scripts específicos da página -->
    <?php if (isset($custom_js)): ?>
        <script>
            <?php echo $custom_js; ?>
        </script>
    <?php endif; ?>
    
    <?php if (isset($external_js)): ?>
        <?php foreach ($external_js as $js_file): ?>
            <script src="<?php echo $js_file; ?>"></script>
        <?php endforeach; ?>
    <?php endif; ?>
    
    <!-- Toast/Notification System -->
    <div id="toast-container" style="position: fixed; top: 20px; right: 20px; z-index: 9999;"></div>
    
    <script>
        // Sistema de notificações
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
        
        // Verificar se há mensagens de sessão para exibir
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
        
        // Responsividade da sidebar
        function toggleSidebar() {
            const sidebar = document.querySelector('.health-sidebar');
            sidebar.classList.toggle('open');
        }
        
        // Adicionar botão de menu em dispositivos móveis
        if (window.innerWidth <= 768) {
            const menuButton = document.createElement('button');
            menuButton.innerHTML = '<i class="fas fa-bars"></i>';
            menuButton.className = 'health-btn health-btn-primary';
            menuButton.style.cssText = `
                position: fixed;
                top: 20px;
                left: 20px;
                z-index: 1001;
                width: 50px;
                height: 50px;
                border-radius: 50%;
            `;
            menuButton.onclick = toggleSidebar;
            document.body.appendChild(menuButton);
        }
        
        // Fechar sidebar ao clicar fora (mobile)
        document.addEventListener('click', function(e) {
            if (window.innerWidth <= 768) {
                const sidebar = document.querySelector('.health-sidebar');
                const menuButton = document.querySelector('button');
                
                if (!sidebar.contains(e.target) && !menuButton.contains(e.target)) {
                    sidebar.classList.remove('open');
                }
            }
        });
    </script>
</body>
</html>

