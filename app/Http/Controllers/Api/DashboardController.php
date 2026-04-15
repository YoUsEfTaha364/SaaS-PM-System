<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\api\ApiResponseService;
use App\Services\DashboardService;

class DashboardController extends Controller
{
    protected $dashboardService;

    public function __construct(DashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    public function index()
    {
        $data = $this->dashboardService->getDashboardData();

        return ApiResponseService::response(200, 'get dashboard data', $data);
    }
}
