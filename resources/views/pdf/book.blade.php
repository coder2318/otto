<html>
<head>
    <title>{{ $story->title }}</title>
    <style>
        @page {
            size: 6.25in 9.25in;
            margin: 0.625in;
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

        p {
            text-indent: 1.5rem;
            text-align: justify;
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

    @foreach ($chapters as $chapter)
        <h1 @if (!$loop->first) style="page-break-before:always" @endif>{{ $chapter->title }}</h1>
        @php
            $content = collect(preg_split('/\n\n/', $chapter->content, -1, PREG_SPLIT_NO_EMPTY))
                ->map(fn (string $p) => preg_replace('/\s+/', ' ', $p));
        @endphp
        <section>
            @foreach ($content as $p)
                <p>{{ $p }}</p>
            @endforeach
        </section>
    @endforeach
</body>
</html>
