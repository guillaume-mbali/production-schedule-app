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
     * Calculates the total production duration for an order based on its order items.
     * The total production duration is rounded to the nearest whole number.
     *
     * @param Collection<int, OrderItem> $orderItems
     * @return int
     */
    public function calculateProductionDuration(Collection $orderItems): int
    {
        $totalProductionDuration = 0;

        foreach ($orderItems as $orderItem) {
            // Ensure the product and its type exist
            $product = $orderItem->product;
            if (!$product) {
                continue;  // Skip the item if the product does not exist
            }

            $productionSpeed = $product->productType->production_speed;

            // Calculate the production duration
            $productionDuration = ($orderItem->quantity * 60) / $productionSpeed;

            $totalProductionDuration += $productionDuration;
        }

        // Return the total duration rounded to the nearest integer
        return (int) round($totalProductionDuration);
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
