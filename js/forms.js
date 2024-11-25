// Form handling functions
function handleAddDoctor(event) {
    event.preventDefault();
    const formData = new FormData(event.target);
    const data = Object.fromEntries(formData);
    
    fetch('php/add_doctor.php', {
        method: 'POST',
        body: JSON.stringify(data),
        headers: {
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: 'Doctor added successfully',
            });
            event.target.reset();
        } else {
            throw new Error(data.message || 'Failed to add doctor');
        }
    })
    .catch(error => {
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: error.message,
        });
    });
}

function handleAddPatient(event) {
    event.preventDefault();
    const formData = new FormData(event.target);
    const data = Object.fromEntries(formData);
    
    fetch('php/add_patient.php', {
        method: 'POST',
        body: JSON.stringify(data),
        headers: {
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: 'Patient added successfully',
            });
            event.target.reset();
        } else {
            throw new Error(data.message || 'Failed to add patient');
        }
    })
    .catch(error => {
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: error.message,
        });
    });
}

function handleAddSchedule(event) {
    event.preventDefault();
    const formData = new FormData(event.target);
    const data = Object.fromEntries(formData);
    
    fetch('php/add_schedule.php', {
        method: 'POST',
        body: JSON.stringify(data),
        headers: {
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: 'Schedule added successfully',
            });
            event.target.reset();
        } else {
            throw new Error(data.message || 'Failed to add schedule');
        }
    })
    .catch(error => {
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: error.message,
        });
    });
}

// Navigation functions
function showSection(hash) {
    // Hide all sections
    document.querySelectorAll('main > div[id]').forEach(section => {
        section.classList.add('hidden');
    });
    
    // Show selected section
    const sectionId = hash.replace('#', '') || 'dashboard';
    const section = document.getElementById(sectionId);
    if (section) {
        section.classList.remove('hidden');
    }
}

// Initialize navigation
window.addEventListener('hashchange', () => showSection(window.location.hash));
window.addEventListener('load', () => showSection(window.location.hash));
