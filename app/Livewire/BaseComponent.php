<?php

namespace App\Livewire;

use App\Traits\WithChangeOrder;
use App\Traits\WithGetFilterData;
use Livewire\WithPagination;
use Livewire\Component;
use App\Traits\WithCreateAction;
use App\Traits\WithDeleteAction;
use App\Traits\WithEditAction;
use App\Traits\WithSaveAction;

class BaseComponent extends Component {
    use WithPagination,
        WithChangeOrder,
        WithGetFilterData,
        WithCreateAction,
        WithEditAction,
        WithDeleteAction,
        WithSaveAction;
}
