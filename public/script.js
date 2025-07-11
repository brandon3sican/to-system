// Smooth scroll for navigation
window.addEventListener('scroll', () => {
    const header = document.querySelector('header');
    if (window.scrollY > 50) {
        header.style.boxShadow = '0 2px 10px rgba(0,0,0,0.1)';
    } else {
        header.style.boxShadow = 'none';
    }
});

// Filter functionality for travel orders table
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const statusFilter = document.getElementById('statusFilter');
    const dateFilter = document.getElementById('dateFilter');
    const tableRows = document.querySelectorAll('.table tbody tr');

    // Search functionality
    searchInput.addEventListener('input', filterTable);

    // Status filter
    statusFilter.addEventListener('change', filterTable);

    // Date filter
    dateFilter.addEventListener('change', filterTable);

    function filterTable() {
        const searchTerm = searchInput.value.toLowerCase();
        const selectedStatus = statusFilter.value;
        const selectedDate = dateFilter.value;
        const today = new Date();
        const currentYear = today.getFullYear();
        const currentMonth = today.getMonth();
        const currentWeek = Math.floor((today.getDate() - 1) / 7) + 1;

        tableRows.forEach(row => {
            const rowData = row.textContent.toLowerCase();
            const statusCell = row.querySelector('.status-badge');
            const dateCell = row.querySelector('td:nth-child(5)').textContent;
            let date = new Date(dateCell);

            // Search filter
            const matchesSearch = rowData.includes(searchTerm);

            // Status filter
            const matchesStatus = !selectedStatus || 
                (statusCell && statusCell.className.includes(`status-${selectedStatus}`));

            // Date filter
            let matchesDate = true;
            if (selectedDate) {
                switch(selectedDate) {
                    case 'today':
                        matchesDate = date.toDateString() === today.toDateString();
                        break;
                    case 'week':
                        const weekStartDate = new Date(today);
                        weekStartDate.setDate(today.getDate() - (today.getDay() + 6) % 7);
                        matchesDate = date >= weekStartDate;
                        break;
                    case 'month':
                        matchesDate = date.getMonth() === currentMonth && date.getFullYear() === currentYear;
                        break;
                    case 'year':
                        matchesDate = date.getFullYear() === currentYear;
                        break;
                }
            }

            // Show/hide row based on all filters
            if (matchesSearch && matchesStatus && matchesDate) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }

    // Reset filters
    function resetFilters() {
        searchInput.value = '';
        statusFilter.value = '';
        dateFilter.value = '';
        filterTable();
    }
});

// Form submission functionality
function submitOrderForm() {
    const form = document.getElementById('createOrderForm');
    
    // Validate current step
    if (!form.checkValidity()) {
        form.classList.add('was-validated');
        return;
    }

    // Get form data
    const formData = {
        toNumber: '', // Will be generated
        employeeName: document.getElementById('employeeName').value,
        position: document.getElementById('position').value,
        division: document.getElementById('division').value,
        salary: document.getElementById('salary').value,
        officialStation: document.getElementById('officialStation').value,
        departureDate: document.getElementById('departureDate').value,
        destination: document.getElementById('destination').value,
        arrivalDate: document.getElementById('arrivalDate').value,
        purpose: document.getElementById('purpose').value,
        perDiem: document.querySelector('input[name="perDiem"]:checked').value,
        assistants: document.getElementById('assistants').value,
        appropriations: document.getElementById('appropriations').value,
        remarks: document.getElementById('remarks').value,
        recommender: document.getElementById('recommender').value,
        finalApprover: document.getElementById('finalApprover').value,
        status: 'recommend', // Default status for new orders
        createdAt: new Date().toISOString()
    };

    // Generate TO number
    const today = new Date();
    const year = today.getFullYear();
    const month = String(today.getMonth() + 1).padStart(2, '0');
    const day = String(today.getDate()).padStart(2, '0');
    formData.toNumber = `TO #${year}-${month}-${day}-${Math.floor(Math.random() * 1000).toString().padStart(3, '0')}`;

    // Create new row
    const tbody = document.querySelector('.table tbody');
    const newRow = document.createElement('tr');
    newRow.innerHTML = `
        <td>${formData.toNumber}</td>
        <td>${formData.employeeName}</td>
        <td>${formData.position}</td>
        <td>${formData.division}</td>
        <td>${formData.officialStation}</td>
        <td>${formData.departureDate}</td>
        <td>${document.querySelector('#recommender option[value="' + formData.recommender + '"]').text}</td>
        <td>${document.querySelector('#finalApprover option[value="' + formData.finalApprover + '"]').text}</td>
        <td>
            <span class="status-badge status-${formData.status}">
                <i class="fas fa-clock me-1"></i>
                For Recommendation
            </span>
        </td>
        <td>
            <button class="btn btn-sm btn-primary" onclick="viewOrderDetail('${formData.toNumber}')">
                <i class="fas fa-eye me-1"></i>
                View
            </button>
        </td>
    `;

    // Add row to table and show success message
    tbody.insertBefore(newRow, tbody.firstChild);

    // Reset form and close modal
    form.reset();
    const modal = bootstrap.Modal.getInstance(document.getElementById('createOrderModal'));
    modal.hide();

    // Show success message
    const successAlert = document.createElement('div');
    successAlert.className = 'alert alert-success alert-dismissible fade show position-fixed top-0 end-0 m-3';
    successAlert.role = 'alert';
    successAlert.innerHTML = `
        <i class="fas fa-check-circle me-2"></i>
        Travel Order ${formData.toNumber} created successfully!
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    `;
    document.body.appendChild(successAlert);

    // Auto-hide alert after 3 seconds
    setTimeout(() => {
        successAlert.remove();
    }, 3000);
}

