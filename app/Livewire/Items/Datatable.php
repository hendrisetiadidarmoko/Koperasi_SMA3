<?php

namespace App\Livewire\Items;

use App\Models\Item;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Milon\Barcode\Facades\DNS1DFacade as DNS1D;
class Datatable extends DataTableComponent
{
    protected $model = Item::class;

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
            Column::make('Nama', 'name')
                ->sortable()
                ->searchable(),
            Column::make('Barcode')
                ->format(function ($value) {
                    return $value
                        ? DNS1D::getBarcodeHTML($value, 'EAN13')
                        : '<em>Tidak tersedia</em>';
                })
                ->html(),
            Column::make('Nomer Barcode', 'barcode')
                ->sortable(),
            Column::make('Harga Penjualan', 'price')
                ->sortable()
                ->format(function ($value) {
                    return number_format($value ?? 0, 0, '.', ',');
                }),
            Column::make('Harga Pembelian', 'price_buy')
                ->sortable()
                ->format(function ($value) {
                    
                    return number_format($value ?? 0, 0, '.', ',');
                }),
            Column::make('Jumlah', 'count')
                ->sortable(),

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
