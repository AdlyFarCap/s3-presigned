<?php

namespace App\Console\Commands;

use Zip;
use Aws\S3\S3Client;
use Illuminate\Console\Command;
use STS\ZipStream\Models\S3File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ZipFileCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:zip-file-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $s3keys = Storage::disk('s3')->allFiles('upload');

        $zip = Zip::create("package.zip");

        foreach ($s3keys as $file) {
            $zip->add("s3://" . env('AWS_BUCKET') . "/" . $file);
        }

        $zip->saveTo("s3://" . env('AWS_BUCKET') . "/");
    }
}
