<html>
<head>
    <style>
        @page {
            margin: 0.5in;
            sheet-size: {{ $width }} {{ $height }};
        }
    </style>
</head>
<body>
    <img src="{{ $cover->getUrl() }}" width="100%" height="100%" />
</body>
</html>