// Step navigation
function nextStep(nextStep) {
    // Get current step
    const currentStep = getCurrentStep();
    
    // Get form elements for current step
    const form = document.getElementById('createOrderForm');
    const currentStepContent = document.querySelector(`.step-content[data-step="${currentStep}"]`);
    
    // Validate current step
    if (!form.checkValidity()) {
        form.classList.add('was-validated');
        // Focus on first invalid field
        const invalidFields = currentStepContent.querySelectorAll(':invalid');
        if (invalidFields.length > 0) {
            invalidFields[0].focus();
            // Show error message
            const errorMessage = document.createElement('div');
            errorMessage.className = 'alert alert-danger alert-dismissible fade show mb-3';
            errorMessage.innerHTML = `
                <i class="fas fa-exclamation-circle me-2"></i>
                Please fill in all required fields before proceeding.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            `;
            currentStepContent.insertBefore(errorMessage, currentStepContent.firstChild);
            setTimeout(() => {
                errorMessage.remove();
            }, 3000);
        }
        return;
    }

    // Reset validation class
    form.classList.remove('was-validated');

    // Update steps
    updateStep(currentStep, false);
    updateStep(nextStep, true);

    // Scroll to top of new step
    const newStepContent = document.querySelector(`.step-content[data-step="${nextStep}"]`);
    if (newStepContent) {
        newStepContent.scrollIntoView({ behavior: 'smooth' });
    }
}

function prevStep(prevStep) {
    // Get current step
    const currentStep = getCurrentStep();
    
    // Reset validation class
    const form = document.getElementById('createOrderForm');
    form.classList.remove('was-validated');

    // Update steps
    updateStep(currentStep, false);
    updateStep(prevStep, true);

    // Scroll to top of previous step
    const prevStepContent = document.querySelector(`.step-content[data-step="${prevStep}"]`);
    if (prevStepContent) {
        prevStepContent.scrollIntoView({ behavior: 'smooth' });
    }
}

function getCurrentStep() {
    const activeStep = document.querySelector('.step.active');
    if (!activeStep) return 1; // Default to first step if none is active
    return parseInt(activeStep.getAttribute('data-step'));
}

function updateStep(stepNumber, isActive) {
    const step = document.querySelector(`.step[data-step="${stepNumber}"]`);
    const stepContent = document.querySelector(`.step-content[data-step="${stepNumber}"]`);
    
    if (!step || !stepContent) {
        console.error(`Step ${stepNumber} not found`);
        return;
    }

    if (isActive) {
        step.classList.add('active');
        stepContent.classList.add('active');
    } else {
        step.classList.remove('active');
        stepContent.classList.remove('active');
    }
}

    // Reset form and close modal
    form.reset();
    const modal = bootstrap.Modal.getInstance(document.getElementById('createOrderModal'));
    modal.hide();

    // Show success message
    const successAlert = document.createElement('div');
    successAlert.className = 'alert alert-success alert-dismissible fade show position-fixed top-0 end-0 m-3';
    successAlert.role = 'alert';
    successAlert.innerHTML = `
        <i class="fas fa-check-circle me-2"></i>
        Travel Order ${formData.toNumber} created successfully!
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    `;
    document.body.appendChild(successAlert);

    // Auto-hide alert after 3 seconds
    setTimeout(() => {
        successAlert.remove();
    }, 3000);



