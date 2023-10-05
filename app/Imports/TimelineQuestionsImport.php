<?php

namespace App\Imports;

use App\Models\TimelineQuestion;
use Maatwebsite\Excel\Concerns\ToModel;
use TextAnalysis\Tokenizers\SentenceTokenizer;

class TimelineQuestionsImport implements ToModel
{
    public function model(array $row): ?TimelineQuestion
    {
        if (! isset($row[1])) {
            return null;
        }

        return new TimelineQuestion([
            'context' => $row[0],
            'question' => $row[1],
            'sub_questions' => ! isset($row[2]) ? null : array_map('trim', tokenize($row[2], SentenceTokenizer::class)),
            'timeline_id' => $row[3],
        ]);
    }
}
