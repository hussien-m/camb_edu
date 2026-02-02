<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificate - {{ $certificate->certificate_number }}</title>
    <style>html, body { margin: 0; padding: 0; }</style>
</head>
<body>
    @include('student.certificates.partials.template', ['forPdf' => true])
</body>
</html>
