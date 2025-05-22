<?php

namespace App\Livewire\ItemsPurchase;

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
                    return number_format($value ?? 0, 0, '.', ',');
                }),
            Column::make('Jumlah', 'count')
                ->sortable(),
            Column::make('Total')
                ->label(function ($row, Column $column) {
                $total = $row->count * $row->price; // Menghitung Total
                return number_format($total ?? 0, 0, '.', ',');
            }),
            Column::make('Dibuat Oleh', 'user.name')
                ->sortable(),
            Column::make('Dibuat Tanggal', 'created_at') // Menampilkan tanggal pembuatan
                ->sortable()
                ->format(fn ($value) => \Carbon\Carbon::parse($value)->format('d-m-Y H:i')),
            Column::make('Aksi', 'id')
                ->view('components.table.actions'),
        ];
    }
}