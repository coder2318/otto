<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Laravel\Cashier\PromotionCode;

class PlanResource extends JsonResource
{
    protected ?PromotionCode $promo;

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data = parent::toArray($request);

        if (isset($this->promo)) {
            $coupon = $this->promo->coupon();

            $data['discount'] = $coupon->isPercentage() ? $coupon->percentOff().'%' : $coupon->amountOff();
        }

        return $data;
    }

    public function withPromo(?PromotionCode $promo)
    {
        $this->promo = $promo;

        return $this;
    }
}
