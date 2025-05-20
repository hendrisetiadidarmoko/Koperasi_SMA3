<?php

namespace App\Livewire\Users;

use App\Models\User;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class Datatable extends DataTableComponent
{
    protected $model = User::class;

    protected $listeners = [
        're_render_table' => '$refresh',
    ];

    public function delete($id)
    {
        $this->dispatch('delete', $id);
    }

    public function edit($id)
    {
        $this->dispatch('edit', $id);
    }

    public function editPassword($id)
    {
        $this->dispatch('editPassword', $id);
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

            Column::make('Email', 'email')
                ->sortable()
                ,

            Column::make('No Telp', 'phone_number') // If this field exists
                ->sortable(),

            Column::make('Role', 'role')
                ->sortable(),
                
            Column::make('Aksi', 'id')
                ->view('components.table.actions-user'),
        ];
    }
}
