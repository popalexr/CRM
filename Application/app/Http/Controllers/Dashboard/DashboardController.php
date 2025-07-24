<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Clients;
use App\Models\Invoices;
use App\Models\Products;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $stats = [
            [
                'title' => 'Total Clienți',
                'value' => Clients::count(),
                'icon' => 'Users',
            ],
            [
                'title' => 'Total Produse & Servicii',
                'value' => Products::count(),
                'icon' => 'Package',
            ],
            [
                'title' => 'Venituri Luna Curentă',
                'value' => number_format(Invoices::where('status', 'paid')->whereMonth('created_at', now()->month)->sum('total'), 2) . ' RON',
                'icon' => 'FileText',
            ],
            [
                'title' => 'Facturi Scadente',
                'value' => Invoices::where('status', 'unpaid')->where('due_date', '<', now())->count(),
                'icon' => 'FileText',
            ],
        ];

        $recentInvoices = Invoices::with('client')
            ->latest()
            ->take(5)
            ->get()
            ->map(fn($invoice) => [
                'id' => $invoice->id,
                'client_name' => $invoice->client->client_name ?? 'Client Șters',
                'total' => $invoice->total,
                'currency' => $invoice->currency,
                'status' => $invoice->status,
            ]);

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
            ->where('created_at', '>=', Carbon::now()->subMonths(5)->startOfMonth())
            ->groupBy('month')
            ->orderBy('month', 'asc')
            ->get();

        $labels = [];
        $data = [];
        
        $period = Carbon::now()->subMonths(5)->startOfMonth()->toPeriod(Carbon::now()->startOfMonth(), '1 month');

        foreach ($period as $date) {
            $monthStr = $date->format('Y-m');
            $labels[] = $date->format('M Y');
            $revenue = $revenueData->firstWhere('month', $monthStr);
            $data[] = $revenue ? $revenue->total : 0;
        }

        $revenueChartData = [
            'labels' => $labels,
            'data' => $data,
        ];

        return Inertia::render('Dashboard', [
            'stats' => $stats,
            'recentInvoices' => $recentInvoices,
            'revenueData' => $revenueChartData,
        ]);
    }
}