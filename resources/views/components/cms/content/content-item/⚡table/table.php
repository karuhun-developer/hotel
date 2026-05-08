<?php

use App\Actions\Cms\Content\ContentItem\DeleteContentItemAction;
use App\Livewire\BaseComponent;
use App\Models\Tenant\Content\Content;
use App\Models\Tenant\Content\ContentItem;
use App\Traits\WithFilterTenantDateRange;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;

new class extends BaseComponent
{
    use WithFilterTenantDateRange;

    // Model instance
    public $modelInstance = ContentItem::class;

    #[Url(as: 'content', except: '')]
    public $contentFilter = '';

    #[Computed]
    public function contents()
    {
        $query = Content::query();
        if (! auth()->user()->isSuperAdmin()) {
            $query->where('tenant_id', auth()->user()->tenant?->tenant_id);
        } elseif ($this->tenantFilter) {
            $query->where('tenant_id', $this->tenantFilter);
        }

        return $query->get();
    }

    // Pagination and Search
    public $searchBy = [
        [
            'name' => 'Tenant Name',
            'field' => 'tenants.name',
        ],
        [
            'name' => 'Content',
            'field' => 'contents.name',
        ],
        [
            'name' => 'Name',
            'field' => 'content_items.name',
        ],
        [
            'name' => 'Status',
            'field' => 'content_items.status',
        ],
        [
            'name' => 'Created At',
            'field' => 'content_items.created_at',
        ],
    ];

    public function mount()
    {
        Gate::authorize('view'.$this->modelInstance);

        // Set default order by
        $this->paginationOrderBy = 'content_items.created_at';
    }

    public function render()
    {
        if ($this->search != '') {
            $this->resetPage();
        }

        // Query data with filters
        $model = ContentItem::query()
            ->join('contents', 'contents.id', '=', 'content_items.content_id')
            ->join('tenants', 'tenants.id', '=', 'content_items.tenant_id')
            ->select('content_items.*', 'tenants.name as tenant_name', 'contents.name as content_name')
            ->with('media');

        if (! auth()->user()->isSuperAdmin()) {
            $model->where('content_items.tenant_id', auth()->user()->tenant?->tenant_id);
        }

        $model = $this->applyFilterTenantDateRange(model: $model, tenantField: 'content_items.tenant_id', dateField: 'content_items.created_at');

        $model->when($this->contentFilter, function ($query) {
            $query->where('content_items.content_id', $this->contentFilter);
        });

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

    #[On('delete')]
    public function delete($id, DeleteContentItemAction $deleteAction)
    {
        Gate::authorize('delete'.$this->modelInstance);

        $deleteAction->handle(
            contentItem: ContentItem::findOrFail($id),
        );

        // Toast message
        $this->dispatch('toast', type: 'success', message: 'Content item deleted successfully.');
    }
};
