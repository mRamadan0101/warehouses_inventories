<?php

namespace App\Http\Controllers\Api;

use App\Filters\ItemFilter;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\WarehouseResource;
use App\Http\Resources\Api\WarehouseResourceCollection;
use App\Models\Warehouse;
use Illuminate\Support\Facades\Cache;

class InventoryController extends Controller
{
    public function index(ItemFilter $filters)
    {
        $perPage = request()->get('per_page', 10);
        $page = request()->get('page', 1);

        $cacheKey = sprintf(
            'inventory:index:page:%s:per_page:%s:filters:%s',
            $page,
            $perPage,
            md5(json_encode(request()->only(['warehouse_id', 'name', 'price_from', 'price_to'])))
        );

        $warehouses = Cache::remember($cacheKey, now()->addMinutes(5), function () use ($filters, $perPage) {
            return Warehouse::with(['itemInventories' => function ($q) use ($filters) {
                $q->with('item')->whereHas('item', function ($query) use ($filters) {
                    $filters->apply($query);
                });
            }])->paginate($perPage);
        });

        return $this->setCode(200)
            ->setData(new WarehouseResourceCollection($warehouses))
            ->send();
    }

    public function show($id, ItemFilter $filters)
    {
        $cacheKey = sprintf(
            'inventory:warehouse:%s:filters:%s',
            $id,
            md5(json_encode(request()->only(['name', 'price_from', 'price_to'])))
        );

        $warehouse = Cache::remember($cacheKey, now()->addMinutes(5), function () use ($id, $filters) {
            return Warehouse::with(['itemInventories' => function ($q) use ($filters) {
                $q->with('item')->whereHas('item', function ($query) use ($filters) {
                    $filters->apply($query);
                });
            }])->findOrFail($id);
        });

        return $this->setCode(200)
            ->setData(new WarehouseResource($warehouse))
            ->send();
    }
}
