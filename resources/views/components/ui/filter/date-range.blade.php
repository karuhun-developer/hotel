<div class="w-full" x-data="{
    startDate: $wire.entangle('startDateFilter'),
    endDate: $wire.entangle('endDateFilter'),
    start: null,
    end: null,
    cb(start, end) {
        if (start == null && end == null) {
            $('#reportrange span').html('All Time');
            return;
        }

        $('#reportrange span').html(start.format('DD/MM/YYYY') + ' - ' + end.format('DD/MM/YYYY'));

        $wire.set('startDateFilter', start.format('YYYY-MM-DD'));
        $wire.set('endDateFilter', end.format('YYYY-MM-DD'));
    },
    resetFilters() {
        this.startDate = '';
        this.endDate = '';
        this.cb(null, null);
    },
    init() {
        let start = this.startDate == '' ? null : moment(this.startDate).startOf('day');
        let end = this.endDate == '' ? null : moment(this.endDate);
        $('#reportrange').daterangepicker({
            // startDate: start,
            // endDate: end,
            ranges: {
                'Today': [moment(), moment()],
                'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                'This Month': [moment().startOf('month'), moment().endOf('month')],
                'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
                'Last 6 Months': [moment().subtract(6, 'month'), moment()],
                'Last 1 Year': [moment().subtract(1, 'year'), moment()],
                'Last 2 Year': [moment().subtract(2, 'year'), moment()],
                'Last 3 Year': [moment().subtract(3, 'year'), moment()],
            }
        }, this.cb);
        this.cb(start, end);
    }
}">
    <div class="flex items-center justify-between mb-3 gap-3">
        <p class="text-sm font-medium text-gray-700 dark:text-gray-300 w-full">Filter Tanggal:</p>
        <div class="w-full" wire:ignore>
            <div id="reportrange" class="bg-white dark:bg-gray-800 cursor-pointer px-4 py-3 border border-gray-300 dark:border-gray-600 w-full rounded-md flex items-center justify-between text-gray-700 dark:text-gray-300 min-w-[280px] h-8">
                <div class="flex items-center gap-3">
                    <flux:icon.calendar size="sm" class="text-gray-400 dark:text-gray-500" />
                    <span class="text-sm font-medium"></span>
                </div>
            </div>
        </div>
        <flux:button
            variant="primary"
            color="yellow"
            icon="arrow-path"
            size="sm"
            @click="resetFilters()">
            Reset
        </flux:button>
    </div>
</div>
@once
<script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
@endonce
