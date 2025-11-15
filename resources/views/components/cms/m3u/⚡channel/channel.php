<?php

use App\Enums\CommonStatusEnum;
use App\Livewire\BaseComponent;
use App\Models\M3u\M3uChannel;
use App\Models\M3u\M3uSource;
use App\Traits\WithMediaCollection;
use Livewire\Attributes\On;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;

new class extends BaseComponent
{
    use WithFileUploads, WithMediaCollection;

    // Model instance
    public $modelInstance = M3uChannel::class;

    public M3uSource $m3uSource;

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
            'name' => 'Status',
            'field' => 'status',
        ],
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
        $model = M3uChannel::query()
            ->where('m3u_source_id', $this->m3uSource->id);

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

    public $oldData;

    public $alias;

    public $image;

    public $status;

    // Get record data
    public function getRecordData($id)
    {
        // Check permission
        if (! auth()->user()->can('show'.$this->modelInstance)) {
            $this->dispatch('toast', type: 'error', message: 'You do not have permission to view this record.');

            return;
        }

        $record = M3uChannel::find($id);
        $this->recordId = $record->id;
        $this->oldData = $record;
        $this->alias = $record->alias ? $record->alias : $record->name;
        $this->status = $record->status->value;
    }

    // Reset record data
    public function resetRecordData()
    {
        $this->reset([
            'recordId',
            'oldData',
            'alias',
            'image',
            'status',
        ]);

        $this->status = CommonStatusEnum::ACTIVE->value;
    }

    // Handle form submit
    public function submit()
    {
        $this->validate([
            'alias' => 'nullable|string|max:255',
            'status' => 'required|in:'.implode(',', CommonStatusEnum::toArray()),
            // Max 50MB
            'image' => 'nullable|image:allow_svg|max:51200',
        ]);

        $model = $this->save();

        // Handle image upload
        if ($this->image instanceof TemporaryUploadedFile) {
            $this->saveFile(
                model: $model,
                file: $this->image,
                collection: 'image',
            );
        }

        $this->resetRecordData();
    }

    // Toggle status
    #[On('toggleStatus')]
    public function toggleStatus($id)
    {
        $record = M3uChannel::find($id);
        $record->status = $record->status === CommonStatusEnum::ACTIVE ? CommonStatusEnum::INACTIVE : CommonStatusEnum::ACTIVE;
        $record->save();

        $this->dispatch('toast', type: 'success', message: 'Status updated successfully.');
    }

    // Call API
    public function callApi()
    {
        try {
            // Call Api
            $ch = curl_init();
            $url = $this->m3uSource->url;
            $type = $this->m3uSource->type;
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $type);

            // SSL important
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

            // Headers
            $headers = $this->m3uSource->headers;
            if (! empty($headers)) {
                $headers = json_decode($headers, true);
                $fix_headers = [];
                foreach ($headers as $key => $value) {
                    $fix_headers[] = $key.': '.$value;
                }

                curl_setopt($ch, CURLOPT_HTTPHEADER, $fix_headers);
            }

            // Body
            $body = $this->m3uSource->body;
            if (! empty($body)) {
                curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
            }

            $output = curl_exec($ch);
            curl_close($ch);

            preg_match_all('/(?P<tag>#EXTINF:-1)|(?:(?P<prop_key>[-a-z]+)=\"(?P<prop_val>[^"]+)")|(?<something>,[^\r\n]+)|(?<url>http[^\s]+)/', $output, $match);

            $count = count($match[0]);
            $result = [];
            $index = -1;

            for ($i = 0; $i < $count; $i++) {
                $item = $match[0][$i];

                if (! empty($match['tag'][$i])) {
                    // is a tag increment the result index
                    $index++;
                } elseif (! empty($match['prop_key'][$i])) {
                    // is a prop - split item
                    $result[$index][$match['prop_key'][$i]] = $match['prop_val'][$i];
                } elseif (! empty($match['something'][$i])) {
                    // is a prop - split item
                    $result[$index]['name'] = str_replace(',', '', $item);
                } elseif (! empty($match['url'][$i])) {
                    $result[$index]['url'] = $item;
                }
            }

            // Save to database
            foreach ($result as $item) {
                M3uChannel::updateOrCreate([
                    'm3u_source_id' => $this->m3uSource->id,
                    'name' => $item['name'],
                ], [
                    'url' => $item['url'],
                    'status' => CommonStatusEnum::ACTIVE,
                ]);
            }

            $this->dispatch('toast', type: 'success', message: 'API call successful and data saved.');
        } catch (\Exception $e) {
            $this->dispatch('toast', type: 'error', message: 'API call failed: '.$e->getMessage());
        }
    }
};
