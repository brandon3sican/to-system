@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
        <div class="dashboard-content">
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon bg-primary">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stat-info">
                        <h3>125</h3>
                        <p>Total Employees</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon bg-info">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="stat-info">
                        <h3>102</h3>
                        <p>Active Users</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon bg-danger">
                        <i class="fas fa-user-check"></i>
                    </div>
                    <div class="stat-info">
                        <h3>32</h3>
                        <p>Inactive Users</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon bg-success">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="stat-info">
                        <h3>45</h3>
                        <p>Active Requests</p>
                    </div>
                </div>
            </div>

            <div class="dashboard-row">
                <div class="dashboard-col" style="flex: 3;">
                    <div class="card">
                        <div class="card-header">
                            <h3>Travel Orders</h3>
                        </div>
                        <div class="card-body">
                            <div class="status-section">
                                <div class="status-grid">
                                    <div class="status-card">
                                        <div class="status-icon bg-info">
                                            <i class="fas fa-check-circle"></i>
                                        </div>
                                        <div class="status-info">
                                            <h4>Approved</h4>
                                            <p>5</p>
                                        </div>
                                    </div>
                                    <div class="status-card">
                                        <div class="status-icon bg-danger">
                                            <i class="fas fa-times-circle"></i>
                                        </div>
                                        <div class="status-info">
                                            <h4>Disapproved</h4>
                                            <p>5</p>
                                        </div>
                                    </div>
                                    <div class="status-card">
                                        <div class="status-icon bg-success">
                                            <i class="fas fa-check-double"></i>
                                        </div>
                                        <div class="status-info">
                                            <h4>Completed</h4>
                                            <p>5</p>
                                        </div>
                                    </div>
                                    <div class="status-card">
                                        <div class="status-icon bg-secondary">
                                            <i class="fas fa-times-circle"></i>
                                        </div>
                                        <div class="status-info">
                                            <h4>Canceled</h4>
                                            <p>5</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="dashboard-col" style="flex: 1;">
                    <div class="card">
                        <div class="card-header">
                            <h3>Updates</h3>
                        </div>
                        <div class="card-body">
                            <div class="to-list">
                                <div class="to-item">
                                    <a href="travel-order-detail.html?to=2025-001" class="to-link">
                                        <div class="to-icon bg-primary">
                                            <i class="fas fa-check"></i>
                                        </div>
                                        <div class="to-info">
                                            <h4>TO #2025-001</h4>
                                            <p>Recommended for approval</p>
                                            <span class="to-status status-recommended">Recommended</span>
                                        </div>
                                    </a>
                                </div>
                                <div class="to-item">
                                    <a href="travel-order-detail.html?to=2025-002" class="to-link">
                                        <div class="to-icon bg-success">
                                            <i class="fas fa-check-circle"></i>
                                        </div>
                                        <div class="to-info">
                                            <h4>TO #2025-002</h4>
                                            <p>Has been approved</p>
                                            <span class="to-status status-approved">Approved</span>
                                        </div>
                                    </a>
                                </div>
                                <div class="to-item">
                                    <a href="travel-order-detail.html?to=2025-003" class="to-link">
                                        <div class="to-icon bg-info">
                                            <i class="fas fa-plus"></i>
                                        </div>
                                        <div class="to-info">
                                            <h4>TO #2025-003</h4>
                                            <p>Created TO request</p>
                                            <span class="to-status status-created">Created</span>
                                        </div>
                                    </a>
                                </div>
                                <div class="to-item">
                                    <a href="travel-order-detail.html?to=2025-004" class="to-link">
                                        <div class="to-icon bg-success">
                                            <i class="fas fa-check-double"></i>
                                        </div>
                                        <div class="to-info">
                                            <h4>TO #2025-004</h4>
                                            <p>Completed</p>
                                            <span class="to-status status-completed">Completed</span>
                                        </div>
                                    </a>
                                </div>
                                <div class="to-item">
                                    <a href="travel-order-detail.html?to=2025-005" class="to-link">
                                        <div class="to-icon bg-danger">
                                            <i class="fas fa-times"></i>
                                        </div>
                                        <div class="to-info">
                                            <h4>TO #2025-005</h4>
                                            <p>Disapproved</p>
                                            <span class="to-status status-disapproved">Disapproved</span>
                                        </div>
                                    </a>
                                </div>
                            </div>
                            <div class="pagination">
                                <button class="btn btn-outline-primary btn-sm prev-page" disabled>Previous</button>
                                <span class="page-info">Page 1 of 3</span>
                                <button class="btn btn-primary btn-sm next-page">Next</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
@endsection
