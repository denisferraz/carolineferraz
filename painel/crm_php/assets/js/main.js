// Funções utilitárias globais
document.addEventListener('DOMContentLoaded', function() {
    
    // Inicializar tooltips e outros componentes
    initializeComponents();
    
    // Configurar AJAX global
    setupAjax();
    
    // Configurar formulários
    setupForms();
});

function initializeComponents() {
    // Adicionar loading states aos botões
    const buttons = document.querySelectorAll('.health-btn');
    buttons.forEach(button => {
        button.addEventListener('click', function(e) {
            if (this.type === 'submit' || this.classList.contains('loading-btn')) {
                showButtonLoading(this);
            }
        });
    });
    
    // Configurar confirmações de exclusão
    const deleteButtons = document.querySelectorAll('[data-confirm]');
    deleteButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            const message = this.getAttribute('data-confirm') || 'Tem certeza que deseja excluir?';
            if (!confirm(message)) {
                e.preventDefault();
            }
        });
    });
}

function setupAjax() {
    // Configurar headers padrão para requisições AJAX
    const originalFetch = window.fetch;
    window.fetch = function(...args) {
        if (args[1] && args[1].method && args[1].method !== 'GET') {
            args[1].headers = {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                ...args[1].headers
            };
        }
        return originalFetch.apply(this, args);
    };
}

function setupForms() {
    // Validação em tempo real
    const inputs = document.querySelectorAll('.health-input, .health-select, .health-textarea');
    inputs.forEach(input => {
        input.addEventListener('blur', function() {
            validateField(this);
        });
        
        input.addEventListener('input', function() {
            clearFieldError(this);
        });
    });
}

function showButtonLoading(button) {
    const originalText = button.innerHTML;
    button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Carregando...';
    button.disabled = true;
    
    // Restaurar após 5 segundos (fallback)
    setTimeout(() => {
        button.innerHTML = originalText;
        button.disabled = false;
    }, 5000);
}

function validateField(field) {
    const value = field.value.trim();
    const type = field.type;
    const required = field.hasAttribute('required');
    
    clearFieldError(field);
    
    if (required && !value) {
        showFieldError(field, 'Este campo é obrigatório');
        return false;
    }
    
    if (value) {
        switch (type) {
            case 'email':
                if (!isValidEmail(value)) {
                    showFieldError(field, 'E-mail inválido');
                    return false;
                }
                break;
            case 'tel':
                if (!isValidPhone(value)) {
                    showFieldError(field, 'Telefone inválido');
                    return false;
                }
                break;
        }
    }
    
    return true;
}

function showFieldError(field, message) {
    clearFieldError(field);
    
    field.style.borderColor = 'var(--health-danger)';
    
    const errorDiv = document.createElement('div');
    errorDiv.className = 'field-error';
    errorDiv.style.cssText = `
        color: var(--health-danger);
        font-size: 0.8rem;
        margin-top: 0.25rem;
    `;
    errorDiv.textContent = message;
    
    field.parentNode.appendChild(errorDiv);
}

function clearFieldError(field) {
    field.style.borderColor = '';
    const errorDiv = field.parentNode.querySelector('.field-error');
    if (errorDiv) {
        errorDiv.remove();
    }
}

function isValidEmail(email) {
    const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return regex.test(email);
}

function isValidPhone(phone) {
    const cleaned = phone.replace(/\D/g, '');
    return cleaned.length >= 10 && cleaned.length <= 11;
}

// Funções para o CRM
function updateContactStage(contactId, newStage) {
    showButtonLoading(event.target);
    
    fetch('api/update_contact_stage.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            contact_id: contactId,
            stage: newStage
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast('Etapa atualizada com sucesso!', 'success');
            setTimeout(() => location.reload(), 1000);
        } else {
            showToast('Erro ao atualizar etapa: ' + data.message, 'danger');
        }
    })
    .catch(error => {
        showToast('Erro de conexão', 'danger');
        console.error('Error:', error);
    });
}

