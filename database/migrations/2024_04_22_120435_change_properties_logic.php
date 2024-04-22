<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {

        $templates = DB::table('book_cover_templates')->get();
        foreach ($templates as $template) {
            $replacedFrontText = $this->replaceAuthorProperties($template->front);
            $replacedFrontText = $this->replaceTitleProperties($replacedFrontText);
            $replacedFrontText = $this->replaceSubtitleProperties($replacedFrontText);
            $replacedBackText = $this->replaceDescriptionProperties($template->back);

            DB::table('book_cover_templates')
                ->where('id', $template->id)
                ->update([
                    'front' => $replacedFrontText,
                    'back' => $replacedBackText]);

        }
    }

    public function replaceAuthorProperties($content): string
    {
        $replacementCallback = function ($matches) use (&$originalAuthor) {
            $originalAuthor = $matches[2];

            $textContent = $matches[3];

            $replacement = '<foreignObject
      x="98" y="85"
      font-size="50pt"
      text-anchor="middle"
      font-family="Merriweather"
      text-shadow="#FC0 1px 0 10px"
      data-shared="true"
      data-author-size="font-size"
      data-author-font="font-family"
      data-max="50"
      data-author-color="color"
      data-draggable="true"
      letter-spacing="0.1em"
      color="grey"
      style="text-transform: uppercase; filter: drop-shadow(3px 3px 1px rgba(0,0,0,0.5)); overflow: visible; line-height: 1"
      width="calc(100% - 98px)" height="1">
        <p xmlns="http://www.w3.org/1999/xhtml"
          data-author="innerText"
          style="word-wrap: break-word">
            '.$textContent.'
        </p>
    </foreignObject>';

            return $replacement;
        };

        $content = preg_replace_callback('/<text([^>]*?)data-author="([^"]+)"[^>]*>(.*?)<\/text>/is', $replacementCallback, $content);

        return str_replace('$originalAuthor', $originalAuthor, $content);
    }

    public function replaceTitleProperties($content): string
    {
        $replacementCallback = function ($matches) use (&$originalTitle) {
            $originalTitle = $matches[2];

            $textContent = $matches[3];

            $replacement = '<foreignObject
      x="98" y="574"
      font-size="20pt"
      text-anchor="middle"
       data-shared="true"
      data-max="50"
      data-title-size="font-size"
      data-title-color="color"
      data-title-font="font-family"
      data-draggable="true"
      text-shadow="#FC0 1px 0 10px"
      font-family="Merriweather"
      font-weight="normal"
      letter-spacing="0.1em"
      color="white"
      style="text-transform:uppercase; filter:drop-shadow(3px 3px 1px rgba(0,0,0,0.5)); overflow: visible; line-height: 1"
      width="calc(100% - 98px)" height="1">
        <p xmlns="http://www.w3.org/1999/xhtml"
          data-title="innerHTML"
          style="word-wrap: break-word">
            '.$textContent.'
        </p>
    </foreignObject>';

            return $replacement;
        };

        $content = preg_replace_callback('/<text([^>]*?)data-title="([^"]+)"[^>]*>(.*?)<\/text>/is', $replacementCallback, $content);

        return str_replace('$originalTitle', $originalTitle, $content);
    }

    public function replaceSubtitleProperties($content): string
    {
        $replacementCallback = function ($matches) use (&$originalSubtitle) {
            $originalSubtitle = $matches[2];

            $textContent = $matches[3];

            $replacement = '<foreignObject
      x="25" y="240"
      font-size="36.14pt"
      text-anchor="middle"
      font-family="Noto Sans Devanagari"
      text-shadow="#FC0 1px 0 10px"
      data-shared="true"
      data-subtitle-size="font-size"
      data-subtitle-font="font-family"
      data-max="50"
      data-subtitle-color="color"
      data-draggable="true"
      letter-spacing="0.1em"
      color="white"
      style="text-transform:uppercase; filter:drop-shadow(3px 3px 1px rgba(0,0,0,0.5)); overflow: visible; line-height: 1"
      width="calc(100% - 25px)" height="1">
        <p xmlns="http://www.w3.org/1999/xhtml"
          data-subtitle="innerHTML"
          style="word-wrap: break-word">
            '.$textContent.'
        </p>
    </foreignObject>';

            return $replacement;
        };

        $content = preg_replace_callback('/<text([^>]*?)data-subtitle="([^"]+)"[^>]*>(.*?)<\/text>/is', $replacementCallback, $content);

        return str_replace('$originalSubtitle', $originalSubtitle, $content);
    }

    public function replaceDescriptionProperties($content): string
    {
        $replacementCallback = function ($matches) use (&$originalSubtitle) {
            $originalSubtitle = $matches[2];

            $textContent = $matches[3];

            $replacement = '<foreignObject
      x="48" y="96"
    text-shadow="#FC0 1px 0 10px"
    font-size="20pt"
    text-anchor="middle"
    data-shared="true"
    data-max="50"
    data-description-text-size="font-size"
    data-description-color="color"
    data-description-font="font-family"
    font-family="Merriweather"
    font-weight="normal"
    letter-spacing="0.1em"
    color="white"
    data-draggable="true"
    data-max-width="492"
    data-text-justify="true"
    style="text-transform:uppercase; filter:drop-shadow(3px 3px 1px rgba(0,0,0,0.5)); overflow: visible; line-height: 1;"
    width="calc(100% - 48px)" height="1">
        <p xmlns="http://www.w3.org/1999/xhtml"
          data-description="innerHTML"
          data-description-text-background="text-background"
          style="word-wrap: break-word; background: black; max-width: fit-content; width: 98%">
            '.$textContent.'
        </p>
    </foreignObject>';

            return $replacement;
        };

        $content = preg_replace('/<defs[^>]*>.+?<\/defs>/is', '', $content);
        $content = preg_replace_callback('/<text([^>]*?)data-description="([^"]+)"[^>]*>(.*?)<\/text>/is', $replacementCallback, $content);

        return str_replace('$originalSubtitle', $originalSubtitle, $content);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
    }
};
