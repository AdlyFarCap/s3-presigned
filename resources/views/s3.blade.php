<!-- resources/views/s3.blade.php -->

@extends('layouts.alpine')

@section('content')
    <div>
        <h2>Upload file:</h2>

        <form>
            <div x-data="{ file: null }">
                <input id="file-input" type="file" name="file_name" required />
            </div>
            <button id="upload-button" type="submit">Submit</button>
        </form>
    </div>

    <ul>
        @foreach ($lists as $item)
            <li>
                <button onclick="downloadFile('{{ $item }}')">{{ $item }}</button>
            </li>
        @endforeach
    </ul>

    <script>
        const fileInput = document.getElementById('file-input');
        const uploadButton = document.getElementById('upload-button');

        uploadButton.addEventListener('click', event => {
            uploadButton.disabled = true;

            event.preventDefault();

            const files = fileInput.files[0];

            const formData = new FormData();
            formData.append('file_name', files.name);
            fetch('{{ route('api.presigned.url') }}', {
                    method: 'POST',
                    body: formData
                })
                .then(response => {
                    // Get the response body as JSON
                    return response.json();
                })
                .then(data => {
                    // Store the API response in a component property
                    const formData1 = new FormData();
                    formData1.append('file', files);

                    fetch(data, {
                        method: 'PUT', // Change method to PUT
                        body: formData1
                    }).then(response => {
                        // handle response
                        fileInput.value = '';
                        uploadButton.disabled = false;
                    }).catch(error => {
                        // handle error
                        uploadButton.disabled = false;
                    });
                })
                .catch(error => {
                    // Handle any errors that occur during the API call
                    console.error('API error:', error);
                    uploadButton.disabled = false;
                });
        });
    </script>

    <script>
        function downloadFile(filename) {
            console.log(filename);

            const formData = new FormData();
            formData.append('filepath', filename);
            fetch('{{ route('api.presigned.download') }}', {
                    method: 'POST',
                    body: formData
                })
                .then(response => {
                    // Get the response body as JSON
                    return response.json();
                })
                .then(data => {
                    console.log(data);
                    window.open(data, '_blank');
                })
                .catch(error => {
                    // Handle any errors that occur during the API call
                    console.error('API error:', error);
                    uploadButton.disabled = false;
                });
        }
    </script>
@endsection
