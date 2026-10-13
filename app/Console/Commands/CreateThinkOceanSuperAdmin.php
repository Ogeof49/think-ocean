<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class CreateThinkOceanSuperAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'think-ocean:create-super-admin
                            {email=ogeof49@gmail.com : Super admin email address}
                            {--password= : Optional temporary password}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create or promote the Think Ocean CMS super administrator';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = strtolower((string) $this->argument('email'));
        $temporaryPassword = (string) ($this->option('password') ?: Str::password(20));
        $user = User::query()->firstOrNew(['email' => $email]);
        $wasCreated = ! $user->exists;

        $user->name = $user->name ?: 'Think Ocean Super Admin';
        $user->is_super_admin = true;

        if ($wasCreated || $this->option('password')) {
            $user->password = $temporaryPassword;
        }

        $user->save();

        $this->info("Think Ocean super admin ready: {$email}");

        if ($wasCreated) {
            $this->warn("Temporary password: {$temporaryPassword}");
            $this->warn('Change this password after the first sign-in.');
        }

        return self::SUCCESS;
    }
}
