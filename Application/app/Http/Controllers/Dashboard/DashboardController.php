<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Clients;
use App\Models\Invoices;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class DashboardController extends Controller
{
    private string $filter;

    private string $startDate;
    private string $endDate;

    public function __construct
    (
        public Request $request
    )
    {
        $this->filter = $this->request->input('filter', 'last_30_days');
    }

    public function __invoke()
    {
        $period = $this->getDateRange($this->filter);
        $this->startDate = $period['start'];
        $this->endDate = $period['end'];

        return Inertia::render('Dashboard', [
            'stats' => $this->getStats(),
            'topClients' => $this->getTopClients(),
            'overdueInvoices' => $this->getOverdueInvoices(),
            'revenueData' => $this->getRevenueChartData($this->startDate, $this->endDate, $this->filter),
            'currentFilter' => $this->filter,
            'dashboardLang' => __('dashboard'),
            'reminderDashboard' => auth()->user()->reminder_dashboard,
            'paidInvoicesCount' => $this->getTotalPaidInvoices(),
            'totalInvoicesCount' => $this->getTotalInvoices(),
            'endedInvoicesCount' => $this->getEndedInvoicesCount(),
        ]);
    }

    public function saveReminder(Request $request)
    {
        $user = $request->user();
        $reminder = $request->input('reminder');
        $user->reminder_dashboard = json_encode($reminder);
        $user->save();
        return redirect()->back()->with('success', 'Reminder saved!');
    }

    private function getStats(): array
    {
        return [
            [
                'title' => 'Total Clients',
                'value' => Clients::whereBetween('created_at', [$this->startDate, $this->endDate])->count(),
                'icon' => 'Users',
            ],
            [
                'title' => 'Ended Invoices',
                'value' => Invoices::where('status', 'finalized')
                    ->whereNull('deleted_at')
                    ->whereBetween('created_at', [$this->startDate, $this->endDate])
                    ->count(),
                'icon' => 'Package',
            ],
            [
                'title' => 'Running Invoices',
                'value' => Invoices::whereNull('deleted_at')
                    ->whereNotIn('status', ['paid', 'finalized'])
                    ->whereBetween('created_at', [$this->startDate, $this->endDate])
                    ->count(),
                'icon' => 'FileText',
            ],
            [
                'title' => 'Invoices Due Soon',
                'value' => Invoices::whereNull('deleted_at')
                    ->whereNotIn('status', ['paid', 'finalized'])
                    ->whereNotNull('payment_deadline')
                    ->whereDate('payment_deadline', '>=', now()->toDateString())
                    ->whereDate('payment_deadline', '<=', now()->addDays(7)->toDateString())
                    ->count(),
                'icon' => 'AlertTriangle',
            ],
        ];
    }

    private function getTotalInvoices(): int
    {
        return Invoices::whereNull('deleted_at')
            ->whereBetween('created_at', [$this->startDate, $this->endDate])
            ->count();
    }

    private function getTotalPaidInvoices(): int
    {
        return Invoices::whereNull('deleted_at')
            ->whereIn('status', ['finalized', 'paid'])
            ->whereBetween('created_at', [$this->startDate, $this->endDate])
            ->count();
    }

    private function getEndedInvoicesCount(): int
    {
        return Invoices::whereNull('deleted_at')
            ->where('status', 'paid')
            ->whereBetween('created_at', [$this->startDate, $this->endDate])
            ->count();
    }

    private function getTopClients(): array
    {
        return Invoices::with('client')
            ->select('client_id', DB::raw('SUM(total) as total_revenue'))
            ->whereBetween('created_at', [$this->startDate, $this->endDate])
            ->groupBy('client_id')
            ->orderBy('total_revenue', 'desc')
            ->limit(5)
            ->get()
            ->map(fn($invoice) => [
                'name' => $invoice->client->client_name ?? 'Deleted Client',
                'value' => number_format($invoice->total_revenue, 2) . ' RON',
            ])
            ->toArray();
    }

    private function getOverdueInvoices(): array
    {
        return Invoices::with('client')
            ->where(function($query) {
                $query->where('status', 'unpaid')
                      ->orWhere('status', 'draft');
            })
            ->where('payment_deadline', '<', now())
            ->whereNull('deleted_at')
            ->orderBy('payment_deadline', 'asc')
            ->get()
            ->map(fn($invoice) => [
                'name' => $invoice->client && $invoice->client->client_name ? $invoice->client->client_name : 'Unknown Client',
                'value' => $invoice->payment_deadline ? ('Overdue by ' . floor((new Carbon($invoice->payment_deadline))->diffInDays(now())) . ' days') : 'Overdue',
                'id' => $invoice->id,
                'status' => $invoice->status,
                'deleted_at' => $invoice->deleted_at,
            ])
            ->toArray();
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