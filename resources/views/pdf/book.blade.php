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
        .title {
            text-align: center;
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
        <h1 class="title">{{ $chapter->title }}</h1>
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
        @php
            $isHTML = str_contains($chapter->content, '<p>') || str_contains($chapter->content, '<img>');

            if (!$isHTML) {
                $content = collect(preg_split('/\n\n/', $chapter->content, -1, PREG_SPLIT_NO_EMPTY))
                    ->map(fn (string $p) => preg_replace('/\s+/', ' ', $p));
            } else {
                $imagesById = [];
                foreach($chapter->images as $image) {
                    $imagesById[ $image->id ] = [
                        'id' => $image->id,
                        'url' => $image->getTemporaryUrl(now()->addHour(), 'optimized'),
                        'caption' => $image->getCustomProperty('caption'),
                    ];
                }

                $chapter->content = preg_replace_callback('/<img[^>]+>/im', function ($matches) use (&$imagesById) {
                    $imageAttr = $matches[0];

                    preg_match('@id="([^"]+)"@', $imageAttr, $match);
                    $id = array_pop($match);
                    preg_match( '@style="([^"]+)"@' , $imageAttr, $match);
                    $style = array_pop($match);

                    preg_match('/[^-]width[: ]+([0-9]+)/', $style, $width);
                    $width = $width[1] ?? null;

                    preg_match('/[^-]height[: ]+([0-9]+)/', $style, $height);
                    $height = $height[1] ?? null;

                    $newStyle = '';
                    if($width) {
                        $newStyle .= "width:{$width}px;";
                    }
                    if($height) {
                        $newStyle .= "height:{$height}px;";
                    }

                    if(strpos($style, 'margin-right: 0px; margin-left: auto;')) {
                        $newStyle .= "margin-right: 0px; margin-left: auto;";
                    }
                    elseif(strpos($style, 'margin-right: auto; margin-left: auto;')) {
                        $newStyle .= "margin-right: auto; margin-left: auto;";
                    }

                    if (isset($imagesById[$id])) {
                        $imageUrl = $imagesById[$id]['url'];
                        $html = '
                        <table style="page-break-inside:avoid; border:0; text-align:center;max-height: 600px;'.$newStyle.'">
                            <tr>
                                <td style="border:0;padding-top:10px;padding-left:10px;padding-right:10px;">
                                    <img src="'.$imageUrl.'" style="'.$newStyle.'">
                                    <div style="font-size:0.8rem;font-style:italic">'.$imagesById[$id]['caption'].'</div>
                                </td>
                            </tr>
                        </table>
                        ';

                        return $html;
                    }

                    return '';
                }, $chapter->content);
            }
        @endphp
        <section>
            @if($isHTML)
                {!! $chapter->content !!}
            @else
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
            @endif
        </section>
    </article>
    @endforeach
</body>
</html>
