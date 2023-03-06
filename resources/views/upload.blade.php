<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Bypass synchronous invocations Lambda</title>
</head>

<body>
    <form action="{{ route('upload.file') }}" enctype="multipart/form-data">
        <input type="file" name="uploadFile" id="">
        <br>
        <button type="submit">Upload</button>
    </form>
</body>

</html>
