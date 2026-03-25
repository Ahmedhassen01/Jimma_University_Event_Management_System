<?php

namespace App\Console\Commands;

use App\Services\FeedbackService;
use Illuminate\Console\Command;
use Carbon\Carbon;

class GenerateFeedbackReport extends Command
{
    protected $signature = 'feedback:generate-report 
                            {--period=monthly : Report period (daily, weekly, monthly, quarterly, yearly)}
                            {--start-date= : Start date (YYYY-MM-DD)}
                            {--end-date= : End date (YYYY-MM-DD)}
                            {--output=console : Output format (console, json, email)}';
    
    protected $description = 'Generate feedback analytics report';
    
    protected $feedbackService;

    public function __construct(FeedbackService $feedbackService)
    {
        parent::__construct();
        $this->feedbackService = $feedbackService;
    }

    public function handle()
    {
        $period = $this->option('period');
        $output = $this->option('output');
        
        // Determine date range
        [$startDate, $endDate] = $this->getDateRange($period);
        
        $this->info("Generating feedback report for period: {$period}");
        $this->info("Date range: {$startDate->format('Y-m-d')} to {$endDate->format('Y-m-d')}");
        
        // Generate report
        $filters = [
            'start_date' => $startDate->format('Y-m-d'),
            'end_date' => $endDate->format('Y-m-d'),
        ];
        
        $report = $this->feedbackService->generateReport($filters);
        
        // Output based on format
        switch ($output) {
            case 'json':
                $this->outputJson($report);
                break;
            case 'email':
                $this->sendEmailReport($report);
                break;
            default:
                $this->outputConsole($report);
        }
        
        $this->info("Report generated successfully!");
        
        return 0;
    }
    
    private function getDateRange(string $period): array
    {
        if ($this->option('start-date') && $this->option('end-date')) {
            return [
                Carbon::parse($this->option('start-date')),
                Carbon::parse($this->option('end-date')),
            ];
        }
        
        return match ($period) {
            'daily' => [now()->subDay(), now()],
            'weekly' => [now()->subWeek(), now()],
            'monthly' => [now()->subMonth(), now()],
            'quarterly' => [now()->subQuarter(), now()],
            'yearly' => [now()->subYear(), now()],
            default => [now()->subMonth(), now()],
        };
    }
    
    private function outputConsole(array $report): void
    {
        $this->newLine();
        $this->line('===========================================');
        $this->line('FEEDBACK ANALYTICS REPORT');
        $this->line('===========================================');
        $this->newLine();
        
        $this->line("Generated: {$report['generated_at']}");
        $this->line("Period: {$report['period']['start']} to {$report['period']['end']}");
        $this->newLine();
        
        // Summary
        $this->line('📊 SUMMARY');
        $this->line('-------------------------------------------');
        $this->table(
            ['Metric', 'Value'],
            [
                ['Total Feedbacks', $report['summary']['total_feedbacks']],
                ['Average Rating', $report['summary']['average_rating']],
                ['Completion Rate', $report['summary']['completion_rate'] . '%'],
            ]
        );
        
        // Performance Metrics
        $this->newLine();
        $this->line('⚡ PERFORMANCE METRICS');
        $this->line('-------------------------------------------');
        $this->table(
            ['Metric', 'Value'],
            [
                ['Avg Response Time', $report['performance_metrics']['average_response_time_hours'] . ' hours'],
                ['Resolution Rate', $report['performance_metrics']['resolution_rate'] . '%'],
            ]
        );
        
        // Distribution
        $this->newLine();
        $this->line('📈 DISTRIBUTION');
        
        $this->line('By Type:');
        foreach ($report['distribution']['by_type'] as $type => $count) {
            $this->line("  {$type}: {$count}");
        }
        
        $this->newLine();
        $this->line('By Status:');
        foreach ($report['distribution']['by_status'] as $status => $count) {
            $this->line("  {$status}: {$count}");
        }
        
        // Insights
        $this->newLine();
        $this->line('💡 INSIGHTS');
        $this->line('-------------------------------------------');
        foreach ($report['insights'] as $insight) {
            $this->line("• {$insight}");
        }
        
        // Recommendations
        $this->newLine();
        $this->line('🎯 RECOMMENDATIONS');
        $this->line('-------------------------------------------');
        foreach ($report['recommendations'] as $recommendation) {
            $this->line("• {$recommendation}");
        }
        
        $this->newLine();
        $this->line('===========================================');
    }
    
    private function outputJson(array $report): void
    {
        $filename = 'feedback-report-' . date('Y-m-d-H-i-s') . '.json';
        file_put_contents(storage_path("app/reports/{$filename}"), json_encode($report, JSON_PRETTY_PRINT));
        $this->info("Report saved to: storage/app/reports/{$filename}");
    }
    
    private function sendEmailReport(array $report): void
    {
        // Get admin emails
        $admins = \App\Models\User::whereHas('role', function ($query) {
            $query->whereIn('slug', ['super-admin', 'admin']);
        })->pluck('email')->toArray();
        
        if (empty($admins)) {
            $this->error('No admin users found to send email to.');
            return;
        }
        
        foreach ($admins as $email) {
            \Illuminate\Support\Facades\Mail::to($email)->send(
                new \App\Mail\FeedbackReportMail($report)
            );
            $this->info("Report sent to: {$email}");
        }
    }
}