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
        $filter = $request->input('filter', 'last_30_days');
        $period = $this->getDateRange($filter);
        $startDate = $period['start'];
        $endDate = $period['end'];

        $stats = [
            [
                'title' => __('dashboard.total_clients'),
                'value' => Clients::whereBetween('created_at', [$startDate, $endDate])->count(),
                'icon' => 'Users',
            ],
            [
                'title' => __('dashboard.period_revenue'),
                'value' => number_format(Invoices::where('status', 'paid')->whereBetween('created_at', [$startDate, $endDate])->sum('total'), 2) . ' RON',
                'icon' => 'FileText',
            ],
            [
                'title' => __('dashboard.issued_invoices'),
                'value' => Invoices::whereBetween('created_at', [$startDate, $endDate])->count(),
                'icon' => 'Package',
            ],
            [
                'title' => __('dashboard.overdue_invoices'),
                'value' => Invoices::where('status', 'unpaid')->where('payment_deadline', '<', now())->count(),
                'icon' => 'FileText',
            ],
        ];

        $topClients = Invoices::with('client')
            ->select('client_id', DB::raw('SUM(total) as total_revenue'))
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('client_id')
            ->orderBy('total_revenue', 'desc')
            ->limit(5)
            ->get()
            ->map(fn($invoice) => [
                'name' => $invoice->client->client_name ?? 'Client Șters',
                'value' => number_format($invoice->total_revenue, 2) . ' RON',
            ]);

        $overdueInvoices = Invoices::with('client')
            ->whereIn('status', ['unpaid', 'draft'])
            ->where('payment_deadline', '<', now())
            ->whereBetween('created_at', [$startDate, $endDate])
            ->whereNull('deleted_at')
            ->orderBy('payment_deadline', 'asc')
            ->limit(5)
            ->get()
            ->map(fn($invoice) => [
                'id' => $invoice->id,
                'name' => $invoice->client->client_name ?? 'Client Șters',
                'value' => 'Overdue by ' . abs((int)Carbon::parse($invoice->payment_deadline)->diffInDays(now())) . ' days',
                'url' => route('invoices.details', ['id' => $invoice->id]),
            ]);

        $revenueChartData = $this->getRevenueChartData($startDate, $endDate, $filter);

        $overdueInvoices = Invoices::with('client')
            ->whereIn('status', ['unpaid', 'draft'])
            ->where('payment_deadline', '<', now())
            ->whereNull('deleted_at')
            ->orderBy('payment_deadline', 'asc')
            ->limit(5)
            ->get()
            ->map(fn($invoice) => [
                'name' => $invoice->client->client_name ?? 'Client Șters',
                'value' => 'Overdue by ' . abs((int)Carbon::parse($invoice->payment_deadline)->diffInDays(now())) . ' days',
                'url' => route('invoices.edit', ['invoice' => $invoice->id]),
            ]);

        $revenueChartData = $this->getRevenueChartData($startDate, $endDate, $filter);

        return Inertia::render('Dashboard', [
            'stats' => $stats,
            'topClients' => $topClients,
            'overdueInvoices' => $overdueInvoices,
            'revenueData' => $revenueChartData,
            'currentFilter' => $filter,
            'dashboardLang' => __('dashboard'),
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

    private function getRevenueChartData($startDate, $endDate, $filter): array
    {
        if ($filter === 'last_30_days') {
            // Group by week
            $labels = [];
            $data = [];
            $start = Carbon::parse($startDate)->startOfDay();
            for ($i = 0; $i < 4; $i++) {
                $weekStart = $start->copy()->addDays($i * 7);
                $weekEnd = $weekStart->copy()->addDays(6)->endOfDay();
                $labels[] = $weekStart->format('d M') . ' - ' . $weekEnd->format('d M');
                $weekSum = Invoices::whereBetween('created_at', [$weekStart, $weekEnd])->sum('total');
                $data[] = $weekSum;
            }
            return ['labels' => $labels, 'data' => $data];
        } else {
            $databaseConnection = DB::connection()->getDriverName();
            $dateFormat = match ($databaseConnection) {
                'mysql' => "DATE_FORMAT(created_at, '%Y-%m')",
                'sqlite' => "strftime('%Y-%m', created_at)",
                default => "DATE_FORMAT(created_at, '%Y-%m')",
            };

            $revenueData = Invoices::select(
                    DB::raw('SUM(total) as total'),
                    DB::raw("{$dateFormat} as month")
                )
                ->whereBetween('created_at', [$startDate, $endDate])
                ->groupBy('month')
                ->orderBy('month', 'asc')
                ->get();

            $labels = [];
            $data = [];
            $period = Carbon::parse($startDate)->toPeriod(Carbon::parse($endDate)->startOfMonth(), '1 month');

            foreach ($period as $date) {
                $monthStr = $date->format('Y-m');
                $labels[] = $date->format('M Y');
                $revenue = $revenueData->firstWhere('month', $monthStr);
                $data[] = $revenue ? $revenue->total : 0;
            }

            return ['labels' => $labels, 'data' => $data];
        }
    }
}