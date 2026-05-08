<?php

use App\Enums\CommonStatusEnum;
use App\Models\M3u\M3uChannel;
use App\Models\M3u\M3uSource;
use App\Models\Tenant\Tenant;
use App\Models\Tenant\TenantChannel;
use Flux\Flux;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use Livewire\Component;

new class extends Component
{
    public $modelInstance = TenantChannel::class;

    public Tenant $tenant;

    #[Url(as: 'source', except: '')]
    public $filterM3uSource = '';

    // Alias edit
    public $recordId;

    public $alias;

    #[Computed]
    public function m3uSources()
    {
        return M3uSource::where('status', CommonStatusEnum::ACTIVE)->get();
    }

    public function mount(Tenant $tenant)
    {
        Gate::authorize('view'.$this->modelInstance);
        $this->tenant = $tenant;
    }

    public function render()
    {
        $data = M3uChannel::query()
            ->when($this->filterM3uSource, function ($query) {
                $query->where('m3u_channels.m3u_source_id', $this->filterM3uSource);
            })
            ->join('m3u_sources', 'm3u_channels.m3u_source_id', '=', 'm3u_sources.id')
            ->leftJoin('tenant_channels', function ($join) {
                $join->on('m3u_channels.id', '=', 'tenant_channels.m3u_channel_id')
                    ->where('tenant_channels.tenant_id', $this->tenant->id);
            })
            ->select(
                'm3u_channels.*',
                'm3u_sources.name as source_name',
                'tenant_channels.id as tenant_channel_id',
                'tenant_channels.alias as tenant_channel_alias',
            )
            ->orderBy('m3u_sources.name')
            ->orderBy('m3u_channels.name')
            ->get();

        return view('components.cms.tenant.channel.⚡table.table', [
            'data' => $data,
        ]);
    }

    public function activateChannel($sourceId, $channelId)
    {
        Gate::authorize('update'.$this->modelInstance);

        TenantChannel::firstOrCreate([
            'tenant_id' => $this->tenant->id,
            'm3u_source_id' => $sourceId,
            'm3u_channel_id' => $channelId,
        ]);

        $this->dispatch('toast', type: 'success', message: 'Channel activated successfully.');
    }

    public function deactivateChannel($sourceId, $channelId)
    {
        Gate::authorize('update'.$this->modelInstance);

        TenantChannel::where('tenant_id', $this->tenant->id)
            ->where('m3u_source_id', $sourceId)
            ->where('m3u_channel_id', $channelId)
            ->delete();

        $this->dispatch('toast', type: 'success', message: 'Channel deactivated successfully.');
    }

    public function activateAll()
    {
        Gate::authorize('update'.$this->modelInstance);

        $channels = M3uChannel::when($this->filterM3uSource, function ($query) {
            $query->where('m3u_source_id', $this->filterM3uSource);
        })->get();

        foreach ($channels as $channel) {
            TenantChannel::firstOrCreate([
                'tenant_id' => $this->tenant->id,
                'm3u_source_id' => $channel->m3u_source_id,
                'm3u_channel_id' => $channel->id,
            ]);
        }

        $this->dispatch('toast', type: 'success', message: 'All channels activated successfully.');
    }

    public function deactivateAll()
    {
        Gate::authorize('update'.$this->modelInstance);

        TenantChannel::where('tenant_id', $this->tenant->id)
            ->when($this->filterM3uSource, function ($query) {
                $query->where('m3u_source_id', $this->filterM3uSource);
            })->delete();

        $this->dispatch('toast', type: 'success', message: 'All channels deactivated successfully.');
    }

    #[On('set-action')]
    public function setAction($id = null)
    {
        if ($id) {
            $record = TenantChannel::findOrFail($id);
            $this->recordId = $record->id;
            $this->alias = $record->alias ?? $record->m3uChannel->name;
        } else {
            $this->reset(['recordId', 'alias']);
        }
    }

    public function submitAlias()
    {
        Gate::authorize('update'.$this->modelInstance);

        $this->validate([
            'alias' => 'nullable|string|max:255',
        ]);

        $record = TenantChannel::findOrFail($this->recordId);
        $record->update(['alias' => $this->alias]);

        $this->dispatch('toast', type: 'success', message: 'Channel alias updated successfully.');
        Flux::modal('aliasModal')->close();
    }
};
