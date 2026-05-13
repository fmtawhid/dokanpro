<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use ZipArchive;
use Artisan;
use Exception;
use Illuminate\Support\Facades\Log;

class LandingInstallController extends Controller
{


    private $filePath = [];
    private $ignoreFiles = ['.', '..', '.git', '.env', 'vendor']; // Example of files/folders to ignore

    /**
     * name of dir which file uplod and divide
     * @param array $mainDir
     */
    private $mainDir = 'landing_files';

        public function index()
        {
            return view('landing.index');
        }

    private function dividePath(): array
    {
        $data = [];
        $mainDir = rtrim(base_path($this->mainDir), '/');

        foreach ($this->filePath as $path) {
            $divide = explode($mainDir, $path);

            if (count($divide) > 1) {
                $data[] = [
                    'dir' => $divide[1],
                    'mainDir' => base_path($divide[1]),
                    'updateDir' => $path,
                ];
            } else {
                Log::warning('Failed to divide path: ', ['path' => $path]);
            }
        }

        return $data;
    }

    public function store(Request $request)
    {
        $request->validate([
            'landing_zip_file' => 'required|mimes:zip'
        ]);

        $this->unzipAndStore($request->landing_zip_file);

        $filePaths = $this->getFilePath($request->landing_zip_file);

        foreach ($filePaths as $filePath) {
            try {

                if (file_exists($filePath['mainDir'])) {
                    if (strpos($filePath['mainDir'], 'routes/web.php') !== false) {
                        $mainContent = file_get_contents($filePath['mainDir']);
                        $updateContent = file_get_contents($filePath['updateDir']);
                        if ($mainContent !== $updateContent) {
                            file_put_contents($filePath['mainDir'], $updateContent);
                            dd('Updated the contents of routes/web.php.');
                        } else {
                            dd('No changes detected for routes/web.php.');
                        }
                        continue;
                    }

                    $mainDir = file($filePath['mainDir']);
                    $updateDir = file($filePath['updateDir']);

                    $numLines = max(count($mainDir), count($updateDir));

                    for ($i = 0; $i < $numLines; $i++) {
                        $line1 = isset($mainDir[$i]) ? rtrim($mainDir[$i]) : null;
                        $line2 = isset($updateDir[$i]) ? rtrim($updateDir[$i]) : null;

                        if ($line1 !== $line2) {
                            file_put_contents($filePath['mainDir'], implode('', $updateDir));
                            break;
                        }
                    }
                } else {
                    $this->createDirectories($filePath['dir']);
                    copy($filePath['updateDir'], $filePath['mainDir']);
                }
            } catch (Exception $e) {
                Log::error("Error processing file: " . $e->getMessage());
            }
        }

        shell_exec('rm -r ' . storage_path('app/public/landing_page'));

        Artisan::call('optimize:clear');
        return back()->withSuccess(__('File Updated Successfully'));
    }



    public function unzipAndStore($landing_zip_file): void
    {
        $tempFile = $landing_zip_file;
        $rootDir = base_path();

        $zip = new ZipArchive;

        if ($zip->open($tempFile) === TRUE) {
            for ($i = 0; $i < $zip->numFiles; $i++) {
                $fileName = $zip->getNameIndex($i);
                $filePath = $rootDir . '/' . $fileName;

                Log::info('File path: ' . $filePath);

                if (strpos($fileName, 'routes/') !== false) {
                    $directoryPath = dirname($filePath);

                    if (substr($fileName, -1) === '/') {
                        if (!is_dir($directoryPath)) {
                            Log::info('Directory does not exist. Creating directory: ' . $directoryPath);
                            mkdir($directoryPath, 0777, true);
                        }
                    } else {

                        if (!is_dir($directoryPath)) {
                            mkdir($directoryPath, 0777, true);
                        }

                        if (file_exists($filePath)) {
                            $existingContent = file_get_contents($filePath);
                            $lines = explode("\n", $existingContent);
                            $pattern = "/Route::get\(['\"]\/['\"],\s*.+?\);/";
                            $replacement = "Route::get('/shop-dashboard', [DashboardController::class, 'index'])->name('root');";
                            if (preg_match($pattern, $existingContent)) {
                                $updatedContent = preg_replace($pattern, $replacement, $existingContent);
                                $combinedContents = file_put_contents($filePath, $updatedContent);

                            }

                            $newContent = file_get_contents("zip://" . $tempFile . "#" . $fileName);
                            $combinedContentss =( $updatedContent ?? $existingContent) . PHP_EOL . $newContent;
                            $filePaths = str_replace("\\", "/", $filePath);
                            file_put_contents($filePaths, $combinedContentss);

                        } else {
                            if (copy("zip://" . $tempFile . "#" . $fileName, $filePath)) {
                                Log::info('web.php copied successfully.');
                            } else {
                                Log::error('Failed to copy web.php to ' . $filePath);
                            }
                        }
                    }
                } else {
                    if (substr($fileName, -1) === '/') {
                        if (!is_dir($filePath)) {
                            mkdir($filePath, 0777, true);
                        }
                    } else {
                        if (!copy("zip://" . $tempFile . "#" . $fileName, $filePath)) {
                            Log::error('Failed to copy file to ' . $filePath);
                        }
                    }
                }
            }
            $zip->close();
        } else {
            Log::error('Failed to unzip file: ' . $tempFile);
        }




    }

    public function getFilePath($filePath): array
    {
        $destination = base_path();
        $directory = scandir($destination);

        $existsDir = array_diff($directory, $this->ignoreFiles);
        foreach ($existsDir as $dirOrFile) {
            $dir = $destination . '/' . $dirOrFile;
            if (is_dir($dir)) {
                $this->scanDirectory($dir);
            } else {
                if (!in_array($dirOrFile, ['AppServiceProvider.php', 'RouteServiceProvider.php', 'BroadcastServiceProvider.php'])) {
                    $this->filePath[] = $dir;
                }
            }
        }

        return $this->dividePath();
    }

    public function scanDirectory($dir): void
    {
        $directory = scandir($dir);
        $existsDir = array_diff($directory, $this->ignoreFiles);

        foreach ($existsDir as $dirOrFile) {
            $path = $dir . '/' . $dirOrFile;
            if (is_dir($path)) {
                $this->scanDirectory($path);
            } else {
                if (!in_array($dirOrFile, ['AppServiceProvider.php', 'RouteServiceProvider.php'])) {
                    $this->filePath[] = $path;
                }
            }
        }
    }

    private function createDirectories($dir): void
    {
        $directories = explode('/', $dir);
        $currentDir = base_path();

        foreach ($directories as $key => $directory) {
            $currentDir .= '/' . $directory;
            if (!is_dir($currentDir)) {
                mkdir($currentDir, 0777, true);
            }
        }
    }

    private function runUpdateCommands(): void
    {
        $commands = config('installer.update_commands');
        $changeToBasePath = 'cd ' . base_path();

        foreach ($commands as $command) {
            shell_exec($changeToBasePath . ' && ' . $command);
        }
    }




}
