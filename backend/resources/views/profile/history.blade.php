<x-app-layout>
    <x-slot name="header">My Translation History</x-slot>

    <style>
        /* ── History Page Styles ── */
        .hist-stat-card {
            background: rgba(255,255,255,0.03);
            border: 1px solid rgba(255,255,255,0.06);
            border-radius: 16px;
            padding: 20px 24px;
            transition: all 0.2s;
        }
        .hist-stat-card:hover { border-color: rgba(79,70,229,0.3); transform: translateY(-2px); }

        .hist-row { transition: background 0.15s; }
        .hist-row:hover { background: rgba(79,70,229,0.06); }

        .lang-chip {
            display: inline-flex; align-items: center; gap: 5px;
            padding: 3px 10px; border-radius: 99px; font-size: 11px; font-weight: 600;
        }
        .lang-chip-from { background: rgba(100,116,139,0.15); color: #94a3b8; }
        .lang-chip-to   { background: rgba(79,70,229,0.15);  color: #818cf8; }

        .avatar-ring {
            width: 60px; height: 60px; border-radius: 50%; object-fit: cover;
            box-shadow: 0 0 0 3px rgba(79,70,229,0.35), 0 8px 24px rgba(0,0,0,0.3);
            flex-shrink: 0;
        }

        /* Light mode overrides */
        body.light-mode .hist-stat-card {
            background: #fff; border-color: #e2e8f0;
            box-shadow: 0 2px 12px rgba(0,0,0,0.04);
        }
        body.light-mode .hist-stat-card:hover { border-color: #a5b4fc; }
        body.light-mode .hist-row:hover { background: rgba(79,70,229,0.04); }
        body.light-mode .lang-chip-from { background: #f1f5f9; color: #475569; }
        body.light-mode .lang-chip-to   { background: #ede9fe; color: #4f46e5; }
    </style>

    <div class="fade-in space-y-6">

        {{-- ── Profile Header Card ── --}}
        <div class="card p-6">
            <div class="flex flex-col sm:flex-row items-start sm:items-center gap-5">
                {{-- Avatar --}}
                <a href="{{ route('profile.edit') }}" title="Edit Profile" class="relative group flex-shrink-0">
                    <img src="{{ auth()->user()->avatarUrl() }}"
                         alt="{{ auth()->user()->name }}"
                         class="avatar-ring">
                    <div class="absolute inset-0 rounded-full bg-black/50 opacity-0 group-hover:opacity-100 flex items-center justify-center transition-all text-white text-[10px] font-bold">
                        Edit
                    </div>
                </a>

                {{-- User info --}}
                <div class="flex-1 min-w-0">
                    <h2 class="text-xl font-bold text-white truncate">{{ auth()->user()->name }}</h2>
                    <p class="text-sm text-slate-500 truncate">{{ auth()->user()->email }}</p>
                    @if(auth()->user()->organisation)
                    <span class="mt-1.5 inline-flex items-center gap-1 text-xs font-medium text-blue-400 bg-blue-900/20 border border-blue-800/30 rounded-full px-3 py-0.5">
                        🏢 {{ auth()->user()->organisation->name }}
                    </span>
                    @endif
                </div>

                {{-- Stats --}}
                <div class="flex items-center gap-4 flex-shrink-0">
                    <div class="text-center">
                        <div class="text-2xl font-bold text-white">{{ $translations->total() }}</div>
                        <div class="text-xs text-slate-500 mt-0.5">Translation{{ $translations->total() !== 1 ? 's' : '' }}</div>
                    </div>
                    <div class="h-10 w-px bg-white/10"></div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-emerald-400">{{ $translations->getCollection()->where('status','completed')->count() }}</div>
                        <div class="text-xs text-slate-500 mt-0.5">Completed</div>
                    </div>
                </div>

                <a href="{{ route('profile.edit') }}" class="btn-secondary text-sm whitespace-nowrap ml-auto">
                    ⚙️ Edit Profile
                </a>
            </div>
        </div>

        {{-- ── Quick Stats Row ── --}}
        @php
            $all       = $translations->getCollection();
            $completed = $all->where('status','completed')->count();
            $failed    = $all->where('status','failed')->count();
            $pending   = $all->where('status','pending')->count();
            $totalChars = $all->sum('characters');
        @endphp
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
            <div class="hist-stat-card">
                <div class="text-2xl mb-1">✅</div>
                <div class="text-xl font-bold text-emerald-400">{{ $completed }}</div>
                <div class="text-xs text-slate-500 mt-0.5">Completed</div>
            </div>
            <div class="hist-stat-card">
                <div class="text-2xl mb-1">❌</div>
                <div class="text-xl font-bold text-red-400">{{ $failed }}</div>
                <div class="text-xs text-slate-500 mt-0.5">Failed</div>
            </div>
            <div class="hist-stat-card">
                <div class="text-2xl mb-1">⏳</div>
                <div class="text-xl font-bold text-amber-400">{{ $pending }}</div>
                <div class="text-xs text-slate-500 mt-0.5">Pending</div>
            </div>
            <div class="hist-stat-card">
                <div class="text-2xl mb-1">🔤</div>
                <div class="text-xl font-bold text-blue-400">{{ number_format($totalChars) }}</div>
                <div class="text-xs text-slate-500 mt-0.5">Chars Translated</div>
            </div>
        </div>

        {{-- ── Filter Bar ── --}}
        <form method="GET" action="{{ route('profile.history') }}" class="card px-5 py-4 flex flex-wrap items-center gap-3">
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Search your translations…"
                   class="input-field flex-1 min-w-[180px]" style="max-width:300px;">
            <select name="status" class="input-field" style="max-width:160px;">
                <option value="">All Statuses</option>
                <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>✅ Completed</option>
                <option value="failed"    {{ request('status') === 'failed'    ? 'selected' : '' }}>❌ Failed</option>
                <option value="pending"   {{ request('status') === 'pending'   ? 'selected' : '' }}>⏳ Pending</option>
            </select>
            <button type="submit" class="btn-primary text-sm">Filter</button>
            @if(request()->hasAny(['search','status']))
            <a href="{{ route('profile.history') }}" class="btn-secondary text-sm">Clear</a>
            @endif
            <div class="ml-auto text-sm text-slate-400">{{ $translations->total() }} record{{ $translations->total() !== 1 ? 's' : '' }}</div>
        </form>

        {{-- ── Translation Table ── --}}
        <div class="card overflow-hidden">
            @if($translations->isEmpty())
            <div class="px-6 py-20 text-center">
                <div class="text-6xl mb-4">📭</div>
                <h3 class="text-lg font-semibold text-white mb-2">No translations yet</h3>
                <p class="text-slate-500 text-sm mb-5">
                    @if(request()->hasAny(['search','status']))
                        No translations match your filters. Try clearing them.
                    @else
                        You haven't made any translations yet. Start one now!
                    @endif
                </p>
                <a href="{{ route('translations.create') }}" class="btn-primary text-sm">✍️ Start Translating →</a>
            </div>
            @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-white/5 bg-white/[0.02]">
                            <th class="text-left px-6 py-3.5 text-xs font-semibold text-slate-400 uppercase tracking-wide">Translation</th>
                            <th class="text-left px-6 py-3.5 text-xs font-semibold text-slate-400 uppercase tracking-wide">Languages</th>
                            <th class="text-left px-6 py-3.5 text-xs font-semibold text-slate-400 uppercase tracking-wide">Output Preview</th>
                            <th class="text-left px-6 py-3.5 text-xs font-semibold text-slate-400 uppercase tracking-wide">Chars</th>
                            <th class="text-left px-6 py-3.5 text-xs font-semibold text-slate-400 uppercase tracking-wide">Status</th>
                            <th class="text-left px-6 py-3.5 text-xs font-semibold text-slate-400 uppercase tracking-wide">Date</th>
                            <th class="px-6 py-3.5"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/[0.04]">
                        @foreach($translations as $t)
                        <tr class="hist-row">
                            <td class="px-6 py-4 max-w-[220px]">
                                @if($t->source_label)
                                <div class="text-[10px] font-bold text-indigo-400 uppercase tracking-wide mb-0.5">{{ $t->source_label }}</div>
                                @endif
                                <a href="{{ route('translations.show', $t) }}"
                                   class="text-white font-medium hover:text-indigo-400 transition-colors leading-snug block"
                                   title="{{ $t->source_text }}">
                                    {{ Str::limit($t->source_text, 60) }}
                                </a>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex flex-col gap-1">
                                    <span class="lang-chip lang-chip-from">{{ \App\Services\TranslationService::getLanguageName($t->source_lang) }}</span>
                                    <span class="lang-chip lang-chip-to">→ {{ \App\Services\TranslationService::getLanguageName($t->target_lang) }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-slate-300 max-w-[200px]" style="font-family:'Noto Sans Devanagari',sans-serif;">
                                {{ $t->translated_text ? Str::limit($t->translated_text, 50) : '—' }}
                            </td>
                            <td class="px-6 py-4 text-slate-400 font-mono text-xs whitespace-nowrap">{{ number_format($t->characters) }}</td>
                            <td class="px-6 py-4">
                                <span class="badge badge-{{ $t->status === 'completed' ? 'success' : ($t->status === 'failed' ? 'error' : 'warning') }}">
                                    {{ ucfirst($t->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-slate-500 whitespace-nowrap text-xs">
                                <div>{{ $t->created_at->format('d M Y') }}</div>
                                <div class="text-slate-600">{{ $t->created_at->format('h:i A') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <a href="{{ route('translations.show', $t) }}"
                                   class="inline-flex items-center gap-1 text-indigo-400 hover:text-indigo-300 font-semibold text-xs transition-colors">
                                    View <span>→</span>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($translations->hasPages())
            <div class="px-6 py-4 border-t border-white/5">
                {{ $translations->links('vendor.pagination.simple') }}
            </div>
            @endif
            @endif
        </div>

        {{-- Privacy notice --}}
        <p class="text-center text-xs text-slate-600 pb-2">
            🔒 Your translation history is private — only you and administrators can view it.
        </p>
    </div>
</x-app-layout>
