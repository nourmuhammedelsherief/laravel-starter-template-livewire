<div
    x-data="dashboardCharts({
        salesOverview: @js($salesOverview),
        userDistribution: @js($userDistribution),
        monthlyRevenue: @js($monthlyRevenue),
        months: @js($months),
    })"
    x-init="init()"
    class="flex h-full w-full flex-1 flex-col gap-6 bg-white p-4 dark:bg-[#0a0a0a] lg:p-8"
>
    <div class="flex flex-wrap items-center justify-between gap-3">
        <div>
            <flux:heading size="xl" level="1" class="text-lg font-extrabold text-neutral-900 dark:text-white">
                {{ __('Dashboard Analytics') }}
            </flux:heading>
            <flux:subheading class="text-base font-bold text-neutral-700 dark:text-zinc-300">
                {{ __('Track performance with real-time sales, users, and revenue trends.') }}
            </flux:subheading>
        </div>
    </div>

    <div class="grid gap-4 lg:grid-cols-3">
        <flux:card class="border-zinc-200 bg-white dark:border-zinc-700 dark:bg-[#0a0a0a] lg:col-span-2">
            <div class="mb-3">
                <flux:heading class="text-base font-extrabold text-neutral-900 dark:text-white">{{ __('Sales Overview') }}</flux:heading>
            </div>
            <div id="salesOverviewChart" wire:ignore class="h-80 w-full"></div>
        </flux:card>

        <flux:card class="border-zinc-200 bg-white dark:border-zinc-700 dark:bg-[#0a0a0a]">
            <div class="mb-3">
                <flux:heading class="text-base font-extrabold text-neutral-900 dark:text-white">{{ __('User Distribution') }}</flux:heading>
            </div>
            <div id="userDistributionChart" wire:ignore class="h-80 w-full"></div>
        </flux:card>
    </div>

    <flux:card class="border-zinc-200 bg-white dark:border-zinc-700 dark:bg-[#0a0a0a]">
        <div class="mb-3">
            <flux:heading class="text-base font-extrabold text-neutral-900 dark:text-white">{{ __('Monthly Revenue') }}</flux:heading>
        </div>
        <div id="monthlyRevenueChart" wire:ignore class="h-96 w-full"></div>
    </flux:card>
</div>

