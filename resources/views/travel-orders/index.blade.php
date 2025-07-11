@extends('layouts.app')

@section('title', 'Travel Orders')

@section('content')
    <div class="dashboard-content">
        <div class="dashboard-row">
            <div class="dashboard-col" style="flex: 3;">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h3>Travel Orders</h3>
                            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#createOrderModal">
                                <i class="fas fa-plus me-1"></i>
                                New Travel Order
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <div class="input-group">
                                        <input type="text" class="form-control" placeholder="Search...">
                                        <button class="btn btn-outline-secondary" type="button">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <select class="form-select">
                                        <option value="">All Status</option>
                                        <option value="recommend">For Recommendation</option>
                                        <option value="approve">For Approval</option>
                                        <option value="approved">Approved</option>
                                        <option value="disapproved">Disapproved</option>
                                        <option value="completed">Completed</option>
                                        <option value="cancelled">Cancelled</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <select class="form-select">
                                        <option value="">All Departments</option>
                                        @foreach($departments as $department)
                                            <option value="{{ $department->id }}">{{ $department->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th class="text-center">TO #</th>
                                        <th>Full Name</th>
                                        <th>Position</th>
                                        <th>Department</th>
                                        <th>Date</th>
                                        <th>Approver</th>
                                        <th>Position</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($travelOrders as $order)
                                        <tr>
                                            <td class="text-center">#{{ $order->to_number }}</td>
                                            <td>{{ $order->employee->full_name }}</td>
                                            <td>{{ $order->employee->position }}</td>
                                            <td>{{ $order->employee->department->name }}</td>
                                            <td>{{ $order->created_at->format('Y-m-d') }}</td>
                                            <td>{{ $order->approver->full_name }}</td>
                                            <td>{{ $order->approver->position }}</td>
                                            <td class="status-badge-cell">
                                                <span class="status-badge status-{{ $order->status_class }}">
                                                    {{ $order->status_text }}
                                                </span>
                                            </td>
                                            <td>
                                                <a href="{{ route('travel-orders.show', $order->id) }}" class="btn btn-sm btn-primary me-1">
                                                    <i class="fas fa-eye me-1"></i>
                                                    View
                                                </a>
                                                @if(in_array($order->status, ['recommend', 'approve']))
                                                    <a href="{{ route('travel-orders.print', $order->id) }}" class="btn btn-sm btn-info">
                                                        <i class="fas fa-print me-1"></i>
                                                        Print
                                                    </a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-3">
                            {{ $travelOrders->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Create Travel Order Modal -->
    <div class="modal fade" id="createOrderModal" tabindex="-1" aria-labelledby="createOrderModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createOrderModalLabel">Create New Travel Order</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="createOrderForm" action="{{ route('travel-orders.store') }}" method="POST">
                        @csrf
                        <!-- Step Navigation -->
                        <div class="steps-nav mb-4">
                            <div class="steps-indicator">
                                <div class="step active" data-step="1">
                                    <span>1</span>
                                    <span>Employee Info</span>
                                </div>
                                <div class="step" data-step="2">
                                    <span>2</span>
                                    <span>Travel Details</span>
                                </div>
                                <div class="step" data-step="3">
                                    <span>3</span>
                                    <span>Approval Details</span>
                                </div>
                            </div>
                        </div>

                        <div class="step-content active" data-step="1">
                            <h5 class="mb-4">Employee Information</h5>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="employee_id">Employee</label>
                                        <select class="form-select" id="employee_id" name="employee_id" required>
                                            <option value="">Select Employee</option>
                                            @foreach($employees as $employee)
                                                <option value="{{ $employee->id }}">
                                                    {{ $employee->full_name }} - {{ $employee->position }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="department_id">Department</label>
                                        <select class="form-select" id="department_id" name="department_id" required>
                                            <option value="">Select Department</option>
                                            @foreach($departments as $department)
                                                <option value="{{ $department->id }}">{{ $department->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="step-content" data-step="2">
                            <h5 class="mb-4">Travel Details</h5>
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="start_date">Start Date</label>
                                        <input type="date" class="form-control" id="start_date" name="start_date" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="end_date">End Date</label>
                                        <input type="date" class="form-control" id="end_date" name="end_date" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="destination">Destination</label>
                                        <input type="text" class="form-control" id="destination" name="destination" required>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="step-content" data-step="3">
                            <h5 class="mb-4">Approval Details</h5>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="approver_id">Approver</label>
                                        <select class="form-select" id="approver_id" name="approver_id" required>
                                            <option value="">Select Approver</option>
                                            @foreach($approvers as $approver)
                                                <option value="{{ $approver->id }}">
                                                    {{ $approver->full_name }} - {{ $approver->position }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="purpose">Purpose</label>
                                        <textarea class="form-control" id="purpose" name="purpose" rows="3" required></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="submitOrderForm()">Create Travel Order</button>
                </div>
            </div>
        </div>
    </div>
@endsection
