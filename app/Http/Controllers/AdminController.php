<?php

namespace App\Http\Controllers;

use App\Http\Requests\Admin\DateRangeRequest;
use App\Http\Requests\Admin\SearchRequest;
use App\Models\Inscripcion;
use Illuminate\Http\Request;
use App\Services\Admin\DashboardService;
use App\Services\Admin\SearchService;
use App\Services\Admin\StatisticsService;

class AdminController extends Controller
{
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function dashboard(DateRangeRequest $request, DashboardService $dashboardService)
    {
        return response()->json($dashboardService->build($request->validated()));
    }

    public function dashboardView(DashboardService $dashboardService)
    {

        return view('admin.dashboard', $dashboardService->build([]));
    }

    public function aspirantesList()
    {
        $aspirantes = \App\Models\Aspirante::with('user')->latest()->paginate(15);

        return view('admin.aspirantes.index', compact('aspirantes'));
    }

    public function statisticsView()
    {
        return view('admin.estadisticas.index');
    }

    public function statisticsData(DateRangeRequest $request, StatisticsService $statisticsService)
    {
        return response()->json($statisticsService->payload($request->validated()));
    }

    public function advancedSearchView(SearchRequest $request, SearchService $searchService)
    {
        return view('admin.aspirantes.search', [
            'searchFields' => $searchService->availableFields(),
            'searchOperators' => $searchService->availableOperators(),
            'results' => $searchService->search($request->validated()),
        ]);
    }

    public function advancedSearchData(SearchRequest $request, SearchService $searchService)
    {
        return response()->json($searchService->search($request->validated()));
    }
}
