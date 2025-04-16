<?php

namespace App\Console\Commands;

use App\Models\Contractor;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Elasticsearch;

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
    protected $description = 'Restore the Contractor.io app to fresh state. Will remove all data from DB and index!';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->elasticsearch();     // 1. Setup elasticsearch, apply mappings
        $this->truncate();          // 2. Truncate all the tables in the DB
        $this->seed();              // 3. Create new seed data
        $this->index();             // 4. Index entities
    }

    private function index()
    {
        Contractor::all()->each(fn($c) => $c->commitToIndex());
    }

    /**
     * Call the primary seeder
     *
     * @return void
     */
    private function seed()
    {
        $this->output->writeln('Seeding database...');
        Artisan::call('db:seed');
    }

    /**
     * Truncate all tables
     *
     * @return void
     */
    private function truncate()
    {
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
    }

    /**
     * Remove and re-create index with search mappings
     *
     * @return void
     */
    private function elasticsearch()
    {
        $this->output->writeln('Creating new index...');

        $res = Artisan::call('laravel-elasticsearch:utils:index-exists ' . env('ELASTICSEARCH_INDEX_NAME', 'contractorapp'));

        if (0 === $res && Artisan::output() !== "Index " . env('ELASTICSEARCH_INDEX_NAME', 'contractorapp') . " doesn't exists.\n") {
            Artisan::call('laravel-elasticsearch:utils:index-delete ' . env('ELASTICSEARCH_INDEX_NAME', 'contractorapp'));
        }

        if (0 !== Artisan::call('laravel-elasticsearch:utils:index-create ' . env('ELASTICSEARCH_INDEX_NAME', 'contractorapp'))) {
            $this->fail('Unable to create index..');
        }

        $searchMappingsFile = public_path('elasticsearch/search-mappings.json');

        try {
            Elasticsearch::indices()->putMapping([
                'index' => env('ELASTICSEARCH_INDEX_NAME', 'contractorapp'),
                'body'  => json_decode(
                    file_get_contents($searchMappingsFile),
                    true
                ),
            ]);
        } catch (\Throwable $t) {
            $this->fail('Unable to apply search mappings ... ' . $t->getMessage());
        }
    }
}
