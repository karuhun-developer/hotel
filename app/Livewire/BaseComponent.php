<?php

namespace App\Livewire;

use App\Traits\Livewire\WithChangeOrder;
use App\Traits\WithGetFilterData;
use Flux\Flux;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

abstract class BaseComponent extends Component
{
    use WithChangeOrder, WithGetFilterData, WithPagination;

    public $paginationOrderBy = 'id';

    public $paginationOrder = 'desc';

    public $paginate = 10;

    public $isUpdate = false;

    public $search = '';

    public function create(string $modal = 'defaultModal', bool $checkPermission = true, ?string $permission = null)
    {
        // Custom permission
        $permission = $permission ?? 'create'.$this->modelInstance;

        // Check if has permission
        if ($checkPermission && ! auth()->user()->can($permission)) {
            $this->dispatch('toast', type: 'error', message: 'You do not have permission to perform this action.');

            return;
        }

        // Set isUpdate to false
        $this->isUpdate = false;

        // Reset all properties
        $this->resetRecordData();

        // Open modal
        Flux::modal($modal)->show();
    }

    public function update($id, string $modal = 'defaultModal', bool $checkPermission = true, ?string $permission = null)
    {
        // Custom permission
        $permission = $permission ?? 'update'.$this->modelInstance;

        // Check if has permission
        if ($checkPermission && ! auth()->user()->can($permission)) {
            $this->dispatch('toast', type: 'error', message: 'You do not have permission to perform this action.');

            return;
        }

        // Set isUpdate to true
        $this->isUpdate = true;

        // Get record data
        $this->getRecordData($id);

        // Open modal
        Flux::modal($modal)->show();
    }

    #[On('delete')]
    public function delete($id)
    {
        // Check if has permission
        if (! auth()->user()->can('delete'.$this->modelInstance)) {
            $this->dispatch('toast', type: 'error', message: 'You do not have permission to perform this action.');

            return;
        }

        // Find record
        $modelClass = new $this->modelInstance;

        // Delete record
        try {
            // TODO : I DON'T KNOW WHY ERROR (Class name must be a valid object or a string )
            // $record = $modelClass->find($id);
            // $record->delete();

            \Illuminate\Support\Facades\DB::delete('delete from '.$modelClass->getTable().' where id = ?', [$id]);

            $this->dispatch('toast', type: 'success', message: 'Record deleted successfully.');
        } catch (\Exception $e) {
            $this->dispatch('toast', type: 'error', message: 'An error occurred while deleting the record.');
        }
    }

    public function save(
        string $modal = 'defaultModal',
        bool $checkPermission = true,
        ?string $permission = null,
        $id = null,
    ) {
        // Determine action
        $action = $this->isUpdate ? 'update' : 'create';

        // Get id
        $id = $id ?? ($this->isUpdate ? $this->recordId : null);

        // Custom permission
        $permission = $permission ?? $action.$this->modelInstance;

        // Check if has permission
        if ($checkPermission && ! auth()->user()->can($permission)) {
            $this->dispatch('toast', type: 'error', message: 'You do not have permission to perform this action.');

            return;
        }

        // Find or create record
        $modelClass = new $this->modelInstance;
        $record = $this->isUpdate ? $modelClass->find($id) : $modelClass;
        // Fill record with validated data
        $record->fill($this->all());

        // Save record
        try {
            $record->save();
            $this->dispatch('toast', type: 'success', message: 'Record saved successfully.');
            // Close modal
            Flux::modal($modal)->close();
        } catch (\Exception $e) {
            $this->dispatch('toast', type: 'error', message: 'An error occurred while saving the record.');
        }

        return $record;
    }

    public function closeModal(string $modal = 'defaultModal')
    {
        Flux::modal($modal)->close();
    }
}
