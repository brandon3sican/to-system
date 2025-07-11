<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\TravelOrder;

class DashboardController extends Controller
{
    public function index()
    {
        // Get statistics
        $totalEmployees = 125;
        $activeUsers = 102;
        $inactiveUsers = 32;
        $activeRequests = 45;

        // Get status counts
        $approvedCount = 5;
        $disapprovedCount = 5;
        $completedCount = 5;
        $canceledCount = 5;

        // Get recent travel orders
        $recentTravelOrders = [
            [
                'id' => 1,
                'to_number' => '2025-001',
                'status_class' => 'recommend',
                'status_icon' => 'fa-check',
                'status_text' => 'Recommended',
                'status_message' => 'Recommended for approval'
            ],
            [
                'id' => 2,
                'to_number' => '2025-002',
                'status_class' => 'approved',
                'status_icon' => 'fa-check-circle',
                'status_text' => 'Approved',
                'status_message' => 'Has been approved'
            ],
            [
                'id' => 3,
                'to_number' => '2025-003',
                'status_class' => 'created',
                'status_icon' => 'fa-plus',
                'status_text' => 'Created',
                'status_message' => 'Created TO request'
            ],
            [
                'id' => 4,
                'to_number' => '2025-004',
                'status_class' => 'completed',
                'status_icon' => 'fa-check-double',
                'status_text' => 'Completed',
                'status_message' => 'Completed'
            ],
            [
                'id' => 5,
                'to_number' => '2025-005',
                'status_class' => 'disapproved',
                'status_icon' => 'fa-times',
                'status_text' => 'Disapproved',
                'status_message' => 'Disapproved'
            ]
        ];

        return view('dashboard', [
            'totalEmployees' => $totalEmployees,
            'activeUsers' => $activeUsers,
            'inactiveUsers' => $inactiveUsers,
            'activeRequests' => $activeRequests,
            'approvedCount' => $approvedCount,
            'disapprovedCount' => $disapprovedCount,
            'completedCount' => $completedCount,
            'canceledCount' => $canceledCount,
            'recentTravelOrders' => $recentTravelOrders
        ]);
    }
}
