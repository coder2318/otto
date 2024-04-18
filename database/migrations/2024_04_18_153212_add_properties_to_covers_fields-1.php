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
        $fields = [
            'description' => [
                [
                    'key' => 'descriptionFont',
                    'name' => 'Description Text Font',
                    'type' => 'font',
                    'group' => 'Back',
                ],
                [
                    'name' => 'Description Text Size',
                    'key' => 'descriptionTextSize',
                    'type' => 'number',
                    'group' => 'Back',
                ],
                [
                    'name' => 'Description Text Background',
                    'key' => 'descriptionTextBackground',
                    'type' => 'color',
                    'group' => 'Back',
                ],
            ],
            'subtitle' => [
                [
                    'key' => 'subtitleFont',
                    'name' => 'Subtitle Font',
                    'type' => 'font',
                    'group' => 'Front',
                ],
            ],
            'spine' => [
                [
                    'key' => 'spineTextFont',
                    'name' => 'Spine Text Font',
                    'type' => 'font',
                    'group' => 'Spine',
                ],
                [
                    'name' => 'Spine Text Size',
                    'key' => 'spineTextSize',
                    'type' => 'number',
                    'group' => 'Spine',
                ],
                [
                    'key' => 'spineBackgroundColor',
                    'name' => 'Spine Background Color',
                    'type' => 'color',
                    'group' => 'Spine',
                ],
            ],
            'title' => [
                [
                    'key' => 'titleFont',
                    'name' => 'Title Font',
                    'type' => 'font',
                    'group' => 'Front',
                ],
            ],
            'author' => [
                [
                    'key' => 'authorFont',
                    'name' => 'Author Font',
                    'type' => 'font',
                    'group' => 'Front',
                    'after' => 'author',
                ],
            ],
        ];

        $templates = DB::table('book_cover_templates')->get();
        $this->updateTemplates($templates, $fields);
    }

    private function updateTemplates($templates, $fields): void
    {
        foreach ($templates as $template) {
            $decoded = json_decode($template->fields, true);
            $fields = $this->removeAddedFields($decoded, $fields);
            $decoded = $this->insertIntoFields($decoded, $fields);

            DB::table('book_cover_templates')
                ->where(['id' => $template->id])
                ->update(['fields' => $decoded]);
        }
    }

    private function insertIntoFields($decoded, $fields): array
    {
        for ($i = 0, $iMax = count($decoded); $i < $iMax; $i++) {
            if (array_key_exists($decoded[$i]['key'], $fields)) {
                array_splice($decoded, $i + 1, 0, $fields[$decoded[$i]['key']]);
                $iMax = count($decoded);
            }
        }

        return $decoded;
    }

    private function removeAddedFields($decoded, $fields): array
    {
        $decoded = array_column($decoded, 'key');
        $newFields = [];
        foreach ($fields as $key => $fieldsAfter) {
            foreach ($fieldsAfter as $fieldToInsert) {
                if (! in_array($fieldToInsert['key'], $decoded)) {
                    if (! array_key_exists($key, $newFields)) {
                        $newFields[$key] = [];
                    }
                    $newFields[$key][] = $fieldToInsert;
                }
            }
        }

        return $newFields;
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
    }
};
