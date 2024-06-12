<?php

namespace Database\Seeders;

use App\Models\Chapter;
use Illuminate\Database\Seeder;

class AddOldImageToChapterContent extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $chapters = Chapter::all();
        $chapters->each(function ($chapter) {
            $images = $chapter->images()->getResults();
            if (count($images)) {
                foreach ($images as $image) {
                    $chapter->content = $chapter->content.'<br><img src="'.$image->getFullUrl().'" title="'.$image->getCustomProperty('caption').'" style="width: 200px; height: auto;" id="'.$image->id.'" draggable="true">';
                }
                $chapter->save();
            }
        });
    }
}
