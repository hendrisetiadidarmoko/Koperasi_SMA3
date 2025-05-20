<div class="actions-table d-flex align-items-center">
    <button class="btn btn-primary mr-5" wire:click.prevent="edit({{ $row->id }})">
        <i class="fas fa-edit"></i> Edit
    </button>
    <button class="btn btn-danger ml-5" wire:click.prevent="delete({{ $row->id }})">
        <i class="fas fa-trash-alt"></i> Delete
    </button>
</div>