@script
<script>
    window.dashboardCharts = (data) => ({
        charts: {},
        observer: null,
        mediaQuery: null,
        onThemeEvent: null,
        onResizeEvent: null,

        init() {
            this.buildCharts(data);
            this.bindThemeWatchers();
            this.bindResizeWatcher();
        },

        isDark() {
            return document.documentElement.classList.contains('dark')
                || window.matchMedia('(prefers-color-scheme: dark)').matches;
        },

        palette() {
            const dark = this.isDark();

            return {
                mode: dark ? 'dark' : 'light',
                cardBg: dark ? '#0a0a0a' : '#ffffff',
                text: dark ? '#ffffff' : '#171717',
                secondaryText: dark ? '#a1a1aa' : '#525252',
                border: dark ? '#3f3f46' : '#d4d4d8',
                tooltipTheme: dark ? 'dark' : 'light',
                // Tailwind Indigo-500, Emerald-400, Sky-500
                series: ['#6366f1', '#34d399', '#0ea5e9'],
            };
        },

        baseOptions() {
            const p = this.palette();

            return {
                chart: {
                    fontFamily: 'Instrument Sans, sans-serif',
                    toolbar: { show: false },
                    background: p.cardBg,
                },
                theme: { mode: p.mode },
                colors: p.series,
                dataLabels: { enabled: false },
                stroke: { width: 3, curve: 'smooth' },
                grid: { borderColor: p.border, strokeDashArray: 4 },
                xaxis: {
                    labels: { style: { colors: p.secondaryText, fontWeight: 700 } },
                    axisBorder: { color: p.border },
                    axisTicks: { color: p.border },
                },
                yaxis: {
                    labels: {
                        style: { colors: p.secondaryText, fontWeight: 700 },
                        formatter: (val) => Math.round(val).toLocaleString(),
                    },
                },
                legend: { labels: { colors: p.text } },
                tooltip: {
                    theme: p.tooltipTheme,
                    x: { show: true },
                    y: {
                        formatter: (val) => Number(val).toLocaleString(),
                    },
                },
            };
        },

        buildCharts(data) {
            this.destroyCharts();

            const base = this.baseOptions();

            this.charts.sales = new ApexCharts(document.querySelector('#salesOverviewChart'), {
                ...base,
                chart: { ...base.chart, type: 'area', height: 320 },
                series: [{ name: 'Sales', data: data.salesOverview }],
                xaxis: { ...base.xaxis, categories: data.months },
                fill: {
                    type: 'gradient',
                    gradient: {
                        shadeIntensity: 1,
                        opacityFrom: 0.35,
                        opacityTo: 0.05,
                        stops: [0, 90, 100],
                    },
                },
            });

            this.charts.distribution = new ApexCharts(document.querySelector('#userDistributionChart'), {
                ...base,
                chart: { ...base.chart, type: 'donut', height: 320 },
                series: data.userDistribution.series,
                labels: data.userDistribution.labels,
                stroke: { show: false },
                plotOptions: {
                    pie: {
                        donut: {
                            size: '70%',
                            labels: {
                                show: true,
                                value: {
                                    color: this.palette().text,
                                    fontWeight: 700,
                                },
                            },
                        },
                    },
                },
            });

            this.charts.revenue = new ApexCharts(document.querySelector('#monthlyRevenueChart'), {
                ...base,
                chart: { ...base.chart, type: 'bar', height: 360 },
                series: [{ name: 'Revenue', data: data.monthlyRevenue }],
                xaxis: { ...base.xaxis, categories: data.months },
                plotOptions: {
                    bar: {
                        borderRadius: 6,
                        borderRadiusApplication: 'end',
                        columnWidth: '52%',
                    },
                },
            });

            Object.values(this.charts).forEach((chart) => chart.render());
        },

        updateTheme() {
            const base = this.baseOptions();
            const p = this.palette();

            Object.values(this.charts).forEach((chart) => {
                chart.updateOptions({
                    theme: { mode: p.mode },
                    chart: { background: p.cardBg },
                    grid: { borderColor: p.border },
                    xaxis: {
                        labels: { style: { colors: p.secondaryText, fontWeight: 700 } },
                        axisBorder: { color: p.border },
                        axisTicks: { color: p.border },
                    },
                    yaxis: {
                        labels: { style: { colors: p.secondaryText, fontWeight: 700 } },
                    },
                    legend: { labels: { colors: p.text } },
                    tooltip: { theme: p.tooltipTheme },
                }, false, true, true);
            });
        },

        bindThemeWatchers() {
            this.mediaQuery = window.matchMedia('(prefers-color-scheme: dark)');
            this.onThemeEvent = () => this.updateTheme();

            this.mediaQuery.addEventListener('change', this.onThemeEvent);

            this.observer = new MutationObserver(() => this.updateTheme());
            this.observer.observe(document.documentElement, {
                attributes: true,
                attributeFilter: ['class'],
            });
        },

        bindResizeWatcher() {
            this.onResizeEvent = () => {
                Object.values(this.charts).forEach((chart) => chart.resize());
            };

            window.addEventListener('resize', this.onResizeEvent);
        },

        destroyCharts() {
            Object.values(this.charts).forEach((chart) => chart?.destroy());
            this.charts = {};
        },

        destroy() {
            this.destroyCharts();

            if (this.observer) {
                this.observer.disconnect();
            }

            if (this.mediaQuery && this.onThemeEvent) {
                this.mediaQuery.removeEventListener('change', this.onThemeEvent);
            }

            if (this.onResizeEvent) {
                window.removeEventListener('resize', this.onResizeEvent);
            }
        },
    });
</script>
@endscript
