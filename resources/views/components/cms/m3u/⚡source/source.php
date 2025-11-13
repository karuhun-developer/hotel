<?php

use App\Enums\CommonStatusEnum;
use App\Livewire\BaseComponent;
use App\Models\M3u\M3uSource;

new class extends BaseComponent
{
    // Model instance
    public $modelInstance = M3uSource::class;

    // Pagination and Search
    public $searchBy = [
        [
            'name' => 'Name',
            'field' => 'name',
        ],
        [
            'name' => 'URL',
            'field' => 'url',
        ],
        [
            'name' => 'Type',
            'field' => 'type',
        ],
        [
            'name' => 'Status',
            'field' => 'status',
        ],
        [
            'name' => 'Created At',
            'field' => 'created_at',
        ]
    ];

    public function mount()
    {
        // Check if user has permission to view
        if (! auth()->user()->can('view'.$this->modelInstance)) {
            abort(403, 'You do not have permission to view this page.');
        }

        // Set default order by
        $this->paginationOrderBy = 'created_at';
    }

    public function render()
    {
        if ($this->search != '') {
            $this->resetPage();
        }

        // Query data with filters
        $data = $this->getDataWithFilter(
            model: M3uSource::withCount('channels'),
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

    public $name;

    public $url;

    public $type;

    public $headers;

    public $body;

    public $status;

    // Get record data
    public function getRecordData($id)
    {
        // Check permission
        if (! auth()->user()->can('show'.$this->modelInstance)) {
            $this->dispatch('toast', type: 'error', message: 'You do not have permission to view this record.');

            return;
        }

        $record = M3uSource::find($id);
        $this->recordId = $record->id;
        $this->name = $record->name;
        $this->url = $record->url;
        $this->type = $record->type;
        $this->headers = $record->headers;
        $this->body = $record->body;
        $this->status = $record->status->value;
    }

    // Reset record data
    public function resetRecordData()
    {
        $this->reset([
            'recordId',
            'name',
            'url',
            'type',
            'headers',
            'body',
            'status',
        ]);

        $this->type = 'GET';
        $this->status = CommonStatusEnum::ACTIVE->value;
    }

    // Handle form submit
    public function submit()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'url' => 'required|url|max:2048',
            'type' => 'required|in:POST,GET',
            'headers' => 'nullable|string',
            'body' => 'nullable|string',
            'status' => 'required|in:'.implode(',', CommonStatusEnum::toArray()),
        ]);

        $this->save();
    }
};
