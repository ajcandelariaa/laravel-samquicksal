<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>PDF Viewer</title>
    <style>
        *{
            padding: 0;
            margin: 0;
            overflow: hidden;
        }
    </style>
</head>
<body>
    <iframe src="{{ asset('storage/uploads/restaurantApplicants/'.$id.'/'.$filename) }}" frameborder="0" style="width:100%;height:100vh;"></iframe>
</body>
</html>