function sendWhatsAppMessage(contactId, phoneNumber, message) {
    if (!message.trim()) {
        showToast('Digite uma mensagem', 'warning');
        return;
    }
    
    const button = event.target;
    showButtonLoading(button);
    
    fetch('api/send_whatsapp.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            contact_id: contactId,
            phone_number: phoneNumber,
            message: message
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast('Mensagem enviada com sucesso!', 'success');
            document.querySelector('textarea[name="message"]').value = '';
            setTimeout(() => location.reload(), 1000);
        } else {
            showToast('Erro ao enviar mensagem: ' + data.message, 'danger');
        }
    })
    .catch(error => {
        showToast('Erro de conexão', 'danger');
        console.error('Error:', error);
    })
    .finally(() => {
        button.innerHTML = '<i class="fas fa-paper-plane"></i> Enviar';
        button.disabled = false;
    });
}

// Funções de formatação
function formatPhone(input) {
    let value = input.value.replace(/\D/g, '');
    
    if (value.length <= 11) {
        if (value.length <= 2) {
            value = value.replace(/(\d{0,2})/, '($1');
        } else if (value.length <= 7) {
            value = value.replace(/(\d{2})(\d{0,5})/, '($1) $2');
        } else {
            value = value.replace(/(\d{2})(\d{5})(\d{0,4})/, '($1) $2-$3');
        }
    }
    
    input.value = value;
}

function formatCPF(input) {
    let value = input.value.replace(/\D/g, '');
    
    if (value.length <= 11) {
        value = value.replace(/(\d{3})(\d{3})(\d{3})(\d{2})/, '$1.$2.$3-$4');
    }
    
    input.value = value;
}

// Funções de busca e filtro
function setupSearch(inputSelector, targetSelector) {
    const searchInput = document.querySelector(inputSelector);
    const targets = document.querySelectorAll(targetSelector);
    
    if (!searchInput || !targets.length) return;
    
    searchInput.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        
        targets.forEach(target => {
            const text = target.textContent.toLowerCase();
            const shouldShow = text.includes(searchTerm);
            target.style.display = shouldShow ? '' : 'none';
        });
    });
}

// Funções de data
function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString('pt-BR');
}

function formatDateTime(dateString) {
    const date = new Date(dateString);
    return date.toLocaleString('pt-BR');
}

function timeAgo(dateString) {
    const date = new Date(dateString);
    const now = new Date();
    const diffTime = Math.abs(now - date);
    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
    
    if (diffDays === 1) {
        return 'Hoje';
    } else if (diffDays === 2) {
        return 'Ontem';
    } else if (diffDays <= 7) {
        return `${diffDays - 1} dias atrás`;
    } else {
        return formatDate(dateString);
    }
}

// Drag and Drop para Kanban (se necessário)
function initializeKanban() {
    const columns = document.querySelectorAll('.kanban-column');
    const cards = document.querySelectorAll('.contact-card');
    
    cards.forEach(card => {
        card.draggable = true;
        
        card.addEventListener('dragstart', function(e) {
            e.dataTransfer.setData('text/plain', this.dataset.contactId);
            this.style.opacity = '0.5';
        });
        
        card.addEventListener('dragend', function() {
            this.style.opacity = '';
        });
    });
    
    columns.forEach(column => {
        column.addEventListener('dragover', function(e) {
            e.preventDefault();
            this.style.backgroundColor = 'rgba(37, 99, 235, 0.1)';
        });
        
        column.addEventListener('dragleave', function() {
            this.style.backgroundColor = '';
        });
        
        column.addEventListener('drop', function(e) {
            e.preventDefault();
            this.style.backgroundColor = '';
            
            const contactId = e.dataTransfer.getData('text/plain');
            const newStage = this.dataset.stage;
            
            if (contactId && newStage) {
                updateContactStage(contactId, newStage);
            }
        });
    });
}

