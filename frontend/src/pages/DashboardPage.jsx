import React, { useEffect, useState } from 'react';
import { useAuth } from '../context/AuthContext';
import api from '../api';
import { LayoutDashboard, Languages, Sparkles, BookOpen, Key, Activity, TrendingUp, ArrowUpRight } from 'lucide-react';

export default function DashboardPage() {
  const { user } = useAuth();
  const [stats, setStats] = useState({ total_translations: 42, characters_translated: 18450, glossary_terms: 8, api_quota_used: 22 });

  useEffect(() => { fetchDashboardData(); }, []);

  const fetchDashboardData = async () => {
    try {
      const res = await api.get('/dashboard');
      if (res.data) setStats(res.data);
    } catch (err) {
      console.warn('Using default dashboard metrics');
    }
  };

  const metrics = [
    {
      label: 'Translations',
      value: stats.total_translations,
      sub: '+24% this week',
      subColor: 'text-emerald-600',
      icon: Languages,
      iconBg: 'bg-teal-50',
      iconColor: 'text-teal-600',
    },
    {
      label: 'Characters Processed',
      value: stats.characters_translated.toLocaleString(),
      sub: 'Monthly quota: 100,000',
      subColor: 'text-slate-400',
      icon: Sparkles,
      iconBg: 'bg-violet-50',
      iconColor: 'text-violet-600',
    },
    {
      label: 'Glossary Terms',
      value: stats.glossary_terms,
      sub: 'Active terminology rules',
      subColor: 'text-emerald-600',
      icon: BookOpen,
      iconBg: 'bg-emerald-50',
      iconColor: 'text-emerald-600',
    },
    {
      label: 'API Key Usage',
      value: `${stats.api_quota_used}%`,
      sub: null,
      icon: Key,
      iconBg: 'bg-amber-50',
      iconColor: 'text-amber-600',
      isProgress: true,
      progressVal: stats.api_quota_used,
    },
  ];

  const recentActivity = [
    { from: 'English', to: 'Hindi', text: '"JanBhasha provides seamless Indic translation AI..."', time: '2 mins ago', color: 'text-teal-600', bg: 'bg-teal-50' },
    { from: 'English', to: 'Tamil', text: '"Government portal localization documentation..."', time: '1 hour ago', color: 'text-violet-600', bg: 'bg-violet-50' },
    { from: 'English', to: 'Bengali', text: '"Official health circular regarding vaccination..."', time: '3 hours ago', color: 'text-sky-600', bg: 'bg-sky-50' },
  ];

  return (
    <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">
      {/* Header */}
      <div className="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
          <h1 className="text-2xl sm:text-3xl font-extrabold text-slate-900 flex items-center gap-3">
            <div className="w-10 h-10 rounded-2xl flex items-center justify-center bg-violet-50">
              <LayoutDashboard className="w-5 h-5 text-violet-600" />
            </div>
            Dashboard Overview
          </h1>
          <p className="text-slate-500 text-sm mt-1.5">
            Welcome back, <span className="text-slate-800 font-bold">{user?.name}</span>. Here is your translation engine activity.
          </p>
        </div>
        <button className="flex items-center gap-1.5 text-xs font-semibold text-teal-600 hover:text-teal-700 px-4 py-2 rounded-xl bg-teal-50 border border-teal-100 transition-colors">
          <Activity className="w-3.5 h-3.5" />
          View Full Report
        </button>
      </div>

      {/* Metric Cards */}
      <div className="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
        {metrics.map(({ label, value, sub, subColor, icon: Icon, iconBg, iconColor, isProgress, progressVal }) => (
          <div key={label} className="neu-card-sm p-5 space-y-3">
            <div className="flex items-center justify-between">
              <span className="text-xs font-bold text-slate-400 uppercase tracking-wider leading-tight">{label}</span>
              <div className={`w-9 h-9 rounded-xl flex items-center justify-center ${iconBg}`}>
                <Icon className={`w-4 h-4 ${iconColor}`} />
              </div>
            </div>
            <p className="text-2xl sm:text-3xl font-extrabold text-slate-900">{value}</p>
            {isProgress ? (
              <div className="w-full h-1.5 rounded-full overflow-hidden" style={{ background: '#dde2e9', boxShadow: 'inset 1px 1px 3px rgba(163,175,194,0.4)' }}>
                <div className="h-full rounded-full transition-all" style={{ width: `${progressVal}%`, background: 'linear-gradient(90deg, #0d9488, #14b8a6)' }} />
              </div>
            ) : (
              <p className={`text-xs font-semibold ${subColor} flex items-center gap-1`}>
                {sub?.includes('+') && <TrendingUp className="w-3 h-3" />}
                {sub}
              </p>
            )}
          </div>
        ))}
      </div>

      {/* Recent Activity */}
      <div className="neu-card p-6 space-y-5">
        <div className="flex items-center justify-between">
          <h3 className="font-bold text-slate-800 flex items-center gap-2">
            <Activity className="w-5 h-5 text-teal-500" />
            Recent Translation Operations
          </h3>
          <button className="text-xs text-teal-600 font-semibold hover:underline flex items-center gap-1">
            See All <ArrowUpRight className="w-3.5 h-3.5" />
          </button>
        </div>

        <div className="space-y-3">
          {recentActivity.map(({ from, to, text, time, color, bg }) => (
            <div key={time + from} className="flex items-center gap-4 p-3.5 rounded-2xl hover:bg-white/60 transition-colors glass-card">
              <div className={`w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0 ${bg}`}>
                <Languages className={`w-4 h-4 ${color}`} />
              </div>
              <div className="flex-1 min-w-0">
                <p className="text-sm font-bold text-slate-800">{from} → {to}</p>
                <p className="text-xs text-slate-400 truncate">{text}</p>
              </div>
              <span className="text-xs text-slate-400 font-mono flex-shrink-0">{time}</span>
            </div>
          ))}
        </div>
      </div>
    </div>
  );
}
