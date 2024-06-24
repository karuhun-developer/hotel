<div>
    <h1 class="h3 mb-3">
        {{ $title ?? '' }}
    </h1>

    <div class="card">
        <div class="card-header">
            <h5 class="card-title">{{ $title ?? '' }} Data</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <x-acc-header :$originRoute createFunction="customCreate">
                    <div class="col-md-6">
                        <a class="btn btn-primary mt-4" href="{{ route('cms.management.menu') }}?on=docs">
                            List Menu Documentation
                        </a>
                    </div>
                </x-acc-header>
                <table class="table table-hover table-striped" style="width:100%">
                    <thead>
                        <tr>
                            <x-acc-loop-th :$searchBy :$orderBy :$order />
                            <th>
                                Action
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($get as $d)
                            <tr>
                                <td>{{ $d->menu }}</td>
                                <td>{{ $d->title }}</td>
                                <td>-</td>
                                <td>{{ $d->ordering }}</td>
                                <x-acc-update-delete :id="$d->id" :$originRoute editFunction="customEdit" />
                            </tr>
                        @empty
                            <tr>
                                <td colspan="100" class="text-center">
                                    No Data Found
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="float-end">
                    {{ $get->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
