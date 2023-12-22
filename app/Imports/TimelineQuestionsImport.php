<?php

namespace App\Imports;

use App\Models\TimelineQuestion;
use Google\Service\Drive;
use Google\Service\Drive\DriveFile;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Row;
use Psr\Http\Message\ResponseInterface;
use TextAnalysis\Tokenizers\SentenceTokenizer;

// Excel::import(new \App\Imports\TimelineQuestionsImport, storage_path('app/questions.xlsx'))
/** @codeCoverageIgnore */
class TimelineQuestionsImport implements OnEachRow, SkipsEmptyRows
{
    protected Drive $drive;

    protected string $token;

    public function onRow(Row $row): ?TimelineQuestion
    {
        dump($row->getIndex());

        foreach ($row->getDelegate()->getColumnIterator('E', 'E') as $cell) {
            if ($cell->hasHyperlink()) {
                $url = $cell->getHyperlink()->getUrl();
                $id = explode('?', basename($url))[0];
                $cell->setValue($id);
            }
        }

        $row = $row->toArray(endColumn: 'E');

        if (! isset($row[1])) {
            return null;
        }

        /** @var TimelineQuestion */
        $question = TimelineQuestion::firstOrCreate([
            'question' => $row[1],
        ], [
            'context' => $row[0],
            'sub_questions' => ! isset($row[2]) ? null : array_map('trim', tokenize($row[2], SentenceTokenizer::class)),
            'timeline_id' => $row[3],
        ]);

        if (! isset($row[4]) || $question->hasMedia('cover')) {
            return $question;
        }

        dump($row[4]);

        dispatch(function () use ($question, $row) {
            $question->clearMediaCollection('cover');

            foreach ($this->getImages($row[4]) as $file) {
                /** @var ResponseInterface */
                $response = $this->getDrive()->files->get($file->id, ['alt' => 'media']);
                $question->addMediaFromStream($response->getBody())->usingFileName($file->name)->toMediaCollection('cover');
            }
        });

        return $question;
    }

    /**
     * Import images from google drive folder link
     *
     * @return Collection<int,DriveFile>
     */
    protected function getImages(string $floderId): ?Collection
    {
        try {
            $files = $this->getDrive()->files->listFiles([
                'q' => "'$floderId' in parents and trashed = false and mimeType != 'application/vnd.google-apps.folder'",
                'fields' => 'files(id,name)',
            ]);
        } catch (\Throwable) {
            return null;
        }

        return collect($files->getFiles());
    }

    protected function getDrive()
    {
        if (isset($this->drive)) {
            return $this->drive;
        }

        $tokenPath = storage_path('app/google_token.json');

        $client = new \Google\Client();
        $client->setAuthConfig(storage_path('app/google.json'));
        $client->setAccessType('offline');
        $client->setScopes(Drive::DRIVE_READONLY);

        if (file_exists($tokenPath)) {
            $accessToken = json_decode(file_get_contents($tokenPath), true);
            $this->token = $accessToken['access_token'];
            $client->setAccessToken($accessToken);
        }

        if ($client->isAccessTokenExpired()) {
            if ($client->getRefreshToken()) {
                $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
            } else {
                $auth = $client->createAuthUrl();
                printf("Open the following link in your browser:\n%s\n", $auth);
                echo 'Enter verification code: ';
                $code = trim(fgets(STDIN));
                $accessToken = $client->fetchAccessTokenWithAuthCode($code);

                if (! file_exists(dirname($tokenPath))) {
                    mkdir(dirname($tokenPath), 0700, true);
                }
                file_put_contents($tokenPath, json_encode($accessToken));
            }
        }

        return $this->drive = new Drive($client);
    }
}
