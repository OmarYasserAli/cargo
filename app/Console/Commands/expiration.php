<?php

namespace App\Console\Commands;

use App\Models\Doctor_forget_email;
use Carbon\Carbon;
use Illuminate\Console\Command;

class expiration extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:expire';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'expire for forget password every time';

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
     * @return int
     */
    public function handle()
    {
        $users = Doctor_forget_email::get();
        foreach($users as $user)
        {
         if($user->created_at->diffInMinutes(Carbon::now())>='30')
         {
             $user->delete();
         }  
        }
    }
}
