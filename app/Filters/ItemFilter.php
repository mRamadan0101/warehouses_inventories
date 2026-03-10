<?php

namespace App\Filters;

class ItemFilter extends Filters
{
    public function warehouse_id($value)
    {
        if (is_int($value) && !empty($value)) {
            return $this->query->whereHas('itemInventories', function ($q) use ($value) {
                $q->where('warehouse_id', $value);
            });
        }
    }

    public function name($value)
    {
        if (is_string($value) && !empty($value)) {
            return $this->query->where('name', 'like', '%'.$value.'%');
        }
    }

    public function price_from($value)
    {
        if (is_numeric($value) && !empty($value)) {
            return $this->query->where('price', '>=', $value);
        }
    }

    public function price_to($value)
    {
        if (is_numeric($value) && !empty($value)) {
            return $this->query->where('price', '<=', $value);
        }
    }
}
