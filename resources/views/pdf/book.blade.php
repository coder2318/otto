<html>
<head>
    <title>{{ $story->title }}</title>
    <style>
        @page {
            sheet-size: 6.39in 9.46in;
            margin: 0.8in;
            margin-bottom: 1in;
            margin-footer: 0.75in;
            header: page-header;
            footer: page-footer;
        }

        .mpdf_toc {
            font-size: 0.8rem;
        }

        span.mpdf_toc_t_level_0 {
            font-family: 'Baskerville';
            font-weight: normal;
        }

        section {
            font-family: 'Baskerville';
            font-size: 0.8rem;
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
            font-size: 1.5rem;
            color: #0C345C;
        }
    </style>
</head>
<body>
    {{-- <htmlpageheader name="page-header">
        <div style="font-size:12pt;text-align:center">{{ $story->title }}</div>
    </htmlpageheader> --}}

    <htmlpagefooter name="page-footer">
        <div style="font-size:0.8rem;text-align:center">{PAGENO}</div>
    </htmlpagefooter>

    <tocpagebreak links="on" toc-prehtml="&lt;h1&gt;Table of Contents&lt;/h1&gt;"/>

    @foreach ($chapters as $chapter)
    @if(!$loop->first)
        <pagebreak/>
    @endif
    <tocentry content="{{ $chapter->title }}"/><bookmark content="{{ $chapter->title }}"/>
    <article>
        <h1>{{ $chapter->title }}</h1>
        <div style="text-align:center;padding:1rem 0">
            <svg viewBox="0 0 375 25" xmlns="http://www.w3.org/2000/svg" width="100%">
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
        <section>
            @php
                $chapter->content = preg_replace_callback("/<img[^>]+>/im", function($matches) {
                    $imageTeg   = $matches[0];

                    preg_match( '@title="([^"]+)"@' , $imageTeg, $match);
                    $title = array_pop($match);

                    preg_match( '@style="([^"]+)"@' , $imageTeg, $match);
                    $style = array_pop($match);

                    return '<figure style="text-align:center;padding:1rem;border:1px solid #999;border-radius:0.5rem;'.$style.'">
                        '.$imageTeg.'
                        <figcaption style="font-size:0.8rem;font-style:italic">'.$title.'</figcaption>
                    </figure>
                    ';
                }, $chapter->content);
            @endphp
            {!! $chapter->content !!}
        </section>
    </article>
    @endforeach
</body>
</html>
