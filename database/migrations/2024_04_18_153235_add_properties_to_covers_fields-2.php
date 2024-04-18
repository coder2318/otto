<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $spine = '<rect x="0" y="0" width="100%" height="100%" fill="black" data-spine-background-color="fill" />
                           <text
                               x="50%"
                               y="50%"
                               width="100%"
                               height="100%"
                               text-anchor="middle"
                               dominant-baseline="middle"
                               fill="white"
                               style="transform-origin:center;transform:rotate(90deg);"
                               font-size="calc(var(--spine-width) * 0.666)"
                               data-spine="innerText"
                               data-spine-text-color="fill"
                               data-spine-text-font="font-family"
                               data-spine-text-size="font-size"
                               font-family="Merriweather"
                               fill="white"
                           ></text>';

        $back = '  <image
                            href="/build/assets/transparent.png"
                            data-back="href"
                            preserveAspectRatio="xMinYMin slice"
                            x="0" y="0" width="100%" height="100%"
                          />
                          <defs>
                            <filter x="0" y="0" width="1" height="1" id="descriptionBackground">
                              <feFlood flood-color="black" result="bg" data-description-text-background="flood-color"/>
                              <feMerge>
                                <feMergeNode in="bg"/>
                                <feMergeNode in="SourceGraphic"/>
                              </feMerge>
                            </filter>
                          </defs>
                          <text
                            filter="url(#descriptionBackground)"
                            x="48" y="96"
                            font-size="1rem"
                            text-anchor="left"
                            text-shadow="#FC0 1px 0 10px"
                            data-description="innerText"
                            data-description-color="fill"
                            data-description-font="font-family"
                            data-description-text-size="font-size"
                            font-family="Merriweather"
                            data-draggable="true"
                            data-max-width="492"
                            data-text-justify="true"
                            letter-spacing="0.1em"
                            fill="white"
                          ></text>';


        $templates = DB::table('book_cover_templates')->get();
        foreach ($templates as $template) {
            DB::table('book_cover_templates')
                ->where(['id' => $template->id])
                ->update([
                    'spine' => DB::raw("'" . $spine . "'"),
                    'back' => DB::raw("'" . $back . "'")
                ]);

            if ($template->name == "Legend") {
                $this->updateFrontContent($template, 'data-author="innerText"', "\n  data-author-font=\"font-family\"");
                $this->updateFrontContent($template, 'data-title-color="fill"', "\n  data-title-font=\"font-family\"");
            }

            if ($template->name == "Open") {
                $this->updateFrontContent($template, 'data-title-color="fill"', "\n  data-title-font=\"font-family\"");
                $this->updateFrontContent($template, 'data-subtitle-size="font-size"', "\n  data-subtitle-font=\"font-family\"");
                $this->updateFrontContent($template, 'data-author-color="fill"', "\n  data-author-font=\"font-family\"");
            }

            if ($template->name == "Open") {
                $this->updateFrontContent($template, 'data-title-color="fill"', "\n  data-title-font=\"font-family\"");
                $this->updateFrontContent($template, 'data-subtitle-size="font-size"', "\n  data-subtitle-font=\"font-family\"");
                $this->updateFrontContent($template, 'data-author-color="fill"', "\n  data-author-font=\"font-family\"");
            }

            if ($template->name == "Woman") {
                $this->updateFrontContent($template, 'data-author="innerText"', "\n  data-author-font=\"font-family\"");
                $this->updateFrontContent($template, 'data-max="50"', "\n  data-title-font=\"font-family\"");
            }

            if ($template->name == "DavidWalls") {
                $this->updateFrontContent($template, 'data-author-size="font-size"', "\n  data-author-font=\"font-family\"");
                $this->updateFrontContent($template, 'data-title-color="fill"', "\n  data-title-font=\"font-family\"");
            }

            if ($template->name == "Harley") {
                $this->updateFrontContent($template, 'data-title-size="font-size"', "\n  data-title-font=\"font-family\"");
                $this->updateFrontContent($template, 'data-subtitle-color="fill"', "\n  data-subtitle-font=\"font-family\"");
                $this->updateFrontContent($template, 'data-author-size="font-size"', "\n  data-author-font=\"font-family\"");
            }

        }
    }

    private function updateFrontContent($template, $searchString, $newText): void
    {
        $frontContent = $template->front;
        $position = strpos($frontContent, $searchString);

        $newTextPosition = strpos($frontContent, $newText);

        if ($position !== false && $newTextPosition === false) {
            $position += strlen($searchString);

            $updatedFrontContent = substr_replace($frontContent, $newText, $position, 0);

            DB::table('book_cover_templates')
                ->where('id', $template->id)
                ->update(['front' => $updatedFrontContent]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prompts');
    }
};
