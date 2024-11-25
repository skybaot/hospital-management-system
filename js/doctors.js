class DoctorManager {
    constructor() {
        this.doctorsTable = document.querySelector('#doctorsTable tbody');
        this.searchInput = document.querySelector('#searchDoctors');
        this.addDoctorForm = document.querySelector('#addDoctorForm');
        this.editDoctorForm = document.querySelector('#editDoctorForm');
        
        this.initializeEventListeners();
        this.loadDoctors();
    }
    
    initializeEventListeners() {
        // Search functionality
        if (this.searchInput) {
            this.searchInput.addEventListener('input', this.debounce(() => {
                this.loadDoctors(this.searchInput.value);
            }, 300));
        }
        
        // Form submissions
        if (this.addDoctorForm) {
            this.addDoctorForm.addEventListener('submit', (e) => this.addDoctor(e));
        }
        if (this.editDoctorForm) {
            this.editDoctorForm.addEventListener('submit', (e) => this.updateDoctor(e));
        }
    }
    
    debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }
    
    async loadDoctors(searchQuery = '') {
        try {
            const url = searchQuery 
                ? `../php/doctors/get_doctors.php?search=${encodeURIComponent(searchQuery)}`
                : '../php/doctors/get_doctors.php';
                
            const response = await fetch(url);
            const data = await response.json();
            
            if (data.success) {
                this.renderDoctors(data.data);
            } else {
                this.showError(data.message);
            }
        } catch (error) {
            this.showError('Failed to load doctors');
            console.error('Error:', error);
        }
    }
    
    renderDoctors(doctors) {
        if (!this.doctorsTable) return;
        
        this.doctorsTable.innerHTML = '';
        
        if (doctors.length === 0) {
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td colspan="8" class="px-4 py-3 text-center text-gray-500">
                    No doctors found
                </td>
            `;
            this.doctorsTable.appendChild(tr);
            return;
        }
        
        doctors.forEach(doctor => {
            const tr = document.createElement('tr');
            tr.className = 'hover:bg-gray-800/50 transition-colors';
            tr.innerHTML = `
                <td class="px-4 py-3">${doctor.id}</td>
                <td class="px-4 py-3">${doctor.name}</td>
                <td class="px-4 py-3">${doctor.specialization}</td>
                <td class="px-4 py-3">${doctor.experience} years</td>
                <td class="px-4 py-3">${doctor.phone}</td>
                <td class="px-4 py-3">${doctor.email}</td>
                <td class="px-4 py-3">
                    <span class="px-2 py-1 rounded ${doctor.status === 'Active' ? 'status-active' : 'status-inactive'}">
                        ${doctor.status}
                    </span>
                </td>
                <td class="px-4 py-3">
                    <button onclick="doctorManager.editDoctor('${doctor.id}')" class="text-blue-500 hover:text-blue-700 mr-2">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button onclick="doctorManager.deleteDoctor('${doctor.id}')" class="text-red-500 hover:text-red-700">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            `;
            this.doctorsTable.appendChild(tr);
        });
    }
    
    async addDoctor(event) {
        event.preventDefault();
        
        const formData = {
            id: document.getElementById('doctorId').value,
            name: document.getElementById('doctorName').value,
            specialization: document.getElementById('specialization').value,
            experience: document.getElementById('experience').value,
            phone: document.getElementById('phone').value,
            email: document.getElementById('email').value
        };
        
        try {
            const response = await fetch('../php/doctors/add_doctor.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(formData)
            });
            
            const data = await response.json();
            
            if (data.success) {
                this.showSuccess('Doctor added successfully');
                this.closeModal('addDoctorModal');
                this.addDoctorForm.reset();
                this.loadDoctors();
            } else {
                this.showError(data.message);
            }
        } catch (error) {
            this.showError('Failed to add doctor');
            console.error('Error:', error);
        }
    }
    
    async editDoctor(doctorId) {
        try {
            const response = await fetch(`../php/doctors/get_doctors.php?id=${doctorId}`);
            const data = await response.json();
            
            if (data.success && data.data.length > 0) {
                const doctor = data.data[0];
                
                // Populate edit form
                document.getElementById('editDoctorId').value = doctor.id;
                document.getElementById('editDoctorName').value = doctor.name;
                document.getElementById('editSpecialization').value = doctor.specialization;
                document.getElementById('editExperience').value = doctor.experience;
                document.getElementById('editPhone').value = doctor.phone;
                document.getElementById('editEmail').value = doctor.email;
                
                this.openModal('editDoctorModal');
            } else {
                this.showError('Doctor not found');
            }
        } catch (error) {
            this.showError('Failed to fetch doctor details');
            console.error('Error:', error);
        }
    }
    
    async updateDoctor(event) {
        event.preventDefault();
        
        const formData = {
            id: document.getElementById('editDoctorId').value,
            name: document.getElementById('editDoctorName').value,
            specialization: document.getElementById('editSpecialization').value,
            experience: document.getElementById('editExperience').value,
            phone: document.getElementById('editPhone').value,
            email: document.getElementById('editEmail').value
        };
        
        try {
            const response = await fetch('../php/doctors/update_doctor.php', {
                method: 'PUT',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(formData)
            });
            
            const data = await response.json();
            
            if (data.success) {
                this.showSuccess('Doctor updated successfully');
                this.closeModal('editDoctorModal');
                this.loadDoctors();
            } else {
                this.showError(data.message);
            }
        } catch (error) {
            this.showError('Failed to update doctor');
            console.error('Error:', error);
        }
    }
    
    async deleteDoctor(doctorId) {
        const result = await Swal.fire({
            title: 'Are you sure?',
            text: "This action can't be undone",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#4f46e5',
            cancelButtonColor: '#dc2626',
            confirmButtonText: 'Yes, delete it!'
        });
        
        if (result.isConfirmed) {
            try {
                const response = await fetch('../php/doctors/delete_doctor.php', {
                    method: 'DELETE',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ id: doctorId })
                });
                
                const data = await response.json();
                
                if (data.success) {
                    this.showSuccess('Doctor deleted successfully');
                    this.loadDoctors();
                } else {
                    this.showError(data.message);
                }
            } catch (error) {
                this.showError('Failed to delete doctor');
                console.error('Error:', error);
            }
        }
    }
    
    showSuccess(message) {
        Swal.fire({
            icon: 'success',
            title: 'Success',
            text: message,
            showConfirmButton: false,
            timer: 1500,
            background: '#1e293b',
            color: '#e2e8f0'
        });
    }
    
    showError(message) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: message,
            background: '#1e293b',
            color: '#e2e8f0',
            confirmButtonColor: '#4f46e5'
        });
    }
    
    openModal(modalId) {
        document.getElementById(modalId).classList.remove('hidden');
        document.getElementById(modalId).classList.add('flex');
    }
    
    closeModal(modalId) {
        document.getElementById(modalId).classList.remove('flex');
        document.getElementById(modalId).classList.add('hidden');
    }
}

// Initialize the DoctorManager when the page loads
let doctorManager;
document.addEventListener('DOMContentLoaded', () => {
    doctorManager = new DoctorManager();
});
