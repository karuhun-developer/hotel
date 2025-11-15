<?php

use App\Enums\CommonStatusEnum;
use App\Livewire\BaseComponent;
use App\Models\M3u\M3uChannel;
use App\Models\M3u\M3uSource;
use App\Models\Tenant\Tenant;
use App\Models\Tenant\TenantChannel;

new class extends BaseComponent
{
    // Model instance
    public $modelInstance = TenantChannel::class;

    public Tenant $tenant;

    public $m3uSources = [];

    // M3U Source filter
    public $filterM3uSource = '';

    public function mount()
    {
        // Check if user has permission to view
        if (! auth()->user()->can('view'.$this->modelInstance)) {
            abort(403, 'You do not have permission to view this page.');
        }

        // Get all M3U sources
        $this->m3uSources = M3uSource::where('status', CommonStatusEnum::ACTIVE)->get();
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

        return $this->view([
            'data' => $data,
        ]);
    }

    public function activateChannel($sourceId, $channelId)
    {
        // Check if user has permission to activate
        if (! auth()->user()->can('update'.$this->modelInstance)) {
            abort(403, 'You do not have permission to perform this action.');
        }

        // Activate the channel for the tenant
        TenantChannel::firstOrCreate([
            'tenant_id' => $this->tenant->id,
            'm3u_source_id' => $sourceId,
            'm3u_channel_id' => $channelId,
        ]);

        // Alert success
        $this->dispatch('toast', type: 'success', message: 'Channel has been activated successfully.');
    }

    public function deactivateChannel($sourceId, $channelId)
    {
        // Check if user has permission to deactivate
        if (! auth()->user()->can('update'.$this->modelInstance)) {
            abort(403, 'You do not have permission to perform this action.');
        }

        // Deactivate the channel for the tenant
        TenantChannel::where('tenant_id', $this->tenant->id)
            ->where('m3u_source_id', $sourceId)
            ->where('m3u_channel_id', $channelId)
            ->delete();

        // Alert success
        $this->dispatch('toast', type: 'success', message: 'Channel has been deactivated successfully.');
    }

    public function activateAll()
    {
        // Check if user has permission to activate
        if (! auth()->user()->can('update'.$this->modelInstance)) {
            abort(403, 'You do not have permission to perform this action.');
        }

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

        // Alert success
        $this->dispatch('toast', type: 'success', message: 'All channels have been activated successfully.');
    }

    public function deactivateAll()
    {
        // Check if user has permission to deactivate
        if (! auth()->user()->can('update'.$this->modelInstance)) {
            abort(403, 'You do not have permission to perform this action.');
        }

        TenantChannel::where('tenant_id', $this->tenant->id)
            ->when($this->filterM3uSource, function ($query) {
                $query->where('m3u_source_id', $this->filterM3uSource);
            })->delete();

        // Alert success
        $this->dispatch('toast', type: 'success', message: 'All channels have been deactivated successfully.');
    }

    // Properties for update alias channel
    public $recordId;

    public $alias;

    // Get record data
    public function getRecordData($id)
    {
        // Check permission
        if (! auth()->user()->can('show'.$this->modelInstance)) {
            $this->dispatch('toast', type: 'error', message: 'You do not have permission to view this record.');

            return;
        }

        $record = TenantChannel::find($id);
        $this->recordId = $record->id;
        $this->alias = $record->alias ?? $record->m3uChannel->name;
    }

    // Reset record data
    public function resetRecordData()
    {
        $this->reset([
            'recordId',
            'alias',
        ]);
    }

    // Handle form submit
    public function submit()
    {
        $this->validate([
            'alias' => 'nullable|string|max:255',
        ]);

        $this->save();
    }
};
