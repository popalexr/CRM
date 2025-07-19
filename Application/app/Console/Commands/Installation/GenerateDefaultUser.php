<?php

namespace App\Console\Commands\Installation;

use App\Models\User;
use Illuminate\Console\Command;

class GenerateDefaultUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:default-user';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a default user for the application. This command is intended to be run during the installation process.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Generating default user...');

        User::create([
            'name' => 'Admin User',
            'email' => 'admin@admin.com',
            'password' => bcrypt('admin'),
            'is_admin' => true,
        ]);

        $this->info('Default user created successfully.');
        $this->info('You can now log in with the following credentials:');
        $this->line('Email: admin@admin.com');
        $this->line('Password: admin');
        
        $this->info('Please remember to change the password after logging in for the first time.');
    }
}
