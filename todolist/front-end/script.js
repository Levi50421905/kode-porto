const API_URL = 'https://todolist-back-wga3.onrender.com/tasks';

let isEditMode = false;
let editingId = null;
let allTasks = [];

function initializeTheme() {
    const theme = localStorage.getItem('theme') || 'light';
    document.documentElement.setAttribute('data-theme', theme);
    updateThemeIcon();
}

function toggleTheme() {
    const currentTheme = document.documentElement.getAttribute('data-theme');
    const newTheme = currentTheme === 'light' ? 'dark' : 'light';
    
    document.documentElement.setAttribute('data-theme', newTheme);
    localStorage.setItem('theme', newTheme);
    updateThemeIcon();
}

function updateThemeIcon() {
    const themeIcon = document.querySelector('.theme-toggle i');
    const currentTheme = document.documentElement.getAttribute('data-theme');
    themeIcon.className = currentTheme === 'light' ? 'fas fa-moon' : 'fas fa-sun';
}

function initializeSearch() {
    const searchInput = document.querySelector('.search-input');
    searchInput.addEventListener('input', handleSearch);
}

function handleSearch(event) {
    const searchTerm = event.target.value.toLowerCase();
    const filteredTasks = allTasks.filter(task => 
        task.subject.toLowerCase().includes(searchTerm) ||
        task.status.toLowerCase().includes(searchTerm)
    );
    renderTasks(filteredTasks);
}

function getStatusBadgeClass(status) {
    switch(status) {
        case 'Belum Mulai':
            return 'status-badge status-pending';
        case 'Sedang Dikerjai':
            return 'status-badge status-progress';
        case 'Selesai':
            return 'status-badge status-completed';
        default:
            return 'status-badge';
    }
}

function formatDate(dateString) {
    const options = { year: 'numeric', month: 'long', day: 'numeric' };
    return new Date(dateString).toLocaleDateString('id-ID', options);
}

function showNotification(message, type = 'success') {
    const notification = document.createElement('div');
    notification.className = `notification ${type}`;
    notification.innerHTML = `
        <div class="notification-content">
            <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'}"></i>
            <span>${message}</span>
        </div>
    `;
    document.body.appendChild(notification);

    setTimeout(() => {
        notification.classList.add('fade-out');
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}

function renderTasks(tasks) {
    const taskList = document.getElementById('taskList');
    taskList.innerHTML = '';
    
    const emptyState = document.getElementById('emptyState');
    if (tasks.length === 0) {
        emptyState?.classList.remove('hidden');
    } else {
        emptyState?.classList.add('hidden');
        tasks.forEach(task => {
            const newRow = taskList.insertRow();
            newRow.setAttribute('id', task._id);
            newRow.innerHTML = `
                <td>${task.subject}</td>
                <td>${formatDate(task.deadline)}</td>
                <td><span class="${getStatusBadgeClass(task.status)}">${task.status}</span></td>
                <td>
                    <div class="action-buttons">
                        <button class="btn-icon btn-edit" onclick="handleEdit('${task._id}')">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn-icon btn-delete" onclick="handleDelete('${task._id}')">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </td>
            `;
        });
    }
}

function fetchTasks() {
    fetch(API_URL)
        .then(response => response.json())
        .then(tasks => {
            allTasks = tasks;
            renderTasks(tasks);
        })
        .catch(error => {
            console.error('Error fetching tasks:', error);
            showNotification('Gagal mengambil data tugas', 'error');
        });
}

function handleSave() {
    const subject = document.getElementById('subject').value;
    const deadline = document.getElementById('deadline').value;
    const status = document.getElementById('status').value;

    if (!subject || !deadline || !status) {
        showNotification('Mohon lengkapi semua data', 'error');
        return;
    }

    if (isEditMode && editingId) {
        fetch(`${API_URL}/${editingId}`, {
            method: 'PUT',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ subject, deadline, status })
        })
        .then(response => response.json())
        .then(() => {
            fetchTasks();
            resetForm();
            showNotification('Tugas berhasil diperbarui');
            isEditMode = false;
            editingId = null;
            updateSaveButton();
        })
        .catch(error => {
            console.error('Error updating task:', error);
            showNotification('Gagal memperbarui tugas', 'error');
        });
    } else {
        fetch(API_URL, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ subject, deadline, status })
        })
        .then(response => response.json())
        .then(() => {
            fetchTasks();
            resetForm();
            showNotification('Tugas berhasil ditambahkan');
        })
        .catch(error => {
            console.error('Error saving task:', error);
            showNotification('Gagal menambahkan tugas', 'error');
        });
    }
}

