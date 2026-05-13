<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Console\Output\BufferedOutput;
use RachidLaasri\LaravelInstaller\Helpers\FinalInstallManager;

class InstallController extends Controller
{
    public function configSetup(Request $request, Redirector $redirect)
    {
        $rules = config('installer.environment.form.rules');
        $messages = [
            'environment_custom.required_if' => __('installer_messages.environment.wizard.form.name_required'),
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return $redirect->route('LaravelInstaller::environmentWizard')->withInput()->withErrors($validator->errors());
        }

        if (! $this->checkDatabaseConnection($request)) {
            return $redirect->route('LaravelInstaller::environmentWizard')->withInput()->withErrors([
                'database_connection' => __('installer_messages.environment.wizard.form.db_connection_failed'),
            ]);
        }

        $results = $this->saveFileWizard($request);

        return $redirect->route('LaravelInstaller::database')
                        ->with(['results' => $results]);
    }

    public function saveClassic(Request $input, Redirector $redirect)
    {
        $message = $this->saveFileClassic($input);

        return $redirect->route('LaravelInstaller::environmentClassic')
                        ->with(['message' => $message]);
    }

    public function database()
    {
        $previousAppServiceProvider = base_path('app/Providers/AppServiceProvider.php');
        $newRouteAppServiceProvider = base_path('app/Http/Core/AppServiceProvider.txt');
        copy($newRouteAppServiceProvider, $previousAppServiceProvider);

        $response = $this->migrate();
        $this->restoreData();

        return redirect()->route('LaravelInstaller::final')
                         ->with(['message' => $response]);
    }

    private function migrate()
    {
        $outputLog = new BufferedOutput;
        try {
             Artisan::call('migrate:fresh',['--force' => true], $outputLog);
        } catch (Exception $e) {
            return $this->response($e->getMessage(), 'error', $outputLog);
        }

        return $this->seed($outputLog);
    }

    private function seed(BufferedOutput $outputLog)
    {
        try {
            Artisan::call('db:seed --force');
            Artisan::call('storage:link');
        } catch (Exception $e) {
            return $this->response($e->getMessage(), 'error', $outputLog);
        }

        return $this->response(__('installer_messages.final.finished'), 'success', $outputLog);
    }

    private function restoreData()
    {
        $previousRouteServiceProvier = base_path('app/Providers/RouteServiceProvider.php');
        $newRouteServiceProvier = base_path('app/Http/Core/RouteServiceProvider.txt');
        copy($newRouteServiceProvier, $previousRouteServiceProvier);

        $previousLanguage = base_path('app/Http/Middleware/Language.php');
        $newLanguage = base_path('app/Http/Core/Language.txt');
        copy($newLanguage, $previousLanguage);

        $routeWeb = base_path('routes/web.php');
        $newRouteWeb = base_path('app/Http/Core/web.txt');
        copy($newRouteWeb, $routeWeb);

        $routeApi = base_path('routes/api.php');
        $newRouteApi = base_path('app/Http/Core/api.txt');
        copy($newRouteApi, $routeApi);
    }

    private function response($message, $status, BufferedOutput $outputLog)
    {
        return [
            'status' => $status,
            'message' => $message,
            'dbOutputLog' => $outputLog->fetch(),
        ];
    }

    private function checkDatabaseConnection(Request $request)
    {
        $connection = $request->input('database_connection');

        $settings = config("database.connections.$connection");

        config([
            'database' => [
                'default' => $connection,
                'connections' => [
                    $connection => array_merge($settings, [
                        'driver' => $connection,
                        'host' => $request->input('database_hostname'),
                        'port' => $request->input('database_port'),
                        'database' => $request->input('database_name'),
                        'username' => $request->input('database_username'),
                        'password' => $request->input('database_password'),
                    ]),
                ],
            ],
        ]);

        DB::purge();

        // try {
            DB::connection()->getPdo();

            return true;
        // } catch (Exception $e) {
        //     return false;
        // }
    }

    private function saveFileClassic(Request $input)
    {
        $message = __('installer_messages.environment.success');

        try {
            file_put_contents(base_path('.env'), $input->get('envConfig'));
        } catch (Exception $e) {
            $message = __('installer_messages.environment.errors');
        }

        return $message;
    }
    private function saveFileWizard(Request $request)
    {
        $results = __('installer_messages.environment.success');

        $envFileData =
            'APP_NAME=\''.$request->app_name."'\n".
            'APP_ENV=production'."\n".
            'APP_KEY='.'base64:'.base64_encode(str()->random(32))."\n".
            'APP_DEBUG='.$request->app_debug."\n".
            'APP_URL='.$request->app_url."\n\n".
            'APP_TIMEZONE=America/New_York'."\n".
            'DB_CONNECTION='.$request->database_connection."\n".
            'DB_HOST='.$request->database_hostname."\n".
            'DB_PORT='.$request->database_port."\n".
            'DB_DATABASE='.$request->database_name."\n".
            'DB_USERNAME='.$request->database_username."\n".
            'DB_PASSWORD='.$request->database_password."\n\n".
            'FILESYSTEM_DISK='.$request->filesystem_disk."\n\n".
            'BROADCAST_DRIVER=log'."\n".
            'CACHE_DRIVER=file'."\n".
            'SESSION_DRIVER=file'."\n".
            'QUEUE_DRIVER=sync'."\n".
            'MAIL_MAILER=smtp'."\n".
            'MAIL_HOST='.$request->mail_host."\n".
            'MAIL_PORT='.$request->mail_port."\n".
            'MAIL_USERNAME='.$request->mail_username."\n".
            'MAIL_PASSWORD='.$request->mail_password."\n".
            'MAIL_FROM_ADDRESS='.$request->mail_from_address."\n".
            'MAIL_FROM_NAME='.$request->mail_from_name."\n".
            'MAIL_ENCRYPTION='.$request->mail_encryption."\n\n";
        try{
            file_put_contents(base_path('.env'), $envFileData);
        } catch (Exception $e) {
            $results = __('installer_messages.environment.errors');
        }

        return $results;
    }
}
