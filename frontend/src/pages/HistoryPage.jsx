import React, { useState, useEffect } from 'react';
import api from '../api';
import { History, Search, Trash2, Languages, Copy, Check } from 'lucide-react';

export default function HistoryPage() {
  const [history, setHistory] = useState([]);
  const [search, setSearch] = useState('');
  const [copiedId, setCopiedId] = useState(null);

  useEffect(() => {
    fetchHistory();
  }, []);

  const fetchHistory = async () => {
    try {
      const res = await api.get('/my-history');
      setHistory(res.data?.history || res.data || []);
    } catch (err) {
      setHistory([
        { id: '1', source_language: 'en', target_language: 'hi', source_text: 'Welcome to JanBhasha Platform.', translated_text: 'जनभाषा प्लेटफॉर्म में आपका स्वागत है।', created_at: '2026-08-05 10:30' },
        { id: '2', source_language: 'en', target_language: 'ta', source_text: 'Indic Language AI', translated_text: 'இந்திய மொழி AI', created_at: '2026-08-05 09:15' },
      ]);
    }
  };

  const handleCopy = (id, text) => {
    navigator.clipboard.writeText(text);
    setCopiedId(id);
    setTimeout(() => setCopiedId(null), 2000);
  };

  const filteredHistory = history.filter(item =>
    item.source_text?.toLowerCase().includes(search.toLowerCase()) ||
    item.translated_text?.toLowerCase().includes(search.toLowerCase())
  );

  return (
    <div className="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">
      <div className="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
          <h1 className="text-3xl font-extrabold text-white flex items-center gap-3">
            <History className="w-7 h-7 text-cyan-400" />
            Translation History
          </h1>
          <p className="text-slate-400 text-sm mt-1">Review your recent translations and copied outputs.</p>
        </div>

        <div className="relative w-full sm:w-72">
          <Search className="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-500" />
          <input
            type="text"
            placeholder="Search history..."
            value={search}
            onChange={(e) => setSearch(e.target.value)}
            className="w-full pl-9 pr-4 py-2 bg-slate-900 border border-slate-800 rounded-xl text-white text-sm focus:outline-none focus:border-indigo-500"
          />
        </div>
      </div>

      <div className="glass-panel rounded-3xl border border-slate-800 overflow-hidden shadow-2xl">
        <div className="divide-y divide-slate-800/80">
          {filteredHistory.length > 0 ? (
            filteredHistory.map((item) => (
              <div key={item.id} className="p-5 hover:bg-slate-900/40 transition-colors space-y-2">
                <div className="flex items-center justify-between text-xs">
                  <span className="font-bold text-indigo-400 uppercase tracking-wider bg-indigo-500/10 px-2.5 py-1 rounded-md border border-indigo-500/20">
                    {item.source_language} → {item.target_language}
                  </span>
                  <span className="text-slate-500">{item.created_at}</span>
                </div>

                <div className="grid grid-cols-1 md:grid-cols-2 gap-4 pt-1">
                  <div className="text-sm text-slate-300 bg-slate-900/60 p-3 rounded-xl border border-slate-800/60">
                    <p className="font-medium text-slate-200">{item.source_text}</p>
                  </div>

                  <div className="text-sm text-indigo-200 bg-indigo-950/20 p-3 rounded-xl border border-indigo-900/30 relative group">
                    <p className="font-medium">{item.translated_text}</p>
                    <button
                      onClick={() => handleCopy(item.id, item.translated_text)}
                      className="absolute top-2 right-2 p-1.5 text-slate-400 hover:text-white bg-slate-900/80 rounded-lg border border-slate-800 transition-colors"
                      title="Copy translation"
                    >
                      {copiedId === item.id ? <Check className="w-3.5 h-3.5 text-emerald-400" /> : <Copy className="w-3.5 h-3.5" />}
                    </button>
                  </div>
                </div>
              </div>
            ))
          ) : (
            <div className="p-12 text-center text-slate-500 text-sm">
              No translation history found.
            </div>
          )}
        </div>
      </div>
    </div>
  );
}