function handleEdit(taskId) {
    const task = allTasks.find(t => t._id === taskId);
    if (!task) return;

    isEditMode = true;
    editingId = taskId;

    document.getElementById('subject').value = task.subject;
    document.getElementById('deadline').value = formatDateForInput(task.deadline);
    document.getElementById('status').value = task.status;

    updateSaveButton();
}

function handleDelete(taskId) {
    const deleteModal = document.createElement('div');
    deleteModal.className = 'modal';
    deleteModal.innerHTML = `
        <div class="modal-content">
            <h2>Konfirmasi Hapus</h2>
            <p>Apakah Anda yakin ingin menghapus tugas ini?</p>
            <div class="modal-actions">
                <button class="btn btn-secondary" onclick="this.closest('.modal').remove()">Batal</button>
                <button class="btn btn-danger" onclick="confirmDelete('${taskId}', this)">Hapus</button>
            </div>
        </div>
    `;
    document.body.appendChild(deleteModal);
}

function confirmDelete(taskId, buttonElement) {
    const modal = buttonElement.closest('.modal');
    
    fetch(`${API_URL}/${taskId}`, {
        method: 'DELETE'
    })
    .then(() => {
        fetchTasks();
        modal.remove();
        showNotification('Tugas berhasil dihapus');
    })
    .catch(error => {
        console.error('Error deleting task:', error);
        showNotification('Gagal menghapus tugas', 'error');
        modal.remove();
    });
}

function resetForm() {
    document.getElementById('subject').value = '';
    document.getElementById('deadline').value = '';
    document.getElementById('status').value = 'Belum Mulai';
    
    isEditMode = false;
    editingId = null;
    updateSaveButton();
}

function updateSaveButton() {
    const saveButton = document.getElementById('saveButton');
    saveButton.innerHTML = isEditMode ? 
        '<i class="fas fa-check"></i> Update' : 
        '<i class="fas fa-save"></i> Simpan';
}

function formatDateForInput(dateString) {
    const date = new Date(dateString);
    return date.toISOString().split('T')[0];
}

window.onload = function() {
    const themeButton = document.createElement('button');
    themeButton.className = 'theme-toggle';
    themeButton.innerHTML = '<i class="fas fa-moon"></i>';
    themeButton.onclick = toggleTheme;
    document.body.appendChild(themeButton);

    initializeTheme();
    
    initializeSearch();
    
    fetchTasks();
};

const typedTextElement = document.querySelector('.typed-text');
const roles = ['Dengan Mudah', 'Dengan Cepat', 'Dengan Teratur', 'Dengan Baik'];
let roleIndex = 0;
let charIndex = 0;
let isDeleting = false;

function typeText() {
    const currentRole = roles[roleIndex];
    
    if (isDeleting) {
        typedTextElement.textContent = currentRole.substring(0, charIndex - 1);
        charIndex--;
    } else {
        typedTextElement.textContent = currentRole.substring(0, charIndex + 1);
        charIndex++;
    }

    if (!isDeleting && charIndex === currentRole.length) {
        setTimeout(() => isDeleting = true, 2000);
    }

    if (isDeleting && charIndex === 0) {
        isDeleting = false;
        roleIndex = (roleIndex + 1) % roles.length;
    }

    setTimeout(typeText, isDeleting ? 100 : 200);
}

typeText();