// View order detail
function viewOrderDetail(toNumber) {
    // This would typically navigate to a detail page or open a detail modal
    // For now, we'll just log the TO number
    console.log('Viewing order:', toNumber);
}

// Toggle password visibility
function togglePasswordVisibility(fieldId) {
    const passwordInput = document.getElementById(fieldId);
    const toggleButton = passwordInput.nextElementSibling;
    const icon = toggleButton.querySelector('i');
    
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        passwordInput.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}

// Add user function
function addUser() {
    const form = document.getElementById('addUserForm');
    const password = document.getElementById('password').value;
    const confirmPassword = document.getElementById('confirmPassword').value;

    if (password !== confirmPassword) {
        alert('Passwords do not match!');
        return;
    }

    // Add validation for other fields here
    // Submit form data to server
}

// Add animations on scroll
document.addEventListener('DOMContentLoaded', () => {
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate');
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, observerOptions);

    // Observe all stat cards
    document.querySelectorAll('.stat-card, .status-card, .activity-item').forEach(element => {
        element.style.opacity = '0';
        element.style.transform = 'translateY(20px)';
        element.style.transition = 'all 0.6s ease-out';
        observer.observe(element);
    });

    // Add staggered animation for cards
    const cards = document.querySelectorAll('.stat-card, .status-card');
    cards.forEach((card, index) => {
        card.style.transitionDelay = `${index * 0.1}s`;
    });
});

// Add search functionality with debounce
const searchInput = document.querySelector('.search-bar input');
let searchTimeout;

searchInput.addEventListener('input', (e) => {
    clearTimeout(searchTimeout);
    
    searchTimeout = setTimeout(() => {
        const searchTerm = e.target.value.toLowerCase();
        const activityItems = document.querySelectorAll('.activity-item');
        
        activityItems.forEach(item => {
            const text = item.querySelector('p').textContent.toLowerCase();
            if (text.includes(searchTerm)) {
                item.style.display = 'flex';
                item.style.opacity = '1';
                item.style.transform = 'translateY(0)';
            } else {
                item.style.display = 'none';
            }
        });
    }, 300);
});

// Add hover effects to sidebar links
document.querySelectorAll('.sidebar nav a').forEach(link => {
    link.addEventListener('mouseenter', () => {
        link.style.transform = 'translateX(5px)';
        link.style.boxShadow = '0 4px 10px rgba(0,0,0,0.1)';
    });
    
    link.addEventListener('mouseleave', () => {
        link.style.transform = 'translateX(0)';
        link.style.boxShadow = 'none';
    });
});

// Add form validation animations
const formInputs = document.querySelectorAll('.form-control');
formInputs.forEach(input => {
    input.addEventListener('focus', () => {
        input.parentElement.classList.add('focused');
    });
    
    input.addEventListener('blur', () => {
        if (input.value === '') {
            input.parentElement.classList.remove('focused');
        }
    });
});

// Add smooth transitions for modals
const modals = document.querySelectorAll('.modal');
modals.forEach(modal => {
    modal.addEventListener('show.bs.modal', () => {
        modal.style.opacity = '0';
        modal.style.transform = 'scale(0.95)';
        modal.style.transition = 'all 0.3s ease';
        
        setTimeout(() => {
            modal.style.opacity = '1';
            modal.style.transform = 'scale(1)';
        }, 100);
    });
});

// Add pulse animation to buttons
const buttons = document.querySelectorAll('.btn');
buttons.forEach(button => {
    button.addEventListener('mouseenter', () => {
        button.style.transform = 'scale(1.02)';
        button.style.boxShadow = '0 4px 15px rgba(0,0,0,0.1)';
    });
    
    button.addEventListener('mouseleave', () => {
        button.style.transform = 'scale(1)';
        button.style.boxShadow = 'none';
    });
});

// Multi-step form functionality
let currentStep = 1;

