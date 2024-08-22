<html>

<head>
    <title>{{ $story->title }}</title>
    <style>
        * {
            font-family: '{{ $fontName }}', sans-serif;
        }

        body {
            font-family: '{{ $fontName }}', sans-serif;
        }

        @page {
            sheet-size: 6.39in 9.46in;
            margin: 0.8in;
            margin-bottom: 1in;
            margin-footer: 0.75in;
            header: page-header;
            footer: page-footer;
            font-family: '{{ $fontName }}', sans-serif;
        }

        .mpdf_toc {
            font-family: '{{ $fontName }}', sans-serif;
            font-size: 0.8rem;
        }

        span.mpdf_toc_t_level_0 {
            font-family: '{{ $fontName }}', sans-serif;
            font-weight: normal;
        }

        .mpdf_toc a {
            font-family: '{{ $fontName }}', sans-serif;
        }

        section {
            font-size: 0.8rem;
        }

        p {
            text-align: justify;
        }

        .first-letter {
            font-weight: bold;
            font-size: 1.5rem;
            color: #0C345C;
        }

        h1.title {
            text-align: center;
            font-size: 1.8rem;
            font-weight: normal;
            color: #0C345C;
            text-wrap: balance;
        }

        .guest-name {
            color: rgba(0, 0, 0, 0.7);
            font-size: 0.9rem;
            text-align: left;
        }

        .avatar {
            width: 60px;
            height: 60px;
            overflow: hidden;
        }

        table.guest-info {
            width: 100%;
            border-collapse: collapse;
        }

        td.avatar-cell {
            width: 60px;
            vertical-align: middle;
            padding-left: 10px;
        }

        td.name-cell {
            text-align: left;
            vertical-align: middle;
        }
    </style>
</head>

<body>
    <htmlpagefooter name="page-footer">
        <div style="font-size:0.8rem;text-align:center">{PAGENO}</div>
    </htmlpagefooter>

    @php
        echo '<tocpagebreak links="on" toc-prehtml="' .
            htmlspecialchars('<h1 class="title">Table of Contents</h1>', ENT_QUOTES) .
            '" />';
    @endphp
    @if (isset($imageErrors))
        @foreach ($imageErrors as $url)
            <div>Chapter generation errors, image not found. <a href="{{ $url }}">Edit</a></div>
        @endforeach
    @endif

    @foreach ($chapters as $chapter)
        @if (!$loop->first)
            <pagebreak />
        @endif
        <tocentry content="{{ $chapter->title }}" />
        <bookmark content="{{ $chapter->title }}" />
        <article>
            <h1 class="title">{{ $chapter->title }}</h1>
            @if ($chapter->guest)
                <table class="guest-info">
                    <tr>
                        <td class="avatar-cell">
                            @if ($chapter->guest->processed_avatar)
                                <img class="avatar" src="{{ $chapter->guest->processed_avatar }}" />
                            @endif
                        </td>
                        <td class="name-cell">
                            <span class="guest-name">written by {{ $chapter->guest->name }}</span>
                        </td>
                    </tr>
                </table>
            @endif
            @php
                $isHTML = str_contains($chapter->content, '<p>') || str_contains($chapter->content, '<img>');

                if (!$isHTML) {
                    $content = collect(preg_split('/\n\n/', $chapter->content, -1, PREG_SPLIT_NO_EMPTY))->map(
                        fn(string $p) => preg_replace('/\s+/', ' ', $p),
                    );
                } else {
                    $chapter->content = preg_replace_callback(
                        '/<img[^>]+>/im',
                        function ($matches) use (&$imagesById) {
                            $imageAttr = $matches[0];

                            preg_match('@id="([^"]+)"@', $imageAttr, $match);
                            $id = array_pop($match);
                            preg_match('@style="([^"]+)"@', $imageAttr, $match);
                            $style = array_pop($match);

                            preg_match('/[^-]width[: ]+([0-9]+)/', $style, $width);
                            $width = $width[1] ?? null;

                            preg_match('/[^-]height[: ]+([0-9]+)/', $style, $height);
                            $height = $height[1] ?? null;

                            $newStyle = '';
                            if ($width) {
                                $newStyle .= "width:{$width}px;";
                            }
                            if ($height) {
                                $newStyle .= "height:{$height}px;";
                            }

                            if (strpos($style, 'margin-right: 0px; margin-left: auto;')) {
                                $newStyle .= 'margin-right: 0px; margin-left: auto;';
                            } elseif (strpos($style, 'margin-right: auto; margin-left: auto;')) {
                                $newStyle .= 'margin-right: auto; margin-left: auto;';
                            }

                            if (isset($imagesById[$id])) {
                                $html =
                                    '
                                    <table style="page-break-inside:avoid; border:0; text-align:center;max-height: 600px; ' .
                                    $newStyle .
                                    '">
                                        <tr>
                                            <td style="border:0;padding-top:10px;padding-left:10px;padding-right:10px;">
                                                <img src="' .
                                    $imagesById[$id]['url'] .
                                    '" style="' .
                                    $newStyle .
                                    '">
                                                <div style="font-size:0.8rem;font-style:italic">' .
                                    $imagesById[$id]['caption'] .
                                    '</div>
                                            </td>
                                        </tr>
                                    </table>
                                ';

                                return $html;
                            }

                            return '';
                        },

                        $chapter->content,
                    );

                    $chapter->content = str_replace(
                        ['<h1>'],
                        ['<h1 style="page-break-inside:avoid;">'],
                        $chapter->content,
                    );
                }
            @endphp
            <section>
                @if ($isHTML)
                    {!! $chapter->content !!}
                @else
                    @foreach ($content as $p)
                        @if ($loop->first)
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
