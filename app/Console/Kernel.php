<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Console\Commands\ScheduledCommand;
use agoalofalife\Reports\Console\ParseReportsCommand;
// use App\Task;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [

    //This is the line of code added, at the end, we the have class name of ScheduledCommand.php inside app\console\commands
        '\App\Console\Commands\ScheduledCommand',
        '\App\Console\Commands\ReportCommand',
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        //insert name and signature of you command and define the time of excusion
        // if ($this->S_everyMinute() == "everyMinute()") {
        //     // var_dump('min');die;
        //     $schedule->command('notifications:ScheduledCommand')
        //              ->everyMinute();
        //     $schedule->command('Mailreports:ReportMail')
        //             ->everyMinute();
        // }elseif ($this->S_everyMinute() == "everyFiveMinutes()") {
        //     // var_dump('hr');die;
        //     $schedule->command('notifications:ScheduledCommand')
        //              ->everyFiveMinutes();
        //              $schedule->command('Mailreports:ReportMail')
        //                      ->everyFiveMinutes();
        // }elseif ($this->S_everyMinute() == "hourly()") {
        //     // var_dump('hr');die;
        //     $schedule->command('notifications:ScheduledCommand')
        //              ->hourly();
        //              $schedule->command('Mailreports:ReportMail')
        //                      ->hourly();
        // }elseif ($this->S_everyMinute() == "daily()") {
        //     // var_dump('di');die;
        //     $schedule->command('notifications:ScheduledCommand')
        //              ->daily();
        //              $schedule->command('Mailreports:ReportMail')
        //                      ->daily();
        // }elseif ($this->S_everyMinute() == 'dailyAt("08:00")') {
        //     // var_dump('at8');die;
        //     $schedule->command('notifications:ScheduledCommand')
        //              ->dailyAt("08:00");
        //     $schedule->command('Mailreports:ReportMail')
        //             ->dailyAt("08:00");
        // }elseif ($this->S_everyMinute() == "weekly()") {
        //     // var_dump('weekly');die;
        //     $schedule->command('notifications:ScheduledCommand')
        //              ->weekly();
        //     $schedule->command('Mailreports:ReportMail')
        //             ->weekly();
        // }elseif ($this->S_everyMinute() == "monthly()") {
        //     // var_dump('mon');die;
        //     $schedule->command('notifications:ScheduledCommand')
        //              ->monthly();
        //              $schedule->command('Mailreports:ReportMail')
        //                      ->monthly();
        // }elseif ($this->S_everyMinute() == "quarterly()") {
        //     // var_dump('qt');die;
        //     $schedule->command('notifications:ScheduledCommand')
        //              ->quarterly();
        //     $schedule->command('Mailreports:ReportMail')
        //             ->quarterly();
        // }elseif ($this->S_everyMinute() == "yearly()") {
        //     // var_dump('yr');die;
        //     $schedule->command('notifications:ScheduledCommand')
        //              ->yearly();
        //     $schedule->command('Mailreports:ReportMail')
        //             ->yearly();
        // }else{
        //     // var_dump('qqq');die;
        //     $schedule->command('notifications:ScheduledCommand')
        //              ->monthly();
        //     $schedule->command('Mailreports:ReportMail')
        //             ->monthly();
        // }
        $schedule->command('notifications:ScheduledCommand')
                 ->everyTenMinutes();
        // $schedule->command('Mailreports:ReportMail')
                // ->hourly();
        // $schedule->command('Mailreports:ReportMail')
        //         ->twiceDaily(13, 15);
        // $schedule->command(ParseReportsCommand::class)->daily();

    }

    // public function S_everyMinute()
    // {
    //     $schedule_t = Task::first();
    //     return $schedule_time = $schedule_t->schedule;
    //     return;
    // }
    

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
