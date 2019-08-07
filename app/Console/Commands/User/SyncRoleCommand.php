<?php

namespace App\Console\Commands\User;

use App\User;
use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;

class SyncRoleCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:syncRole';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync role from existing users';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // create all roles
        foreach (config('permission.role.roles') as $roleName) {
            Role::firstOrCreate(['name' => $roleName]);
        }

        // assign user to role
        $users = User::whereDoesntHave('roles')
            ->orderBy('id')
            ->get();

        $bar = $this->output->createProgressBar();

        $role = Role::firstOrCreate(['name' => config('permission.role.default')]);

        $bar->start($users->count());
        foreach ($users as $user) {
            $user->syncRoles([$role]);

            $bar->advance();
        }
        $bar->finish();
    }
}
