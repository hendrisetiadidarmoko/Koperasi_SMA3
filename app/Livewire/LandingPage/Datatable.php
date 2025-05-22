<?php

namespace App\Livewire\LandingPage;

use App\Models\Item;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class Datatable extends DataTableComponent
{
    protected $model = Item::class;

    protected $listeners = [
        're_render_table' => '$refresh'
    ];


    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setSearchVisibilityEnabled();
        $this->setDefaultSort('id', 'desc');
        $this->setEagerLoadAllRelationsEnabled();
    }

    public function columns(): array
    {
        return [
            Column::make('ID', 'id')
                ->sortable(),
            Column::make('Nama', 'name')
                ->sortable()
                ->searchable(),
            Column::make('Harga Penjualan', 'price')
                ->sortable()
                ->format(function ($value) {
                    return number_format($value ?? 0, 0, '.', ',');
                }),
            Column::make('Jumlah', 'count')
                ->sortable(),
        ];
    }
}