<div class="actions-table d-flex flex-md-row flex-column align-items-center">
    <button class="btn btn-primary mb-2 mb-md-0 mr-md-2 w-100 w-md-auto m-1" wire:click.prevent="edit({{ $row->id }})">
        <i class="fas fa-edit"></i> Edit Role
    </button>
    @if($row->role !== 'admin')
        <button class="btn btn-danger w-100 w-md-auto m-1" wire:click="editPassword({{ $row->id }})">
            <i class="fas fa-edit"></i> Edit Password
        </button>
    @endif
</div>
