<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class ManualInstallController extends Controller
{
    public function seederRun()
    {
        try {
            Artisan::call('migrate:fresh --seed --force');
            Artisan::call('storage:link');
            return back()->with('success', 'Successfully restored is necessary data.');
        } catch (\Throwable $th) {
            return back()->with('error', 'Seeder not runed beacause ' . $th->getMessage());
        }
    }
    public function storageInstall()
    {
        try {
            Artisan::call('storage:link');
            return back()->with('success', 'Storage linked is successfully.');
        } catch (\Throwable $th) {
            return back()->with('error', 'Storage not linked beacause ' . $th->getMessage());
        }
    }
}
