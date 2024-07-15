<?php

namespace App\Providers;

use App\Models\Setting;
use App\Services\AiService;
use App\Services\Claude3Service;
use App\Services\OpenAIService;
use DateTime;
use Exception;
use Google\Cloud\Translate\V2\TranslateClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Inertia\Inertia;
use Laravel\Pennant\Middleware\EnsureFeaturesAreActive;
use Sqids\Sqids;
use Stripe\StripeClient;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->app->bind(StripeClient::class, fn () => new StripeClient(config('cashier.secret')));

        $this->app->bind(Sqids::class, fn () => new Sqids(config('services.sqids.dictionary')));

        $this->app->bind(TranslateClient::class, fn () => new TranslateClient([
            'key' => config('services.google.translate.key'),
        ]));

        // ---------------------------------------------------------------------------------------
        // AI Services bind
        // ***************************************************************************************

        try {
            $selectedModel = Schema::hasTable('settings') ? Setting::firstWhere('name', 'ai_service') : null;
        } catch (Exception) {
            $selectedModel = null;
        }

        if (is_null($selectedModel)) {
            $selectedModel = 'Claude3';
        } else {
            $selectedModel = $selectedModel->value;
        }

        try {
            $requestType = Schema::hasTable('settings') ? Setting::firstWhere('name', 'ai_request_type') : null;
        } catch (Exception) {
            $requestType = null;
        }
        $requestType = is_null($requestType) ? 'chunked' : $requestType->value;
        $segmentate = $requestType == 'chunked' ? true : false;

        if ($selectedModel === 'Claude3') {
            $this->app->bind(AiService::class, fn () => new Claude3Service(config('services.anthropic.key'), $segmentate));
        } elseif (str_starts_with($selectedModel, 'GPT4')) {
            $modelName = config('services.openai.models.chat'); //default model
            $openAIParts = explode(':', $selectedModel);
            if (count($openAIParts) == 2) {
                $modelName = $openAIParts[1];
            }
            $this->app->bind(AiService::class, fn () => new OpenAIService(config('services.openai.fake'), $modelName, $segmentate));
        }
        // ---------------------------------------------------------------------------------------

        Storage::disk('local')->buildTemporaryUrlsUsing(
            fn (string $path, DateTime $expiration, array $options) => URL::temporarySignedRoute(
                'temp.url',
                $expiration,
                array_merge($options, ['path' => $path]),
            ),
        );

        EnsureFeaturesAreActive::whenInactive(fn (Request $request) => $request->wantsJson()
            ? response()->json(['message' => 'This feature is not available'], 418)
            : Inertia::render('Pennent')
        );
    }
}
