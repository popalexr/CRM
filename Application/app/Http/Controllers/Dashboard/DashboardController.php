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
    public function saveReminder(Request $request)
    {
        $user = $request->user();
        $reminder = $request->input('reminder');
        $user->reminder_dashboard = json_encode($reminder);
        $user->save();
        return redirect()->back()->with('success', 'Reminder saved!');
    }
    private function getRevenueChartData($startDate, $endDate, $filter): array
    {
        $databaseConnection = DB::connection()->getDriverName();
        $dateFormat = match ($databaseConnection) {
            'mysql' => "DATE_FORMAT(created_at, '%Y-%m')",
            'sqlite' => "strftime('%Y-%m', created_at)",
            default => "DATE_FORMAT(created_at, '%Y-%m')",
        };

        if ($filter === 'this_month' || $filter === 'last_30_days') {
            $groupFormat = '%Y-%m-%d';
            $labelFormat = 'd M';
        } elseif ($filter === 'this_quarter') {
            $groupFormat = '%Y-%m';
            $labelFormat = 'M Y';
        } elseif ($filter === 'this_year') {
            $groupFormat = '%Y-%m';
            $labelFormat = 'M Y';
        } else {
            $groupFormat = '%Y-%m';
            $labelFormat = 'M Y';
        }

        $dateFormat = $databaseConnection === 'sqlite' ? "strftime('$groupFormat', created_at)" : "DATE_FORMAT(created_at, '$groupFormat')";

        $revenueData = Invoices::select(
                DB::raw('SUM(total) as total'),
                DB::raw("{$dateFormat} as period")
            )
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('period')
            ->orderBy('period', 'asc')
            ->get();

        $labels = [];
        $data = [];

        $periods = [];
        $current = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);
        if ($groupFormat === '%Y-%m-%d') {
            while ($current <= $end) {
                $periods[] = $current->format('Y-m-d');
                $labels[] = $current->format($labelFormat);
                $current->addDay();
            }
        } else {
            while ($current <= $end) {
                $periods[] = $current->format('Y-m');
                $labels[] = $current->format($labelFormat);
                $current->addMonth();
            }
        }

        foreach ($periods as $i => $periodStr) {
            $revenue = $revenueData->firstWhere('period', $periodStr);
            $data[] = $revenue ? $revenue->total : 0;
        }

        return ['labels' => $labels, 'data' => $data];
    }
    public function __invoke(Request $request)
    {
        $filter = $request->input('filter', 'last_30_days');
        $period = $this->getDateRange($filter);
        $startDate = $period['start'];
        $endDate = $period['end'];

        $stats = [
            [
                'title' => 'Total Clients',
                'value' => Clients::whereBetween('created_at', [$startDate, $endDate])->count(),
                'icon' => 'Users',
            ],
            [
                'title' => 'Ended Invoices',
                'value' => Invoices::where('status', 'finalized')
                    ->whereNull('deleted_at')
                    ->whereBetween('created_at', [$startDate, $endDate])
                    ->count(),
                'icon' => 'Package',
            ],
            [
                'title' => 'Running Invoices',
                'value' => Invoices::where('status', 'active')->whereBetween('created_at', [$startDate, $endDate])->count(),
                'icon' => 'FileText',
            ],
            [
                'title' => 'Pending Invoices',
                'value' => Invoices::where('status', 'draft')
                    ->where('payment_status', 'unpaid')
                    ->whereNull('deleted_at')
                    ->where(function($query) {
                        $query->whereNull('due_date')->orWhere('due_date', '>=', now());
                    })
                    ->whereBetween('created_at', [$startDate, $endDate])
                    ->count(),
                'icon' => 'AlertTriangle',
            ],
        ];
        $totalInvoicesCount = Invoices::whereNull('deleted_at')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();

        $paidInvoicesCount = Invoices::whereNull('deleted_at')
            ->whereIn('status', ['finalized', 'paid'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();

        $topClients = Invoices::with('client')
            ->select('client_id', DB::raw('SUM(total) as total_revenue'))
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('client_id')
            ->orderBy('total_revenue', 'desc')
            ->limit(5)
            ->get()
            ->map(fn($invoice) => [
                'name' => $invoice->client->client_name ?? 'Deleted Client',
                'value' => number_format($invoice->total_revenue, 2) . ' RON',
            ]);

        $overdueInvoices = Invoices::with('client')
            ->where(function($query) {
                $query->where('status', 'unpaid')
                      ->orWhere('status', 'draft');
            })
            ->where('due_date', '<', now())
            ->whereNull('deleted_at')
            ->orderBy('due_date', 'asc')
            ->get()
            ->map(fn($invoice) => [
                'name' => $invoice->client && $invoice->client->client_name ? $invoice->client->client_name : 'Unknown Client',
                'value' => $invoice->due_date ? ('Overdue by ' . $invoice->due_date->diffInDays(now()) . ' days') : 'Overdue',
                'id' => $invoice->id,
                'status' => $invoice->status,
                'deleted_at' => $invoice->deleted_at,
            ]);

        $revenueChartData = $this->getRevenueChartData($startDate, $endDate, $filter);

        return Inertia::render('Dashboard', [
            'stats' => $stats,
            'topClients' => $topClients,
            'overdueInvoices' => $overdueInvoices,
            'revenueData' => $revenueChartData,
            'currentFilter' => $filter,
            'dashboardLang' => __('dashboard'),
            'reminderDashboard' => auth()->user()->reminder_dashboard,
            'paidInvoicesCount' => $paidInvoicesCount,
            'totalInvoicesCount' => $totalInvoicesCount,
        ]);
    }

    private function getDateRange(string $filter): array
    {
        return match ($filter) {
            'this_month' => ['start' => Carbon::now()->startOfMonth(), 'end' => Carbon::now()->endOfMonth()],
            'this_quarter' => ['start' => Carbon::now()->startOfQuarter(), 'end' => Carbon::now()->endOfQuarter()],
            'this_year' => ['start' => Carbon::now()->startOfYear(), 'end' => Carbon::now()->endOfYear()],
            default => ['start' => Carbon::now()->subDays(29), 'end' => Carbon::now()],
        };
    }

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