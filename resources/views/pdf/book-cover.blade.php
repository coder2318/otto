<html>
<head>
    <style>
        @page {
            margin: 0.75in;
            sheet-size: {{ $width }} {{ $height }};
        }
    </style>
</head>
<body>
    <img src="{{ $cover->getTemporaryUrl(now()->addMinutes(5) }}" width="100%" height="100%" />
</body>
</html>
