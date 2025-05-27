<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\DB;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\UpdateDeliveredOrders::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        //UPDATE REWARD-POINT AND HOT DEAL PRODUCT STATUS
        $schedule->call(function () {
            DB::table('products')
                ->whereIn('product_type', ['Reward Point Offer', 'Hot Deal'])
                ->where('status', '=', 'active')
                ->where('deal_end_1', '<=', time())
                ->update(['status' => 'inactive', 'deal_time' => 0]);
        })->everyMinute();

        //UPDATE COUPON STATUS
        $schedule->call(function () {
            DB::table('coupons')
                ->where('status', '=', 'active')
                ->where('expiration_date', '<=', date("Y-m-d"))
                ->update(['status' => 'expired']);
        })->daily();

        // Run the command every hour to check for orders that need to be updated
        $schedule->command('orders:update-delivered')->hourly();
    }

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
