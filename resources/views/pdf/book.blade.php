<html>
<head>
    <title>{{ $story->title }}</title>
    <style>
        @page {
            size: 8.75in 11.25in;
            margin: 0.625in;
            header: page-header;
            footer: page-footer;
            margin-header: 5mm;
            margin-footer: 5mm;
        }

        span.mpdf_toc_t_level_0 {
            font-family: 'Baskerville';
            font-size: 1rem;
            font-weight: normal;
        }

        section {
            font-family: 'Baskerville';
            font-size: 1rem;
        }

        h1 {
            font-family: 'DejaVu Serif';
            font-size: 1.5rem;
            font-style: italic;
            font-weight: normal;
            color: #0C345C;
            text-align: center;
            text-wrap: balance;
        }

        p {
            text-align: justify;
        }

        .first-letter {
            font-weight: bold;
            font-size: 2rem;
            color: #0C345C;
        }
    </style>
</head>
<body>
    {{-- <htmlpageheader name="page-header">
        <div style="font-size:12pt;text-align:center">{{ $story->title }}</div>
    </htmlpageheader> --}}

    <htmlpagefooter name="page-footer">
        <div style="font-size:12pt;text-align:center">{PAGENO}</div>
    </htmlpagefooter>

    <tocpagebreak links="on" toc-prehtml="&lt;h1&gt;Table of Contents&lt;/h1&gt;"/>

    @foreach ($chapters as $chapter)
    @if(!$loop->first)
        <pagebreak/>
    @endif
    <article>
        <h1><tocentry content="{{ $chapter->title }}"/><bookmark content="{{ $chapter->title }}"/>{{ $chapter->title }}</h1>
        <div style="text-align:center;padding:1rem 0">
            <svg viewBox="0 0 375 25" xmlns="http://www.w3.org/2000/svg">
                <g stroke="#999" fill="#999">
                    <path d="m203.06 12.22c6.26-1.7 8.3-4.34 11.94-4.34 3.99 0 4.67 4.34 4.67 4.34h-16.61z"/>
                    <path d="m203.06 12.22c6.26 1.7 8.3 4.34 11.94 4.34 3.99 0 4.67-4.34 4.67-4.34h-16.61z"/>
                    <path d="m172.16 12.22c-6.26-1.7-8.3-4.34-11.94-4.34-3.99 0-4.67 4.34-4.67 4.34h16.61z"/>
                    <path d="m172.16 12.22c-6.26 1.7-8.3 4.34-11.94 4.34-3.99 0-4.67-4.34-4.67-4.34h16.61z"/>
                    <path d="m358.61 12.22c6.26-1.7 8.3-4.34 11.94-4.34 3.99 0 4.67 4.34 4.67 4.34h-16.61z"/>
                    <path d="m358.61 12.22c6.26 1.7 8.3 4.34 11.94 4.34 3.99 0 4.67-4.34 4.67-4.34h-16.61z"/>
                    <path d="m175.39 12.22c6.75 0 12.22-5.47 12.22-12.22 0 6.75 5.47 12.22 12.22 12.22-6.75 0-12.22 5.47-12.22 12.22 0-6.75-5.47-12.22-12.22-12.22z"/>
                    <path d="m16.61 12.22c-6.26-1.7-8.3-4.34-11.94-4.34-3.99 0-4.67 4.34-4.67 4.34h16.61z"/>
                    <path d="m16.61 12.22c-6.26 1.7-8.3 4.34-11.94 4.34-3.99 0-4.67-4.34-4.67-4.34h16.61z"/>
                    <line x1="7.83" x2="155.55" y1="12.22" y2="12.22" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.54"/>
                    <line x1="219.67" x2="364.46" y1="12.22" y2="12.22" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.54"/>
                </g>
            </svg>
        </div>
        @php
            $content = collect(preg_split('/\n\n/', $chapter->content, -1, PREG_SPLIT_NO_EMPTY))
                ->map(fn (string $p) => preg_replace('/\s+/', ' ', $p));
        @endphp
        <section>
            @foreach ($content as $p)
            @if($loop->first)
                @php
                    $cap = substr($p, 0, 1);
                    $p = substr($p, 1);
                @endphp
                <p><span class="first-letter">{{ $cap }}</span>{{ $p }}</p>
            @else
                <p>{{ $p }}</p>
            @endif
            @endforeach
        </section>
    </article>
    @endforeach
</body>
</html>
