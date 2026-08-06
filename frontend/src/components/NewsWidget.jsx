import React, { useState, useEffect } from 'react';
import api from '../api';
import { Newspaper, X, Search, Globe, Landmark } from 'lucide-react';

export default function NewsWidget({ isOpen, onOpen, onClose }) {
  const [activeTab, setActiveTab] = useState('india');
  const [search, setSearch] = useState('');
  const [news, setNews] = useState({ india: [], global: [] });

  useEffect(() => { fetchNews(); }, []);

  const fetchNews = async () => {
    try {
      const res = await api.get('/news');
      setNews(res.data);
    } catch {
      setNews({
        india: [
          { id: 1, title: 'RBI keeps repo rate unchanged at 6.5% for 10th consecutive meeting', summary: 'Monetary Policy Committee retains focus on inflation alignment.', source: 'Financial Express', time: '10m ago' },
          { id: 2, title: 'GST collections cross ₹1.82 lakh crore in latest monthly data', summary: 'Robust economic growth drives revenue across manufacturing states.', source: 'Economic Times', time: '1h ago' },
          { id: 3, title: 'Sensex hits fresh record high led by banking and IT blue-chips', summary: 'Domestic institutional investors remain bullish on infrastructure.', source: 'Moneycontrol', time: '2h ago' },
        ],
        global: [
          { id: 101, title: 'Federal Reserve hints at gradual rate cuts amidst cooling inflation', summary: 'US labor markets show resilience while headline inflation figures moderate.', source: 'Bloomberg', time: '30m ago' },
          { id: 102, title: 'Asian markets rally as Tech indices surge across Tokyo and Singapore', summary: 'Semiconductor demand drives high volume trading.', source: 'Reuters', time: '3h ago' },
        ],
      });
    }
  };

  const currentItems = (news[activeTab] || []).filter(
    (item) =>
      item.title.toLowerCase().includes(search.toLowerCase()) ||
      item.summary.toLowerCase().includes(search.toLowerCase())
  );

  return (
    <div className="flex flex-col items-end gap-2">
      {/* Panel */}
      {isOpen && (
        <div className="w-80 sm:w-96 rounded-3xl p-5 animate-slide-up space-y-4 mb-2"
             style={{ background: 'rgba(255,255,255,0.92)', backdropFilter: 'blur(20px)', WebkitBackdropFilter: 'blur(20px)', border: '1px solid rgba(255,255,255,0.9)', boxShadow: '-8px -8px 20px rgba(255,255,255,0.9), 8px 8px 20px rgba(182,190,204,0.55)' }}>
          <div className="flex items-center justify-between">
            <div className="flex items-center gap-2">
              <div className="w-8 h-8 rounded-xl flex items-center justify-center bg-amber-50">
                <Newspaper className="w-4 h-4 text-amber-600" />
              </div>
              <div>
                <h3 className="font-bold text-slate-800 text-sm">Live Financial Feed</h3>
                <p className="text-xs text-slate-400">Real-time market news</p>
              </div>
            </div>
            <button onClick={onClose} className="p-1.5 rounded-xl text-slate-400 hover:text-slate-700 hover:bg-slate-100/70 transition-colors">
              <X className="w-4 h-4" />
            </button>
          </div>

          {/* Tabs */}
          <div className="flex gap-1 p-1 rounded-2xl" style={{ background: '#dde2e9', boxShadow: 'inset 2px 2px 6px rgba(163,175,194,0.4), inset -2px -2px 6px rgba(255,255,255,0.7)' }}>
            {[{ key: 'india', label: '🇮🇳 India', Icon: Landmark }, { key: 'global', label: '🌐 Global', Icon: Globe }].map(({ key, label, Icon }) => (
              <button key={key} onClick={() => setActiveTab(key)}
                className={`flex-1 py-2 text-xs font-bold rounded-xl flex items-center justify-center gap-1.5 transition-all ${activeTab === key ? 'bg-white text-teal-700 shadow-sm' : 'text-slate-500 hover:text-slate-700'}`}>
                <Icon className="w-3.5 h-3.5" />{label}
              </button>
            ))}
          </div>

          {/* Search */}
          <div className="relative">
            <Search className="absolute left-3.5 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-slate-400 z-10" />
            <input type="text" placeholder="Search news..." value={search} onChange={(e) => setSearch(e.target.value)}
              className="w-full pl-9 pr-3.5 py-2 text-xs rounded-2xl neu-inset text-slate-700 placeholder-slate-400 font-medium" />
          </div>

          {/* News Items */}
          <div className="max-h-64 overflow-y-auto space-y-2 pr-1">
            {currentItems.map((item) => (
              <div key={item.id} className="p-3.5 rounded-2xl transition-colors space-y-1.5 border border-white/60"
                   style={{ background: 'rgba(255,255,255,0.55)' }}>
                <div className="flex items-center justify-between">
                  <span className="text-[10px] font-bold text-teal-600 uppercase tracking-wide">{item.source}</span>
                  <span className="text-[10px] text-slate-400 font-mono">{item.time}</span>
                </div>
                <h4 className="text-xs font-bold text-slate-800 leading-snug hover:text-teal-700 cursor-pointer transition-colors">{item.title}</h4>
                <p className="text-[11px] text-slate-500 leading-relaxed">{item.summary}</p>
              </div>
            ))}
          </div>
        </div>
      )}

      {/* FAB Icon — expands on hover */}
      <button
        onClick={isOpen ? onClose : onOpen}
        className="group flex items-center justify-end overflow-hidden text-slate-900 rounded-full font-bold text-sm transition-all duration-300 ease-in-out"
        style={{ width: '48px', height: '48px', padding: '0 14px', background: 'linear-gradient(135deg, #fbbf24, #f59e0b)', boxShadow: '0 6px 20px rgba(245,158,11,0.3)', gap: '0px' }}
        onMouseEnter={e => { e.currentTarget.style.width = '192px'; e.currentTarget.style.gap = '8px'; }}
        onMouseLeave={e => { e.currentTarget.style.width = '48px'; e.currentTarget.style.gap = '0px'; }}
        title="Live Finance News"
      >
        <span className="whitespace-nowrap overflow-hidden opacity-0 group-hover:opacity-100 transition-opacity duration-200 text-sm font-bold order-1">
          📰 Finance News
        </span>
        <Newspaper className="w-5 h-5 flex-shrink-0 order-2" />
      </button>
    </div>
  );
}
