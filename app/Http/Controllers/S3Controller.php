<?php

// app/Http/Controllers/S3Controller.php

namespace App\Http\Controllers;

use Aws\S3\S3Client;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Aws\Exception\AwsException;
use Aws\S3\Exception\S3Exception;
use Illuminate\Support\Facades\Storage;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\Response;

class S3Controller extends Controller
{
    public function index(Request $request)
    {
        $lists = Storage::disk('s3')->allFiles();

        return view('s3', [
            'lists' => $lists
        ]);
    }

    public function presignedUrl(Request $request)
    {
        // Create an S3Client instance
        $s3 = new S3Client([
            'region' => env('AWS_DEFAULT_REGION'),
            'version' => 'latest',
            'credentials' => [
                'key' => env('AWS_ACCESS_KEY_ID'),
                'secret' => env('AWS_SECRET_ACCESS_KEY'),
            ],
        ]);

        // Set the bucket name and file name
        $bucket = env('AWS_BUCKET');
        $fileName = $request->file_name;

        // Set the expiration time (in seconds)
        $expiration = 600;

        // Generate the presigned URL
        try {
            $cmd = $s3->getCommand('PutObject', [
                'Bucket' => $bucket,
                'Key' => $fileName,
            ]);
            $presignedUrl = $s3->createPresignedRequest($cmd, "+{$expiration} seconds")->getUri()->__toString();

            return response()->json($presignedUrl);
        } catch (S3Exception $e) {
            // Handle the error
            echo $e->getMessage();
        }
    }

    public function presignedDownload(Request $request)
    {
        $s3Client = new S3Client([
            'region' => env('AWS_DEFAULT_REGION'),
            'version' => 'latest',
            'credentials' => [
                'key' => env('AWS_ACCESS_KEY_ID'),
                'secret' => env('AWS_SECRET_ACCESS_KEY'),
            ],
        ]);

        $bucketName = env('AWS_BUCKET');
        $objectKey = $request->filepath;

        // $presignedUrl = $s3Client->getObjectUrl($bucketName, $objectKey, '+10 minutes');

        $cmd = $s3Client->getCommand('GetObject', [
            'Bucket' => $bucketName,
            'Key' => $objectKey,
        ]);

        $presignedUrl = $s3Client->createPresignedRequest($cmd, '+5 minutes')->getUri();

        return response()->json($presignedUrl);
    }
}
