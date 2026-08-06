import React, { useState, useEffect } from 'react';
import api from '../api';
import { History, Search, Copy, Check, Languages, ArrowRight } from 'lucide-react';

const LANG_COLORS = {
  en: { bg: 'bg-sky-50', text: 'text-sky-700', border: 'border-sky-100' },
  hi: { bg: 'bg-orange-50', text: 'text-orange-700', border: 'border-orange-100' },
  ta: { bg: 'bg-teal-50', text: 'text-teal-700', border: 'border-teal-100' },
  te: { bg: 'bg-violet-50', text: 'text-violet-700', border: 'border-violet-100' },
  bn: { bg: 'bg-pink-50', text: 'text-pink-700', border: 'border-pink-100' },
  mr: { bg: 'bg-amber-50', text: 'text-amber-700', border: 'border-amber-100' },
};

const getLangStyle = (code) =>
  LANG_COLORS[code] || { bg: 'bg-slate-50', text: 'text-slate-600', border: 'border-slate-100' };

export default function HistoryPage() {
  const [history, setHistory] = useState([]);
  const [search, setSearch] = useState('');
  const [copiedId, setCopiedId] = useState(null);

  useEffect(() => { fetchHistory(); }, []);

  const fetchHistory = async () => {
    try {
      const res = await api.get('/my-history');
      setHistory(res.data?.history || res.data || []);
    } catch (err) {
      setHistory([
        { id: '1', source_language: 'en', target_language: 'hi', source_text: 'Government of India Circular regarding Digital Public Infrastructure', translated_text: 'डिजिटल सार्वजनिक अवसंरचना के संबंध में भारत सरकार का परिपत्र', created_at: '2026-08-05 11:20' },
        { id: '2', source_language: 'en', target_language: 'ta', source_text: 'Official notice regarding health insurance benefits', translated_text: 'சுகாதார காப்பீட்டு சலுகைகள் பற்றிய அதிகாரப்பூர்வ அறிவிப்பு', created_at: '2026-08-05 09:45' },
        { id: '3', source_language: 'en', target_language: 'bn', source_text: 'Digital Literacy Mission — Rural Outreach Programme', translated_text: 'ডিজিটাল সাক্ষরতা মিশন — গ্রামীণ প্রচার কার্যক্রম', created_at: '2026-08-04 16:10' },
      ]);
    }
  };

  const handleCopy = (id, text) => {
    navigator.clipboard.writeText(text);
    setCopiedId(id);
    setTimeout(() => setCopiedId(null), 2000);
  };

  const filteredHistory = history.filter((item) =>
    item.source_text?.toLowerCase().includes(search.toLowerCase()) ||
    item.translated_text?.toLowerCase().includes(search.toLowerCase())
  );

  return (
    <div className="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8 space-y-6">
      {/* Header */}
      <div className="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
          <h1 className="text-2xl sm:text-3xl font-extrabold text-slate-900 flex items-center gap-3">
            <div className="w-10 h-10 rounded-2xl flex items-center justify-center bg-sky-50 flex-shrink-0">
              <History className="w-5 h-5 text-sky-600" />
            </div>
            Translation History
          </h1>
          <p className="text-slate-500 text-sm mt-1.5">Review your recent translations and copied outputs.</p>
        </div>

        {/* Search */}
        <div className="relative w-full sm:w-72">
          <Search className="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 z-10" />
          <input
            type="text"
            placeholder="Search history..."
            value={search}
            onChange={(e) => setSearch(e.target.value)}
            className="w-full pl-10 pr-4 py-2.5 rounded-2xl neu-inset text-slate-700 placeholder-slate-400 text-sm font-medium"
          />
        </div>
      </div>

      {/* History List */}
      <div className="neu-card overflow-hidden">
        <div className="divide-y divide-slate-100/80">
          {filteredHistory.length > 0 ? (
            filteredHistory.map((item) => {
              const srcStyle = getLangStyle(item.source_language);
              const tgtStyle = getLangStyle(item.target_language);
              return (
                <div key={item.id} className="p-4 sm:p-5 hover:bg-white/50 transition-colors space-y-3 animate-fade-in">
                  {/* Meta row */}
                  <div className="flex items-center justify-between text-xs flex-wrap gap-2">
                    <div className="flex items-center gap-2">
                      <span className={`font-bold uppercase tracking-wider px-2.5 py-1 rounded-xl border ${srcStyle.bg} ${srcStyle.text} ${srcStyle.border}`}>
                        {item.source_language}
                      </span>
                      <ArrowRight className="w-3.5 h-3.5 text-slate-300" />
                      <span className={`font-bold uppercase tracking-wider px-2.5 py-1 rounded-xl border ${tgtStyle.bg} ${tgtStyle.text} ${tgtStyle.border}`}>
                        {item.target_language}
                      </span>
                    </div>
                    <span className="text-slate-400 font-mono">{item.created_at}</span>
                  </div>

                  {/* Text pair — stacked on mobile, side-by-side on md+ */}
                  <div className="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <div
                      className="text-sm text-slate-600 p-3.5 rounded-2xl border border-slate-100 leading-relaxed"
                      style={{ background: 'rgba(255,255,255,0.5)' }}
                    >
                      {item.source_text}
                    </div>
                    <div
                      className="text-sm text-slate-800 p-3.5 rounded-2xl border border-teal-100 leading-relaxed relative group"
                      style={{ background: 'rgba(240,253,250,0.6)' }}
                    >
                      {item.translated_text}
                      {/* Copy button — always visible on touch, hover on desktop */}
                      <button
                        onClick={() => handleCopy(item.id, item.translated_text)}
                        className="absolute top-2.5 right-2.5 p-1.5 rounded-xl bg-white/80 border border-white shadow-sm text-slate-400 hover:text-teal-600 transition-colors opacity-100 sm:opacity-0 sm:group-hover:opacity-100"
                        title="Copy translation"
                      >
                        {copiedId === item.id ? <Check className="w-3.5 h-3.5 text-emerald-500" /> : <Copy className="w-3.5 h-3.5" />}
                      </button>
                    </div>
                  </div>
                </div>
              );
            })
          ) : (
            <div className="p-14 text-center">
              <div className="w-14 h-14 rounded-full flex items-center justify-center mx-auto mb-3 bg-slate-50">
                <History className="w-6 h-6 text-slate-300" />
              </div>
              <p className="text-slate-400 text-sm font-medium">No translation history found.</p>
            </div>
          )}
        </div>
      </div>
    </div>
  );
}
