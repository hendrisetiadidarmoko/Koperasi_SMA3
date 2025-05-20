<?php

namespace App\Livewire\ListItem;

use App\Models\ItemPurchase;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class Datatable extends DataTableComponent
{
    protected $model = ItemPurchase::class;

    protected $listeners = [
        're_render_table' => '$refresh'
    ];

    public function delete($id)
    {
        $this->dispatch('delete', $id); 
    }

    public function edit($id)
    {
        $this->dispatch('edit', $id); 
    }

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
            Column::make('Nama', 'item.name')
                ->sortable()
                ->searchable(),
            Column::make('Harga', 'price')
                ->sortable()
                ->format(function ($value) {
                    return number_format($value, 0, '.', '');
                }),
            Column::make('Jumlah', 'count')
                ->sortable(),
        ];
    }
}