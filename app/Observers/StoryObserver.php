<?php

namespace App\Observers;

use App\Data\Story\Status;
use App\Models\Story;
use Signifly\Shopify\Support\Facades\Shopify;

class StoryObserver
{
    public function updated(Story $story)
    {
        if (!$story->isDirty('status')) {
            return;
        }

        if ($story->status === Status::PUBLISHED) {
            $product = Shopify::createProduct([
                'title' => $story->title,
            ]);

            $story->update(['shopify_id' => $product->id]);
        } elseif ($story->shopify_id) {
            Shopify::deleteProduct($story->shopify_id);

            $story->update(['shopify_id' => null]);
        }
    }

    public function deleted(Story $story)
    {
        if ($story->shopify_id) {
            Shopify::deleteProduct($story->shopify_id);
        }
    }

    public function forceDeleted(Story $story)
    {
        if ($story->shopify_id) {
            Shopify::deleteProduct($story->shopify_id);
        }
    }

    public function restored(Story $story)
    {
        if ($story->shopify_id && $story->status === Status::PUBLISHED) {
            $product = Shopify::createProduct([
                'title' => $story->title,
            ]);

            $story->update(['shopify_id' => $product->id]);
        }
    }
}
