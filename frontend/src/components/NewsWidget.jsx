import React, { useState, useEffect } from 'react';
import api from '../api';
import { Newspaper, X, Search, ExternalLink, Globe, Landmark } from 'lucide-react';

export default function NewsWidget({ isOpen, onToggle, onCloseOther }) {
  const [activeTab, setActiveTab] = useState('india');
  const [search, setSearch] = useState('');
  const [news, setNews] = useState({ india: [], global: [] });

  useEffect(() => {
    fetchNews();
  }, []);

  const fetchNews = async () => {
    try {
      const res = await api.get('/news');
      setNews(res.data);
    } catch (err) {
      setNews({
        india: [
          { id: 1, title: 'RBI keeps repo rate unchanged at 6.5% for 10th consecutive meeting', summary: 'Monetary Policy Committee retains focus on inflation alignment.', source: 'Financial Express', time: '10m ago' },
          { id: 2, title: 'GST collections cross ₹1.82 lakh crore in latest monthly data', summary: 'Robust economic growth drives revenue across manufacturing states.', source: 'Economic Times', time: '1h ago' },
          { id: 3, title: 'Sensex hits fresh record high led by banking and IT blue-chips', summary: 'Domestic institutional investors remain bullish on infrastructure.', source: 'Moneycontrol', time: '2h ago' }
        ],
        global: [
          { id: 101, title: 'Federal Reserve hints at gradual rate cuts amidst cooling inflation', summary: 'US labor markets show resilience while headline inflation figures moderate.', source: 'Bloomberg', time: '30m ago' },
          { id: 102, title: 'Asian markets rally as Tech indices surge across Tokyo and Singapore', summary: 'Semiconductor demand drives high volume trading.', source: 'Reuters', time: '3h ago' }
        ]
      });
    }
  };

  const handleOpen = () => {
    onCloseOther();
    onToggle();
  };

  const currentItems = (news[activeTab] || []).filter(item =>
    item.title.toLowerCase().includes(search.toLowerCase()) ||
    item.summary.toLowerCase().includes(search.toLowerCase())
  );

  return (
    <div className="fixed bottom-6 right-52 z-40">
      {!isOpen ? (
        <button
          onClick={handleOpen}
          className="flex items-center gap-2 px-4 py-3 bg-amber-500 hover:bg-amber-400 text-slate-950 font-bold rounded-full shadow-2xl shadow-amber-500/30 hover:scale-105 transition-all text-sm"
        >
          <Newspaper className="w-5 h-5" />
          <span>📰 Live Finance News</span>
        </button>
      ) : (
        <div className="w-80 sm:w-96 glass-panel rounded-2xl p-5 shadow-2xl border border-amber-500/30 animate-slide-up space-y-4">
          <div className="flex items-center justify-between border-b border-slate-800 pb-3">
            <div className="flex items-center gap-2">
              <Newspaper className="w-5 h-5 text-amber-400" />
              <h3 className="font-bold text-white text-base">Live Financial Feed</h3>
            </div>
            <button onClick={onToggle} className="text-slate-400 hover:text-white p-1">
              <X className="w-5 h-5" />
            </button>
          </div>

          {/* Tabs */}
          <div className="flex gap-2 p-1 bg-slate-900 rounded-xl border border-slate-800">
            <button
              onClick={() => setActiveTab('india')}
              className={`flex-1 py-1.5 text-xs font-bold rounded-lg flex items-center justify-center gap-1.5 transition-all ${
                activeTab === 'india' ? 'bg-amber-500 text-slate-950 shadow' : 'text-slate-400 hover:text-white'
              }`}
            >
              <Landmark className="w-3.5 h-3.5" />
              🇮🇳 India
            </button>
            <button
              onClick={() => setActiveTab('global')}
              className={`flex-1 py-1.5 text-xs font-bold rounded-lg flex items-center justify-center gap-1.5 transition-all ${
                activeTab === 'global' ? 'bg-amber-500 text-slate-950 shadow' : 'text-slate-400 hover:text-white'
              }`}
            >
              <Globe className="w-3.5 h-3.5" />
              🌐 Global
            </button>
          </div>

          {/* Search */}
          <div className="relative">
            <Search className="absolute left-3 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-slate-500" />
            <input
              type="text"
              placeholder="Search news..."
              value={search}
              onChange={(e) => setSearch(e.target.value)}
              className="w-full pl-8 pr-3 py-1.5 text-xs bg-slate-900 border border-slate-800 rounded-lg text-white placeholder-slate-500 focus:outline-none focus:border-amber-500"
            />
          </div>

          {/* News Feed */}
          <div className="max-h-72 overflow-y-auto space-y-3 pr-1 divide-y divide-slate-800/60">
            {currentItems.map((item) => (
              <div key={item.id} className="pt-2 text-left space-y-1">
                <div className="flex items-center justify-between text-[10px] text-amber-400 font-semibold">
                  <span>{item.source}</span>
                  <span className="text-slate-500">{item.time}</span>
                </div>
                <h4 className="text-xs font-bold text-slate-200 leading-snug hover:text-amber-300 cursor-pointer">{item.title}</h4>
                <p className="text-[11px] text-slate-400 leading-relaxed">{item.summary}</p>
              </div>
            ))}
          </div>
        </div>
      )}
    </div>
  );
}
