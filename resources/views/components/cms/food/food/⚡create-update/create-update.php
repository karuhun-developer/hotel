<?php

use App\Actions\Cms\Food\Food\StoreFoodAction;
use App\Actions\Cms\Food\Food\UpdateFoodAction;
use App\Enums\CommonStatusEnum;
use App\Models\Tenant\Food\Food;
use App\Models\Tenant\Food\FoodCategory;
use App\Models\Tenant\Tenant;
use App\Traits\WithMediaCollection;
use Flux\Flux;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;

new class extends Component
{
    use WithFileUploads, WithMediaCollection;

    // Model instance
    public $modelInstance = Food::class;

    public $isUpdate = false;

    // Record data
    public $id;

    public $tenant_id;

    public $food_category_id;

    public $name;

    public $price;

    public $description;

    public $status;

    public $image;

    public $record = null;

    #[On('set-action')]
    public function setAction($id = null)
    {
        if ($id) {
            $this->isUpdate = true;
            $this->getRecordData($id);
        } else {
            $this->isUpdate = false;
            $this->resetRecordData();
        }
    }

    #[Computed]
    public function tenants()
    {
        return Tenant::where('status', CommonStatusEnum::ACTIVE)->get();
    }

    #[Computed]
    public function foodCategories()
    {
        return FoodCategory::when($this->tenant_id, function ($query) {
            $query->where('tenant_id', $this->tenant_id);
        })->where('status', CommonStatusEnum::ACTIVE)->get();
    }

    // Get record data
    public function getRecordData($id)
    {
        Gate::authorize('show'.$this->modelInstance);

        $record = Food::findOrFail($id);
        $this->record = $record;
        $this->fill(
            $record->only(
                'id',
                'tenant_id',
                'food_category_id',
                'name',
                'price',
                'description',
            )
        );
        $this->status = $record->status->value;
    }

    // Reset record data
    public function resetRecordData()
    {
        $this->record = null;
        $this->reset([
            'id',
            'tenant_id',
            'food_category_id',
            'name',
            'price',
            'description',
            'status',
            'image',
        ]);
        $this->tenant_id = auth()->user()->tenant?->tenant_id;
        $this->status = CommonStatusEnum::ACTIVE->value;
    }

    // Handle form submit
    public function submit(StoreFoodAction $storeAction, UpdateFoodAction $updateAction)
    {
        Gate::authorize(($this->isUpdate ? 'update' : 'create').$this->modelInstance);

        $this->validate([
            'tenant_id' => 'required|exists:tenants,id',
            'food_category_id' => 'required|exists:food_categories,id',
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'status' => 'required|in:'.implode(',', CommonStatusEnum::toArray()),
            'image' => 'nullable|image:allow_svg|max:51200',
        ]);

        $data = $this->only(['tenant_id', 'food_category_id', 'name', 'price', 'description', 'status']);

        if ($this->isUpdate) {
            $food = Food::findOrFail($this->id);
            $updateAction->handle(
                food: $food,
                data: $data,
            );
        } else {
            $food = $storeAction->handle(
                data: $data,
            );
        }

        // Handle image upload
        if ($this->image instanceof TemporaryUploadedFile) {
            $this->saveMedia(model: $food, file: $this->image, collection: 'image');
        }

        // Reset file input
        $this->reset('image');
        $this->record = null;

        // Toast message
        $this->dispatch('toast',
            type: 'success',
            message: $this->isUpdate ? 'Food updated successfully.' : 'Food created successfully.',
        );

        // Reset data table
        $this->dispatch('reset-parent-page');

        // Close modal
        Flux::modal('defaultModal')->close();
    }
};
