<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Clients;
use App\Models\Invoices;
use App\Models\Products;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        // $filter = $request->input('filter', 'last_30_days');
        // $period = $this->getDateRange($filter);
        // $startDate = $period['start'];
        // $endDate = $period['end'];

        // $stats = [
        //     [
        //         'title' => 'Total Clienți',
        //         'value' => Clients::count(),
        //         'icon' => 'Users',
        //     ],
        //     [
        //         'title' => 'Venituri Perioadă',
        //         'value' => number_format(Invoices::where('status', 'paid')->whereBetween('created_at', [$startDate, $endDate])->sum('total'), 2) . ' RON',
        //         'icon' => 'FileText',
        //     ],
        //     [
        //         'title' => 'Facturi Emise',
        //         'value' => Invoices::whereBetween('created_at', [$startDate, $endDate])->count(),
        //         'icon' => 'Package',
        //     ],
        //     [
        //         'title' => 'Facturi Scadente',
        //         'value' => Invoices::where('status', 'unpaid')->where('due_date', '<', now())->count(),
        //         'icon' => 'FileText',
        //     ],
        // ];

        // $topClients = Invoices::with('client')
        //     ->select('client_id', DB::raw('SUM(total) as total_revenue'))
        //     ->whereBetween('created_at', [$startDate, $endDate])
        //     ->groupBy('client_id')
        //     ->orderBy('total_revenue', 'desc')
        //     ->limit(5)
        //     ->get()
        //     ->map(fn($invoice) => [
        //         'name' => $invoice->client->client_name ?? 'Client Șters',
        //         'value' => number_format($invoice->total_revenue, 2) . ' RON',
        //     ]);

        // $overdueInvoices = Invoices::with('client')
        //     ->where('status', 'unpaid')
        //     ->where('due_date', '<', now())
        //     ->orderBy('due_date', 'asc')
        //     ->limit(5)
        //     ->get()
        //     ->map(fn($invoice) => [
        //         'name' => $invoice->client->client_name ?? 'Client Șters',
        //         'value' => 'Scadentă de ' . $invoice->due_date->diffInDays(now()) . ' zile',
        //     ]);

        // $revenueChartData = $this->getRevenueChartData();

        return Inertia::render('Dashboard', [
            'stats' => [],
            'topClients' => [],
            'overdueInvoices' => [],
            'revenueData' => [],
            'currentFilter' => $request->input('filter', 'last_30_days'),
            'dashboardLang' => __('dashboard'),
        ]);
    }

    // private function getDateRange(string $filter): array
    // {
    //     return match ($filter) {
    //         'this_month' => ['start' => Carbon::now()->startOfMonth(), 'end' => Carbon::now()->endOfMonth()],
    //         'this_quarter' => ['start' => Carbon::now()->startOfQuarter(), 'end' => Carbon::now()->endOfQuarter()],
    //         'this_year' => ['start' => Carbon::now()->startOfYear(), 'end' => Carbon::now()->endOfYear()],
    //         default => ['start' => Carbon::now()->subDays(29), 'end' => Carbon::now()],
    //     };
    // }

    // private function getRevenueChartData(): array
    // {
    //     $databaseConnection = DB::connection()->getDriverName();
    //     $dateFormat = match ($databaseConnection) {
    //         'mysql' => "DATE_FORMAT(created_at, '%Y-%m')",
    //         'sqlite' => "strftime('%Y-%m', created_at)",
    //         default => "DATE_FORMAT(created_at, '%Y-%m')",
    //     };

    //     $revenueData = Invoices::select(
    //             DB::raw('SUM(total) as total'),
    //             DB::raw("{$dateFormat} as month")
    //         )
    //         ->where('created_at', '>=', Carbon::now()->subMonths(5)->startOfMonth())
    //         ->groupBy('month')
    //         ->orderBy('month', 'asc')
    //         ->get();

    //     $labels = [];
    //     $data = [];
    //     $period = Carbon::now()->subMonths(5)->startOfMonth()->toPeriod(Carbon::now()->startOfMonth(), '1 month');

    //     foreach ($period as $date) {
    //         $monthStr = $date->format('Y-m');
    //         $labels[] = $date->format('M Y');
    //         $revenue = $revenueData->firstWhere('month', $monthStr);
    //         $data[] = $revenue ? $revenue->total : 0;
    //     }

    //     return ['labels' => $labels, 'data' => $data];
    // }
}