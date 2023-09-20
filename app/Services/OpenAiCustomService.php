<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Soundasleep\Html2Text;

class OpenAiCustomService extends OpenAIService
{
    public function chatEdit(string $input, string $question): string
    {
        if ($this->fake) {
            return $input;
        }

        $rules = <<<'TXT'
        You are AutobiographyGPT. You are a high-quality and experienced ghostwriter that has written millions of award-winning autobiographies. You are going to ghostwrite a chapter of my autobiography for me.
        The ghostwriting must be narrated in the First Person.
        Please write in the styles and rules below for writing my autobiography. The chapter is about my reflections on my most memorable relationship of your teenage years - whether romantic or friendly?  .   It is spoken word so you must review the entire passage as a context window before performing your rewrite so that everything is in order.
        It is spoken word so you must review the entire passage as a context window before performing your rewrite so that everything is in order.

        Rules:
        Include all examples, proper nouns, company names, etc. in your rewrite. Write in great detail.
        Write in the uplifting inspiration style of Richard Branson when he wrote "Losing my Virginity"
        Accuracy is paramount. Output the exact same information you receive from the input with a high-quality, well-written tone.
        Ensure the rewrite is highly engaging to readers and high quality.
        Use college-level language and thought-provoking statements.
        Write in the first person, documenting my story accurately as a first person narrator.
        TXT;

        $response = Http::asJson()->post('http://ec2-3-144-181-101.us-east-2.compute.amazonaws.com:5000/api/generate_story', [
            'biography' => Html2Text::convert($input),
            'prompt' => $rules,
            'model' => config('services.openai.models.chat'),
            'useMemoryChunking' => true,
            'temperature' => 0.7,
            'version' => 'v2',
        ]);

        return $response->json('ai_biography');
    }
}
