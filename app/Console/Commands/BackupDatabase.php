<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Finder\Finder;

class BackupDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:backup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Backup database';

    public $prefix = 'mysql-backup-';
    public $daysToKeep = 3;

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $path = $this->getBackupPath();
        $fileName = $this->prefix . now()->format('Y-m-d');
        $filePath = "$path/$fileName.gz";

        $command = $this->mysqlDump($filePath);

        $output = null;
        $status = null;
        exec($command, $output, $status);
        if ($status == 0) {
            $this->clearOldBackUp();
        }
        $this->info("done backup mysql");
    }

    /**
     * @param string $path
     * @return string
     */
    protected function mysqlDump(string $path): string
    {
        $user = env('DB_USERNAME');
        $password = env('DB_PASSWORD');
        $host = env('DB_HOST');
        $database = env('DB_DATABASE');
        return "mysqldump --user=$user --password='$password' --host=$host $database | gzip > $path";
    }

    /**
     * @param $file
     * @return bool
     */
    protected function startsWithPrefix($file): bool
    {
        return str_starts_with($file->getFilenameWithoutExtension(), $this->prefix);
    }

    /**
     * @param $file
     * @return bool
     */
    protected function olderThan($file): bool
    {
        $date = str_replace($this->prefix, '', $file->getFilenameWithoutExtension());
        return now()->diff($date)->days >= $this->daysToKeep;
    }

    /**
     * clear old database backup gz
     */
    protected function clearOldBackUp(): void
    {
        $files = Finder::create()
            ->files()
            ->name("*.gz")
            ->in($this->getBackupPath())
            ->filter(fn($file) => $this->startsWithPrefix($file))
            ->filter(fn($file) => $this->olderThan($file));


        foreach ($files as $file) {
            unlink($file->getRealPath());
        }
    }

    /**
     * get the mysql backup path
     * @return string
     */
    protected function getBackupPath(): string
    {
        return getenv('HOME') . "/database-backup";
    }
}
