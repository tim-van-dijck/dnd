<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Symfony\Component\Console\Input\InputOption;

class CreateUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:user {--a|admin=0 : Make the new user an admin.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new user';

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
     * @return void
     */
    public function handle()
    {
        if (!in_array(env('APP_ENV'), ['development', 'local'])) {
            if (User::count() > 0) {
                $this->error('This command is not allowed on this environment');
            }
        }
        $name = $this->ask('Name:');
        $email = $this->ask('E-mail address:');
        $password = $this->secret('Password');
        $admin = $this->option('admin', false);

        $user = new User();
        $user->admin = 1;
        $user->name = $name;
        $user->email = $email;
        $user->password = bcrypt($password);
        $user->email_verified_at = Carbon::now();
        $user->save();
    }
}
