<?php

namespace App\Console\Commands;

use App\Models\Customer;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class BootContractorApp extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ca:boot';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Restore the Contractor.io app to fresh state. Will remove all data!';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // 1. Truncate all tables
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        $this->output->writeln("Truncating tables...");
        DB::table('ca_customers')->truncate();
        DB::table('ca_contractors')->truncate();
        DB::table('ca_skills')->truncate();
        DB::table('ca_locations')->truncate();
        DB::table('ca_contractors_skills')->truncate();
        DB::table('ca_jobs')->truncate();
        DB::table('ca_jobs_skills')->truncate();
        DB::table('ca_contracts')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 2. Seed with new data, static skills
        $this->output->writeln('Seeding database...');
        Artisan::call('db:seed');

        // 3. Queue indexing jobs
    }
}
