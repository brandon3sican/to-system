<!-- Select2 -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />

<!-- Custom Styles -->
<style>
    /* Table Styles */
    .table {
        background-color: white;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .table th {
        background-color: #f8f9fa;
        font-weight: 600;
        color: #333;
        border-bottom: 2px solid #dee2e6;
    }

    /* Action Buttons */
    /* Action Buttons */
    .btn-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
        border-radius: 6px;
        transition: all 0.2s ease;
    }

    .btn-sm:hover {
        transform: translateY(-1px);
    }

    .btn-outline-warning {
        color: #ffc107;
        border-color: #ffc107;
    }

    .btn-outline-warning:hover {
        background-color: #ffc107;
        color: white;
    }

    .btn-outline-danger {
        color: #dc3545;
        border-color: #dc3545;
    }

    .btn-outline-danger:hover {
        background-color: #dc3545;
        color: white;
    }

    .btn-sm i {
        font-size: 1rem;
    }

    /* Floating Action Button */
    .btn-lg.rounded-circle {
        width: 4rem;
        height: 4rem;
        padding: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        transition: all 0.3s ease;
    }

    .btn-lg.rounded-circle:hover {
        transform: scale(1.1);
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    }

    .btn-lg.rounded-circle i {
        font-size: 1.5rem;
    }

    /* Action Menu Items */
    .dropdown-item {
        padding: 0.75rem 1rem;
        border-radius: 6px;
        transition: all 0.2s ease;
    }

    .dropdown-item:hover {
        background-color: #f8f9fa;
        transform: translateX(5px);
    }

    .dropdown-item i {
        width: 1.25rem;
        text-align: center;
        font-size: 1rem;
    }

    .btn-group .btn-outline-secondary:hover {
        background-color: #e9ecef;
    }

    .dropdown-item {
        padding: 0.5rem 1rem;
        border-radius: 4px;
    }

    .dropdown-item:hover {
        background-color: #f8f9fa;
    }

    .dropdown-item i {
        width: 1.25rem;
        text-align: center;
    }

    /* Modal Styles */
    .modal-content {
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }

    .modal-header {
        border-bottom: 1px solid #dee2e6;
    }

    .modal-footer {
        border-top: 1px solid #dee2e6;
    }

    /* Form Controls */
    .form-control {
        border-radius: 6px;
        padding: 0.5rem 1rem;
    }

    .form-control:focus {
        box-shadow: 0 0 0 0
        .2rem rgba(0,123,255,.25);
    }

    .form-label {
        font-weight: 500;
        font-size: 0.9rem;
        font-style: bold;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    /* Success/Error Messages */
    .alert {
        border-radius: 6px;
        margin-bottom: 1rem;
    }

    /* Pagination */
    /* Pagination Styles */
    .pagination {
        margin: 0;
    }

    .pagination .page-link {
        border-radius: 6px;
        padding: 0.5rem 1rem;
        margin: 0 0.25rem;
        background-color: #f8f9fa;
        border: 1px solid #dee2e6;
        color: #6c757d;
        transition: all 0.2s ease;
    }

    .pagination .page-link:hover {
        background-color: #e9ecef;
        border-color: #dee2e6;
        color: #495057;
    }

    .pagination .page-item.active .page-link {
        background-color: #0d6efd;
        border-color: #0d6efd;
        color: white;
        z-index: 2;
    }

    .pagination .page-item.disabled .page-link {
        color: #adb5bd;
        pointer-events: none;
    }

    /* Pagination Info */
    .card-footer {
        background-color: #f8f9fa;
        border-top: 1px solid #dee2e6;
    }

    .card-footer .d-flex {
        gap: 1rem;
    }
</style>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    // Initialize Select2 on document ready
    document.addEventListener('DOMContentLoaded', function() {
        $('.select2').select2({
            theme: 'bootstrap-5',
            width: '100%',
            placeholder: 'Select an option',
            allowClear: true
        });
    });
</script>
