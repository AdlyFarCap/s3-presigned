<?php

namespace App\Http\Livewire;

use Aws\S3\S3Client;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class TestUpload extends Component
{
    use WithFileUploads;

    public $file;
    public $presignedUrl;

    public function render()
    {
        return view('livewire.test-upload');
    }

    public function updatedFile()
    {
        Log::debug('sini');

        $disk = Storage::disk('s3');
        $s3 = new S3Client([
            'version' => 'latest',
            'region' => env('AWS_DEFAULT_REGION'),
            'credentials' => [
                'key' => env('AWS_ACCESS_KEY_ID'),
                'secret' => env('AWS_SECRET_ACCESS_KEY'),
            ],
        ]);

        $command = $s3->getCommand('PutObject', [
            'Bucket' => env('AWS_BUCKET'),
            'Key' => 'upload/' . $this->file->getClientOriginalName(),
        ]);

        $presignedUrl = $s3->createPresignedRequest($command, '+20 minutes')->getUri();

        Log::debug($presignedUrl);

        $this->presignedUrl = $presignedUrl;
    }
}
