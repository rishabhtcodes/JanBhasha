import React, { useEffect, useState } from 'react';
import { useAuth } from '../context/AuthContext';
import api from '../api';
import { LayoutDashboard, Languages, Sparkles, BookOpen, Key, Activity, TrendingUp } from 'lucide-react';

export default function DashboardPage() {
  const { user } = useAuth();
  const [stats, setStats] = useState({ total_translations: 12, characters_translated: 4850, glossary_terms: 5, api_quota_used: 15 });

  useEffect(() => {
    fetchDashboardData();
  }, []);

  const fetchDashboardData = async () => {
    try {
      const res = await api.get('/dashboard');
      if (res.data) {
        setStats(res.data);
      }
    } catch (err) {
      console.warn('Using default dashboard metrics');
    }
  };

  return (
    <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">
      <div className="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
          <h1 className="text-3xl font-extrabold text-white flex items-center gap-3">
            <LayoutDashboard className="w-7 h-7 text-purple-400" />
            Dashboard Overview
          </h1>
          <p className="text-slate-400 text-sm mt-1">Welcome back, <span className="text-white font-semibold">{user?.name}</span>. Here is your translation engine activity.</p>
        </div>
      </div>

      {/* Metrics Row */}
      <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <div className="glass-card p-6 rounded-2xl border border-slate-800 space-y-2">
          <div className="flex items-center justify-between text-slate-400">
            <span className="text-xs font-bold uppercase tracking-wider">Translations</span>
            <Languages className="w-5 h-5 text-indigo-400" />
          </div>
          <p className="text-3xl font-extrabold text-white">{stats.total_translations}</p>
          <p className="text-xs text-emerald-400 flex items-center gap-1 font-semibold">
            <TrendingUp className="w-3.5 h-3.5" /> +24% this week
          </p>
        </div>

        <div className="glass-card p-6 rounded-2xl border border-slate-800 space-y-2">
          <div className="flex items-center justify-between text-slate-400">
            <span className="text-xs font-bold uppercase tracking-wider">Characters Processed</span>
            <Sparkles className="w-5 h-5 text-purple-400" />
          </div>
          <p className="text-3xl font-extrabold text-white">{stats.characters_translated.toLocaleString()}</p>
          <p className="text-xs text-slate-400 font-semibold">Monthly quota: 100,000</p>
        </div>

        <div className="glass-card p-6 rounded-2xl border border-slate-800 space-y-2">
          <div className="flex items-center justify-between text-slate-400">
            <span className="text-xs font-bold uppercase tracking-wider">Glossary Terms</span>
            <BookOpen className="w-5 h-5 text-emerald-400" />
          </div>
          <p className="text-3xl font-extrabold text-white">{stats.glossary_terms}</p>
          <p className="text-xs text-emerald-400 font-semibold">Active terminology rules</p>
        </div>

        <div className="glass-card p-6 rounded-2xl border border-slate-800 space-y-2">
          <div className="flex items-center justify-between text-slate-400">
            <span className="text-xs font-bold uppercase tracking-wider">API Key Usage</span>
            <Key className="w-5 h-5 text-amber-400" />
          </div>
          <p className="text-3xl font-extrabold text-white">{stats.api_quota_used}%</p>
          <div className="w-full bg-slate-900 h-1.5 rounded-full overflow-hidden">
            <div className="bg-amber-400 h-full rounded-full" style={{ width: `${stats.api_quota_used}%` }}></div>
          </div>
        </div>
      </div>

      {/* Activity Card */}
      <div className="glass-panel p-6 rounded-3xl border border-slate-800 space-y-4">
        <h3 className="text-lg font-bold text-white flex items-center gap-2">
          <Activity className="w-5 h-5 text-indigo-400" />
          Recent Translation Operations
        </h3>
        <div className="divide-y divide-slate-800/80">
          <div className="py-3.5 flex items-center justify-between">
            <div>
              <p className="text-sm font-semibold text-white">English → Hindi</p>
              <p className="text-xs text-slate-400">"JanBhasha provides seamless Indic translation AI..."</p>
            </div>
            <span className="text-xs text-slate-500 font-mono">2 mins ago</span>
          </div>
          <div className="py-3.5 flex items-center justify-between">
            <div>
              <p className="text-sm font-semibold text-white">English → Tamil</p>
              <p className="text-xs text-slate-400">"Government portal localization documentation..."</p>
            </div>
            <span className="text-xs text-slate-500 font-mono">1 hour ago</span>
          </div>
        </div>
      </div>
    </div>
  );
}
