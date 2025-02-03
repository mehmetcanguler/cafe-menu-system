<?php

namespace App\Console\Commands;

use App\Models\Category;
use App\Models\User;
use Hash;
use Illuminate\Console\Command;

class TestCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:test-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Category::create([
            'user_id' => 2,
            'name' => 'test',
        ]);
        Category::create([
            'user_id' => 1,
            'name' => 'ara12412512512',
        ]);
        Category::create([
            'user_id' => 2,
            'name' => 'aramam',
        ]);
    }
}
