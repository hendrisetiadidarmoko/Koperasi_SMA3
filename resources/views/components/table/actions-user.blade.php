<div class="actions-table d-flex align-items-center">
    <button class="btn btn-primary mr-5" wire:click.prevent="edit({{ $row->id }})">
        <i class="fas fa-edit"></i> Edit Role
    </button>
    @if($row->role !== 'admin')
        <button class="btn btn-danger ml-5" wire:click="editPassword({{ $row->id }})">
            <i class="fas fa-edit"></i> Edit Password
        </button>
    @endif
</div>
