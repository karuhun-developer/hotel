<?php

use App\Livewire\BaseComponent;
use App\Models\Spatie\Role;
use App\Models\User;
use Livewire\Attributes\On;

new class extends BaseComponent
{
    // Model instance
    public $modelInstance = User::class;

    // Pagination and Search
    public $searchBy = [
        [
            'name' => 'Name',
            'field' => 'users.name',
        ],
        [
            'name' => 'Email',
            'field' => 'users.email',
        ],
        [
            'name' => 'Role',
            'field' => 'roles.name',
        ],
        [
            'name' => 'Email Verified',
            'field' => 'users.email_verified_at',
        ],
        [
            'name' => 'Created At',
            'field' => 'users.created_at',
        ],
    ];

    // Roles list
    public $roles;

    public function mount()
    {
        // Check if user has permission to view
        if (! auth()->user()->can('view'.$this->modelInstance)) {
            abort(403, 'You do not have permission to view this page.');
        }

        // Set default order by
        $this->paginationOrderBy = 'users.created_at';

        // Get roles list
        $this->roles = Role::all();
    }

    public function render()
    {
        if ($this->search != '') {
            $this->resetPage();
        }

        // Query data with filters
        $model = User::query()
            ->join('model_has_roles', 'model_has_roles.model_id', '=', 'users.id')
            ->join('roles', 'roles.id', '=', 'model_has_roles.role_id')
            ->select('users.*', 'roles.name as role');

        $data = $this->getDataWithFilter(
            model: $model,
            searchBy: $this->searchBy,
            orderBy: $this->paginationOrderBy,
            order: $this->paginationOrder,
            paginate: $this->paginate,
            s: $this->search,
        );

        return $this->view([
            'data' => $data,
        ]);
    }

    // Record data
    public $recordId;

    public $role;

    public $name;

    public $email;

    public $password;

    // Get record data
    public function getRecordData($id)
    {
        // Check permission
        if (! auth()->user()->can('show'.$this->modelInstance)) {
            $this->dispatch('toast', type: 'error', message: 'You do not have permission to perform this action.');

            return;
        }

        $record = User::find($id);
        $this->recordId = $record->id;
        $this->role = $record->getRoleNames()[0];
        $this->name = $record->name;
        $this->email = $record->email;
        $this->password = $record->password;
    }

    // Reset record data
    public function resetRecordData()
    {
        $this->reset([
            'recordId',
            'role',
            'name',
            'email',
            'password',
        ]);

        $this->guard_name = 'api';
    }

    // Handle form submit
    public function submit()
    {
        // Validation rules
        $emailRule = $this->isUpdate
            ? 'required|string|email|max:255|unique:users,email,'.$this->recordId
            : 'required|string|email|max:255|unique:users,email';

        $this->validate([
            'role' => 'required|string|exists:roles,name',
            'name' => 'required|string|max:255',
            'email' => $emailRule,
            'password' => $this->isUpdate ? 'nullable' : 'required|string|min:8',
        ]);

        // bcrypt password
        if (! $this->isUpdate) {
            $this->password = bcrypt($this->password);
        }

        // Save record
        $record = $this->save();

        // Sync role
        $record->syncRoles([$this->role]);
    }

    #[On('verifyEmail')]
    public function verifyEmail($id)
    {
        if (! auth()->user()->can('validate'.$this->modelInstance)) {
            $this->dispatch('toast', type: 'error', message: 'You do not have permission to perform this action.');

            return;
        }

        $user = User::findOrFail($id);
        $user->markEmailAsVerified();

        $this->dispatch('alert', type: 'success', message: 'Email verified successfully.');
    }

    // Change password modal
    public function changePassword($id)
    {
        // Check permission
        if (! auth()->user()->can('update'.$this->modelInstance)) {
            $this->dispatch('toast', type: 'error', message: 'You do not have permission to perform this action.');

            return;
        }

        // Get record data
        $this->getRecordData($id);

        // Reset password field
        $this->reset(['password']);

        // Open change password modal
        Flux::modal('changePasswordModal')->show();
    }

    // Handle change password submit
    public function changePasswordSubmit()
    {
        // Validation rules
        $this->validate([
            'password' => 'required|string|min:8',
        ]);

        // Find user and update password
        $user = User::find($this->recordId);
        $user->password = bcrypt($this->password);
        $user->save();

        // Alert success and close modal
        $this->dispatch('toast', type: 'success', message: 'Password changed successfully.');
        Flux::modal('changePasswordModal')->close();
    }
};
