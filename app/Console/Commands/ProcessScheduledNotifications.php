<?php

namespace App\Console\Commands;

use App\Services\NotificationService;
use Illuminate\Console\Command;

class ProcessScheduledNotifications extends Command
{
    protected $signature = 'notifications:process-scheduled';
    protected $description = 'Process and send scheduled notifications';
    
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        parent::__construct();
        $this->notificationService = $notificationService;
    }

    public function handle()
    {
        $this->info('Starting scheduled notifications processing...');
        
        $results = $this->notificationService->processScheduledNotifications();
        
        if (empty($results)) {
            $this->info('No scheduled notifications to process.');
            return 0;
        }
        
        $this->table(['Notification ID', 'Sent Count', 'Status'], $results);
        $this->info('Processed ' . count($results) . ' scheduled notifications.');
        
        return 0;
    }
}