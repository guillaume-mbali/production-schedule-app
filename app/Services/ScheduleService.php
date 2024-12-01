<?php

namespace App\Services;

use App\Models\ChangeoverTime;
use Illuminate\Database\Eloquent\Collection;
use App\Models\OrderItem;
use Carbon\Carbon;

class ScheduleService
{
    /**
     * Returns the color associated with a product type.
     *
     * @param string $productType
     * @return string
     */
    public function getColorForProductType(string $productType): string
    {
        // Specify that 'type_colors' is an associative array of string => string
        $colors = config('product_types.type_colors') ?? [];

        return $colors[$productType] ?? $colors['default'];
    }

    /**
     * Retrieves the changeover time between two product types.
     *
     * @param string $fromProductTypeId
     * @param string $toProductTypeId
     * @return int
     */
    public function getChangeoverTime(string $fromProductTypeId, string $toProductTypeId): int
    {
        $changeover = ChangeoverTime::where('from_product_type_id', $fromProductTypeId)
            ->where('to_product_type_id', $toProductTypeId)
            ->first();

        return $changeover ? $changeover->changeover_time : 0;
    }

    /**
     * Calculates the total production duration for one or more order items.
     *
     * @param OrderItem|Collection<int, OrderItem> $orderItems An OrderItem instance or a collection of OrderItem instances.
     * @return int The total production duration in minutes.
     */
    public function calculateProductionDuration(OrderItem|Collection $orderItems): int
    {
        $totalProductionDuration = 0;

        // If a single OrderItem is provided, convert it to a collection for uniform processing
        if ($orderItems instanceof OrderItem) {
            $orderItems = collect([$orderItems]);
        }

        foreach ($orderItems as $orderItem) {
            $product = $orderItem->product;
            if (!$product) {
                continue;
            }

            $productionSpeed = $product->productType->production_speed;

            // Calculate the production duration
            $productionDuration = ($orderItem->quantity * 60) / $productionSpeed;

            $totalProductionDuration += $productionDuration;
        }
        return $totalProductionDuration;
    }



    /**
     * Get the start time for an order, adjusting for overlap with the previous order.
     *
     * @param Carbon $lastEndTime
     * @param string $timezone
     * @return Carbon
     */
    public function getStartTime(Carbon $lastEndTime, string $timezone): Carbon
    {
        $start = Carbon::today($timezone)->setHour(8)->setMinute(0)->setSecond(0);

        if ($start->lessThan($lastEndTime)) {
            $start = $lastEndTime;
        }

        return $start;
    }

    /**
     * Get the end time based on the production duration.
     *
     * @param Carbon $start
     * @param int $productionDuration
     * @return Carbon
     */
    public function getEndTime(Carbon $start, int $productionDuration): Carbon
    {
        $end = $start->copy()->addMinutes($productionDuration);

        // Adjust for time past 6:00 PM
        if ($end->hour > 18) {
            $end = $end->addDay()->setHour(8)->setMinute(0)->setSecond(0);
        }

        // Adjust for times before 8:00 AM on the next day
        if ($end->hour < 8) {
            $end->setHour(8)->setMinute(0)->setSecond(0);
        }

        return $end;
    }
}
