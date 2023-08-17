<html>
<head>
    <title>{{ $story->title }}</title>
    <style>
        @page {
            size: a5 portrait;
        }

        section {
            font-family: 'DejaVu Sans';
            font-size: 0.75rem;
        }

        h1 {
            font-family: 'DejaVu Serif';
            font-size: 1.5rem;
            font-style: italic;
            color: #0C345C;
            text-align: center;
        }

        footer {
            position: fixed;
            left: 0;
            bottom: 0;
            right: 0;
            text-align: right;
        }

        footer::after {
            font-size: 0.75rem;
            content: counter(page);
        }
    </style>
</head>
<body>
    <footer></footer>

    @foreach ($story->chapters()->lazy() as $chapter)
        <h1 @if (!$loop->first) style="page-break-before:always" @endif>{{ $chapter->title }}</h1>
        <section>{!! $chapter->content !!}</section>
    @endforeach
</body>
</html>
