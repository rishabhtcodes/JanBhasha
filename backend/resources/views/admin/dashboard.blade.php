<x-app-layout>
    <x-slot name="header">Admin Dashboard</x-slot>

    @php
        $user = auth()->user();
        $isSuper = $user->isSuperAdmin();
        $org = $user->organisation;

        // Calculate quota details
        if ($isSuper) {
            $usedChars = \App\Models\Translation::whereMonth('created_at', now()->month)
                                                ->whereYear('created_at', now()->year)->sum('characters');
            $limitChars = \App\Models\Organisation::sum('monthly_char_limit');
            if ($limitChars == 0) $limitChars = 10000000; // fallback if no limit
        } else {
            $usedChars = $org ? $org->monthlyCharactersUsed() : 0;
            $limitChars = $org ? $org->monthly_char_limit : 2000000;
        }
        $percent = $limitChars > 0 ? min(round(($usedChars / $limitChars) * 100), 100) : 0;

        // Fetch real translation counts for the last 6 months
        $months = [];
        $counts = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $months[] = $date->format('M');
            
            $query = \App\Models\Translation::whereMonth('created_at', $date->month)
                                            ->whereYear('created_at', $date->year);
            if (!$isSuper) {
                $query->where('organisation_id', $user->organisation_id);
            }
            $counts[] = $query->count();
        }
    @endphp

    <style>
        /* ── Custom Premium Dashboard Styling ── */
        .dash-card {
            background: #ffffff;
            border: 1px solid #cbd5e1;
            border-radius: 20px;
            box-shadow: 0 4px 18px rgba(0, 0, 0, 0.06);
            transition: transform 0.25s ease, box-shadow 0.25s ease;
        }
        .dash-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(255, 71, 87, 0.08);
        }
        .stat-icon-wrapper {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
        }
        .bg-light-red {
            background: #ffe8ea;
            color: #ff4757;
        }
        .bg-light-amber {
            background: #fef3c7;
            color: #d97706;
        }
        .bg-light-emerald {
            background: #d1fae5;
            color: #059669;
        }
        .bg-light-purple {
            background: #f3e8ff;
            color: #7c3aed;
        }

        /* Dark mode overrides */
        body:not(.light-mode) .dash-card {
            background: #161e2e !important;
            border-color: rgba(255, 255, 255, 0.08) !important;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.25);
        }
        body:not(.light-mode) .text-gray-900 {
            color: #ffffff !important;
        }
        body:not(.light-mode) .text-gray-500 {
            color: #94a3b8 !important;
        }
        body:not(.light-mode) .text-gray-800 {
            color: #f5e6e8 !important;
        }
    </style>

    <div class="fade-in space-y-6">

        {{-- Top Welcome Banner --}}
        <div class="dash-card overflow-hidden border-l-8" style="border-left-color: #ff4757;">
            <div class="flex items-center p-6 bg-[#ffe8ea] dark:bg-[#340b10]">
                <div class="flex-1">
                    <div class="flex items-center gap-3 mb-1">
                        <span class="text-2xl">⚡</span>
                        <h2 class="text-xl font-bold text-gray-900 dark:text-white">
                            {{ $isSuper ? 'Super Admin Control Centre' : ($org ? $org->name . ' — Admin Dashboard' : 'Admin Panel') }}
                        </h2>
                    </div>
                    <p class="text-gray-500 dark:text-red-200 text-sm">
                        {{ $isSuper ? 'Complete governance of organizations, user accounts, system metrics, and translation logs.' : 'Monitor live API characters usage, configure glossary terms, and manage team members.' }}
                    </p>
                </div>
                <div class="hidden sm:flex gap-2">
                    @if($isSuper)
                    <a href="{{ route('admin.organisations.create') }}" class="btn-primary text-sm">
                        ➕ New Org
                    </a>
                    @endif
                    <a href="{{ route('admin.users.create') }}" class="btn-secondary text-sm hover:border-[#c1121f] hover:text-[#c1121f] transition-all">
                        👤 Add User
                    </a>
                </div>
            </div>
        </div>

        {{-- Stats Grid --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-5">
            <div class="dash-card p-5">
                <div class="flex items-center justify-between mb-4">
                    <div class="stat-icon-wrapper bg-light-purple">
                        <i class="fas fa-building"></i>
                    </div>
                    <span class="badge {{ $isSuper ? 'badge-success' : 'badge-warning' }} text-xs">
                        {{ $isSuper ? 'Active: ' . $stats['active_orgs'] : 'Portal' }}
                    </span>
                </div>
                <div class="text-3xl font-extrabold text-gray-900">{{ number_format($stats['total_orgs']) }}</div>
                <div class="text-xs text-gray-500 mt-1 uppercase tracking-wider font-semibold">Organisations</div>
            </div>

            <div class="dash-card p-5">
                <div class="flex items-center justify-between mb-4">
                    <div class="stat-icon-wrapper bg-light-red">
                        <i class="fas fa-users"></i>
                    </div>
                    <span class="badge badge-success text-xs">Active Team</span>
                </div>
                <div class="text-3xl font-extrabold text-gray-900">{{ number_format($stats['total_users']) }}</div>
                <div class="text-xs text-gray-500 mt-1 uppercase tracking-wider font-semibold">Total Users</div>
            </div>

            <div class="dash-card p-5">
                <div class="flex items-center justify-between mb-4">
                    <div class="stat-icon-wrapper bg-light-amber">
                        <i class="fas fa-file-alt"></i>
                    </div>
                    <span class="badge badge-warning text-xs">Month: {{ number_format($stats['this_month']) }}</span>
                </div>
                <div class="text-3xl font-extrabold text-gray-900">{{ number_format($stats['total_translations']) }}</div>
                <div class="text-xs text-gray-500 mt-1 uppercase tracking-wider font-semibold">Translations</div>
            </div>

            <div class="dash-card p-5">
                <div class="flex items-center justify-between mb-4">
                    <div class="stat-icon-wrapper bg-light-emerald">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <span class="badge badge-error text-xs">Failed: {{ number_format($stats['failed']) }}</span>
                </div>
                <div class="text-3xl font-extrabold text-gray-900">{{ number_format($stats['completed']) }}</div>
                <div class="text-xs text-gray-500 mt-1 uppercase tracking-wider font-semibold">Completed</div>
            </div>
        </div>

        {{-- Charts Row (Graph and Gauge) --}}
        <div class="grid lg:grid-cols-3 gap-6">
            {{-- Translation Activity Chart --}}
            <div class="dash-card p-5 lg:col-span-2">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h3 class="font-bold text-gray-800 dark:text-white">Activity Chart</h3>
                        <p class="text-xs text-gray-400">Total translations processed over the last 6 months</p>
                    </div>
                    <span class="text-xs font-semibold px-2.5 py-1 bg-gray-100 dark:bg-red-950/40 text-gray-600 dark:text-red-300 rounded-lg">This Year</span>
                </div>
                <div class="w-full h-64">
                    <canvas id="activityChart"></canvas>
                </div>
            </div>

            {{-- Monthly Quota Gauge --}}
            <div class="dash-card p-5 flex flex-col justify-between">
                <div>
                    <h3 class="font-bold text-gray-800 dark:text-white">Monthly Quota</h3>
                    <p class="text-xs text-gray-400">API Characters consumed of allocated monthly limit</p>
                </div>
                <div class="relative flex items-center justify-center h-40">
                    <canvas id="quotaGauge"></canvas>
                    <div class="absolute text-center mt-6">
                        <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ $percent }}%</div>
                        <div class="text-[10px] text-gray-400 uppercase tracking-wider">Consumed</div>
                    </div>
                </div>
                <div class="pt-4 border-t border-gray-100 dark:border-red-950/20 text-center">
                    <div class="text-xs font-semibold text-gray-800 dark:text-white">
                        {{ number_format($usedChars) }} / {{ number_format($limitChars) }} characters
                    </div>
                    <div class="text-[10px] text-gray-400 mt-0.5">Quota resets on the 1st of next month</div>
                </div>
            </div>
        </div>

        {{-- Lists Grid --}}
        <div class="grid lg:grid-cols-2 gap-6">
            {{-- Recent Organisations --}}
            <div class="dash-card overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 dark:border-red-950/20 flex items-center justify-between">
                    <h3 class="font-bold text-gray-800 dark:text-white">Recent Organisations</h3>
                    @if($isSuper)
                    <a href="{{ route('admin.organisations.index') }}" class="text-xs font-semibold text-[#c1121f] hover:underline">View all →</a>
                    @endif
                </div>
                @if($recentOrgs->isEmpty())
                <div class="px-6 py-12 text-center text-gray-400">No organisations yet.</div>
                @else
                <div class="divide-y divide-gray-100 dark:divide-red-950/10">
                    @foreach($recentOrgs as $org)
                    <div class="px-6 py-4 flex items-center gap-4 hover:bg-red-50/20 dark:hover:bg-red-950/5 transition-colors">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center text-white font-bold text-sm flex-shrink-0"
                             style="background: linear-gradient(135deg, #c1121f, #780000);">
                            {{ strtoupper(substr($org->name, 0, 2)) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            @if($isSuper)
                            <a href="{{ route('admin.organisations.show', $org) }}" class="font-semibold text-gray-800 dark:text-white hover:text-[#ff4757] transition-colors block truncate">
                                {{ $org->name }}
                            </a>
                            @else
                            <div class="font-semibold text-gray-800 dark:text-white block truncate">{{ $org->name }}</div>
                            @endif
                            <div class="text-xs text-gray-400">{{ $org->users_count }} users · {{ number_format($org->translations_count) }} translations</div>
                        </div>
                        @if($org->is_active)
                        <span class="badge badge-success text-[10px]">Active</span>
                        @else
                        <span class="badge badge-error text-[10px]">Suspended</span>
                        @endif
                    </div>
                    @endforeach
                </div>
                @endif
            </div>

            {{-- Recent Translations --}}
            <div class="dash-card overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 dark:border-red-950/20 flex items-center justify-between">
                    <h3 class="font-bold text-gray-800 dark:text-white">Recent Translations (System-wide)</h3>
                </div>
                @if($recentTranslations->isEmpty())
                <div class="px-6 py-12 text-center text-gray-400">No translations processed yet.</div>
                @else
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-gray-100 dark:border-red-950/20 bg-gray-50 dark:bg-red-950/10">
                                <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 dark:text-red-300 uppercase tracking-wide">Org</th>
                                <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 dark:text-red-300 uppercase tracking-wide">Source</th>
                                <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 dark:text-red-300 uppercase tracking-wide">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-red-950/10">
                            @foreach($recentTranslations as $t)
                            <tr class="hover:bg-red-50/20 dark:hover:bg-red-950/5 transition-colors">
                                <td class="px-6 py-3 text-xs font-medium text-gray-500 dark:text-red-200 truncate max-w-[120px]">{{ $t->organisation?->name ?? '—' }}</td>
                                <td class="px-6 py-3 text-gray-700 dark:text-gray-300 font-medium truncate max-w-[200px]">{{ $t->source_text }}</td>
                                <td class="px-6 py-3">
                                    <span class="badge badge-{{ $t->status === 'completed' ? 'success' : ($t->status === 'failed' ? 'error' : 'warning') }} text-[10px]">
                                        {{ ucfirst($t->status) }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
            </div>
        </div>

        {{-- Quick Links --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            @if($isSuper)
            <a href="{{ route('admin.organisations.index') }}" class="dash-card p-5 flex items-center gap-4 hover:shadow-lg transition-all group">
                <div class="stat-icon-wrapper bg-light-purple text-2xl"><i class="fas fa-building"></i></div>
                <div>
                    <div class="font-bold text-gray-800 dark:text-white group-hover:text-[#ff4757] transition-colors">Organisations</div>
                    <div class="text-[10px] text-gray-400">Manage all orgs</div>
                </div>
            </a>
            @endif
            <a href="{{ route('admin.users.index') }}" class="dash-card p-5 flex items-center gap-4 hover:shadow-lg transition-all group">
                <div class="stat-icon-wrapper bg-light-red text-2xl"><i class="fas fa-users"></i></div>
                <div>
                    <div class="font-bold text-gray-800 dark:text-white group-hover:text-[#ff4757] transition-colors">Users</div>
                    <div class="text-[10px] text-gray-400">Manage all users</div>
                </div>
            </a>
            <a href="{{ route('translations.index') }}" class="dash-card p-5 flex items-center gap-4 hover:shadow-lg transition-all group">
                <div class="stat-icon-wrapper bg-light-amber text-2xl"><i class="fas fa-clipboard"></i></div>
                <div>
                    <div class="font-bold text-gray-800 dark:text-white group-hover:text-[#ff4757] transition-colors">Translations</div>
                    <div class="text-[10px] text-gray-400">Translation logs</div>
                </div>
            </a>
            <a href="{{ route('glossary.index') }}" class="dash-card p-5 flex items-center gap-4 hover:shadow-lg transition-all group">
                <div class="stat-icon-wrapper bg-light-emerald text-2xl"><i class="fas fa-book"></i></div>
                <div>
                    <div class="font-bold text-gray-800 dark:text-white group-hover:text-[#ff4757] transition-colors">Glossary</div>
                    <div class="text-[10px] text-gray-400">Glossary terms</div>
                </div>
            </a>
        </div>
    </div>

    {{-- ChartJS Import & Scripts --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Chart.js global defaults for clean styling
            Chart.defaults.font.family = "'Inter', sans-serif";
            Chart.defaults.color = '#94a3b8';

            const isDarkMode = !document.body.classList.contains('light-mode');

            // 1. Activity Chart (Line Graph)
            const ctxActivity = document.getElementById('activityChart').getContext('2d');
            
            // Create a gorgeous red gradient fill
            const redGradient = ctxActivity.createLinearGradient(0, 0, 0, 240);
            redGradient.addColorStop(0, 'rgba(255, 71, 87, 0.25)');
            redGradient.addColorStop(1, 'rgba(255, 71, 87, 0.00)');

            const monthsData = @json($months);
            const countsData = @json($counts);

            new Chart(ctxActivity, {
                type: 'line',
                data: {
                    labels: monthsData,
                    datasets: [{
                        label: 'Translations Count',
                        data: countsData,
                        borderColor: '#ff4757',
                        borderWidth: 3.5,
                        backgroundColor: redGradient,
                        fill: true,
                        tension: 0.45,
                        pointBackgroundColor: '#ff4757',
                        pointBorderColor: '#ffffff',
                        pointBorderWidth: 2,
                        pointRadius: 5,
                        pointHoverRadius: 7
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        x: {
                            grid: { display: false },
                            ticks: { font: { size: 10, weight: 500 } }
                        },
                        y: {
                            grid: { 
                                color: isDarkMode ? 'rgba(255, 255, 255, 0.05)' : 'rgba(0, 0, 0, 0.1)',
                                drawTicks: false 
                            },
                            ticks: { 
                                font: { size: 10, weight: 500 },
                                precision: 0
                            }
                        }
                    }
                }
            });

            // 2. Quota Gauge Chart (Doughnut arch)
            const ctxGauge = document.getElementById('quotaGauge').getContext('2d');
            const percent = {{ $percent }};
            const remaining = 100 - percent;

            new Chart(ctxGauge, {
                type: 'doughnut',
                data: {
                    datasets: [{
                        data: [percent, remaining],
                        backgroundColor: [
                            '#ff4757',
                            isDarkMode ? 'rgba(255, 255, 255, 0.1)' : '#cbd5e1'
                        ],
                        borderWidth: 0,
                        hoverBackgroundColor: ['#ff6b81', isDarkMode ? 'rgba(255, 255, 255, 0.12)' : '#b8c2cc']
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    circumference: 180,
                    rotation: 270,
                    cutout: '80%',
                    plugins: {
                        legend: { display: false },
                        tooltip: { enabled: false }
                    }
                }
            });
        });
    </script>
</x-app-layout>