function updateSteps() {
    // Update step indicators
    document.querySelectorAll('.step').forEach((step, index) => {
        if (index < currentStep - 1) {
            step.classList.add('completed');
        } else if (index === currentStep - 1) {
            step.classList.remove('completed');
            step.classList.add('active');
        } else {
            step.classList.remove('completed', 'active');
        }
    });

    // Show/hide form sections
    document.querySelectorAll('.form-section').forEach(section => {
        section.style.display = 'none';
    });
    
    const currentSection = document.getElementById(`step${currentStep}`);
    if (currentSection) {
        currentSection.style.display = 'block';
    }
}

// Next buttons
[1, 2, 3, 4].forEach(num => {
    document.getElementById(`next${num}`).addEventListener('click', () => {
        if (validateStep(num)) {
            currentStep++;
            updateSteps();
        }
    });
});

// Back buttons
[1, 2, 3, 4].forEach(num => {
    document.getElementById(`back${num}`).addEventListener('click', () => {
        currentStep--;
        updateSteps();
    });
});

// Review button
document.getElementById('review').addEventListener('click', () => {
    if (validateStep(5)) {
        // Show review section
        document.getElementById('reviewSection').style.display = 'block';
        // Hide current step
        document.getElementById('step5').style.display = 'none';
        // Update step indicator
        document.querySelector('.step:last-child').classList.add('completed');
        // Populate review section
        populateReview();
    }
});

// Edit button
document.getElementById('edit').addEventListener('click', () => {
    // Show last step
    document.getElementById('step5').style.display = 'block';
    // Hide review section
    document.getElementById('reviewSection').style.display = 'none';
    // Update step indicator
    document.querySelector('.step:last-child').classList.remove('completed');
    document.querySelector('.step:last-child').classList.add('active');
});

// Submit button
document.getElementById('submit').addEventListener('click', () => {
    if (validateForm()) {
        // Submit form logic here
        alert('Travel Order submitted successfully!');
        // Reset form
        resetForm();
    }
});

// Form validation
function validateStep(stepNumber) {
    const step = document.getElementById(`step${stepNumber}`);
    const inputs = step.querySelectorAll('input[required], select[required], textarea[required]');
    
    let isValid = true;
    inputs.forEach(input => {
        if (!input.value) {
            isValid = false;
            input.classList.add('is-invalid');
        } else {
            input.classList.remove('is-invalid');
        }
    });
    
    return isValid;
}

function validateForm() {
    // Validate all steps
    for (let i = 1; i <= 5; i++) {
        if (!validateStep(i)) return false;
    }
    return true;
}

// Populate review section
function populateReview() {
    document.getElementById('reviewFullName').textContent = document.getElementById('fullName').value;
    document.getElementById('reviewPosition').textContent = document.getElementById('position').value;
    document.getElementById('reviewDivision').textContent = document.getElementById('division').value;
    document.getElementById('reviewStation').textContent = document.getElementById('station').value;
    document.getElementById('reviewDeparture').textContent = document.getElementById('departureDate').value;
    document.getElementById('reviewDestination').textContent = document.getElementById('destination').value;
    document.getElementById('reviewArrival').textContent = document.getElementById('arrivalDate').value;
    document.getElementById('reviewPurpose').textContent = document.getElementById('purpose').value;
    document.getElementById('reviewPerDiem').textContent = document.getElementById('perDiem').value === 'yes' ? 'Yes' : 'No';
    document.getElementById('reviewAssistants').textContent = document.getElementById('assistants').value;
    document.getElementById('reviewAppropriations').textContent = document.getElementById('appropriations').value;
    document.getElementById('reviewRemarks').textContent = document.getElementById('remarks').value;
    document.getElementById('reviewRecommender').textContent = document.getElementById('recommender').value;
    document.getElementById('reviewFinalApprover').textContent = document.getElementById('finalApprover').value;
}

// Reset form
function resetForm() {
    // Reset all form fields
    document.querySelectorAll('input, select, textarea').forEach(input => {
        input.value = '';
        input.classList.remove('is-invalid');
    });
    
    // Reset steps
    currentStep = 1;
    updateSteps();
    
    // Hide review section
    document.getElementById('reviewSection').style.display = 'none';
    document.getElementById('step1').style.display = 'block';
    
    // Reset step indicators
    document.querySelectorAll('.step').forEach((step, index) => {
        if (index === 0) {
            step.classList.add('active');
        } else {
            step.classList.remove('completed', 'active');
        }
    });
}

// Initialize form
updateSteps();
