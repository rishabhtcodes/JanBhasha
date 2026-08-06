import React, { useState, useEffect } from 'react';
import api from '../api';
import { BookOpen, Plus, X, ArrowRight } from 'lucide-react';

const CATEGORIES = ['Government', 'Technology', 'Legal', 'Medical', 'Finance', 'Education', 'General'];

const CATEGORY_COLORS = {
  Government: 'bg-sky-50 text-sky-700 border-sky-100',
  Technology: 'bg-violet-50 text-violet-700 border-violet-100',
  Legal: 'bg-amber-50 text-amber-700 border-amber-100',
  Medical: 'bg-rose-50 text-rose-700 border-rose-100',
  Finance: 'bg-emerald-50 text-emerald-700 border-emerald-100',
  Education: 'bg-teal-50 text-teal-700 border-teal-100',
  General: 'bg-slate-50 text-slate-600 border-slate-100',
};

export default function GlossaryPage() {
  const [terms, setTerms] = useState([]);
  const [showAdd, setShowAdd] = useState(false);
  const [newTerm, setNewTerm] = useState({ source_term: '', target_term: '', language: 'hi', category: 'General' });

  useEffect(() => { fetchGlossary(); }, []);

  const fetchGlossary = async () => {
    try {
      const res = await api.get('/glossary');
      setTerms(res.data?.glossary || res.data || []);
    } catch (err) {
      setTerms([
        { id: '1', source_term: 'Ministry of Finance', target_term: 'वित्त मंत्रालय', language: 'hi', category: 'Government' },
        { id: '2', source_term: 'Gazette Notification', target_term: 'राजपत्र अधिसूचना', language: 'hi', category: 'Legal' },
        { id: '3', source_term: 'Digital India', target_term: 'डिजिटल इंडिया', language: 'hi', category: 'Technology' },
        { id: '4', source_term: 'Aadhaar', target_term: 'ஆதார்', language: 'ta', category: 'Government' },
      ]);
    }
  };

  const handleAddTerm = async (e) => {
    e.preventDefault();
    try {
      await api.post('/glossary', newTerm);
      fetchGlossary();
    } catch (err) {
      setTerms([...terms, { id: Date.now().toString(), ...newTerm }]);
    }
    setShowAdd(false);
    setNewTerm({ source_term: '', target_term: '', language: 'hi', category: 'General' });
  };

  const inputClass = "w-full px-3.5 py-2.5 rounded-2xl neu-inset text-slate-700 placeholder-slate-400 text-sm font-medium";

  return (
    <div className="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8 space-y-6">
      {/* Header */}
      <div className="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
          <h1 className="text-2xl sm:text-3xl font-extrabold text-slate-900 flex items-center gap-3">
            <div className="w-10 h-10 rounded-2xl flex items-center justify-center bg-emerald-50 flex-shrink-0">
              <BookOpen className="w-5 h-5 text-emerald-600" />
            </div>
            Custom Glossary Terms
          </h1>
          <p className="text-slate-500 text-sm mt-1.5">Define custom domain terms to override default neural translations.</p>
        </div>
        <button
          onClick={() => setShowAdd(true)}
          className="w-full sm:w-auto flex items-center justify-center gap-2 px-5 py-2.5 text-white font-bold text-sm rounded-2xl btn-teal flex-shrink-0"
        >
          <Plus className="w-4 h-4" />
          Add Glossary Term
        </button>
      </div>

      {/* Add Term Form */}
      {showAdd && (
        <div className="neu-card p-5 sm:p-6 space-y-5 animate-fade-in border border-teal-100/60">
          <div className="flex items-center justify-between">
            <h3 className="font-bold text-slate-800 text-base">Add New Technical / Domain Term</h3>
            <button onClick={() => setShowAdd(false)} className="p-1.5 rounded-xl text-slate-400 hover:text-slate-700 hover:bg-slate-100/70 transition-colors">
              <X className="w-5 h-5" />
            </button>
          </div>

          <form onSubmit={handleAddTerm} className="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label className="block text-xs font-bold text-slate-400 uppercase mb-1.5">Source Term (English)</label>
              <input type="text" required placeholder="e.g. JanBhasha"
                value={newTerm.source_term}
                onChange={(e) => setNewTerm({ ...newTerm, source_term: e.target.value })}
                className={inputClass}
              />
            </div>
            <div>
              <label className="block text-xs font-bold text-slate-400 uppercase mb-1.5">Target Term (Indic)</label>
              <input type="text" required placeholder="e.g. जनभाषा"
                value={newTerm.target_term}
                onChange={(e) => setNewTerm({ ...newTerm, target_term: e.target.value })}
                className={inputClass}
              />
            </div>
            <div>
              <label className="block text-xs font-bold text-slate-400 uppercase mb-1.5">Target Language</label>
              <select value={newTerm.language}
                onChange={(e) => setNewTerm({ ...newTerm, language: e.target.value })}
                className={inputClass}>
                <option value="hi">Hindi (hi)</option>
                <option value="ta">Tamil (ta)</option>
                <option value="te">Telugu (te)</option>
                <option value="bn">Bengali (bn)</option>
                <option value="mr">Marathi (mr)</option>
                <option value="gu">Gujarati (gu)</option>
                <option value="kn">Kannada (kn)</option>
              </select>
            </div>
            <div>
              <label className="block text-xs font-bold text-slate-400 uppercase mb-1.5">Category</label>
              <select value={newTerm.category}
                onChange={(e) => setNewTerm({ ...newTerm, category: e.target.value })}
                className={inputClass}>
                {CATEGORIES.map((c) => <option key={c} value={c}>{c}</option>)}
              </select>
            </div>
            <div className="sm:col-span-2 flex justify-end pt-1">
              <button type="submit" className="w-full sm:w-auto px-6 py-2.5 text-white font-bold text-sm rounded-2xl btn-teal">
                Save Glossary Entry
              </button>
            </div>
          </form>
        </div>
      )}

      {/* Glossary — Card layout on mobile, Table on sm+ */}
      <div className="neu-card overflow-hidden">
        {/* Desktop Table (hidden on mobile) */}
        <div className="hidden sm:block overflow-x-auto">
          <table className="w-full text-left border-collapse">
            <thead>
              <tr className="border-b border-slate-100 text-xs text-slate-400 font-bold uppercase tracking-wider"
                  style={{ background: 'rgba(255,255,255,0.5)' }}>
                <th className="px-5 py-4">Source Term</th>
                <th className="px-5 py-4">Indic Term</th>
                <th className="px-5 py-4">Language</th>
                <th className="px-5 py-4">Category</th>
              </tr>
            </thead>
            <tbody className="divide-y divide-slate-100/80 text-sm">
              {terms.map((term) => (
                <tr key={term.id} className="hover:bg-white/50 transition-colors">
                  <td className="px-5 py-4 font-semibold text-slate-800">{term.source_term}</td>
                  <td className="px-5 py-4 font-bold text-teal-700">{term.target_term}</td>
                  <td className="px-5 py-4">
                    <span className="uppercase text-xs font-mono font-bold text-slate-500 bg-slate-50 border border-slate-100 px-2.5 py-1 rounded-xl">
                      {term.language}
                    </span>
                  </td>
                  <td className="px-5 py-4">
                    <span className={`text-xs font-bold px-2.5 py-1 rounded-xl border ${CATEGORY_COLORS[term.category] || CATEGORY_COLORS.General}`}>
                      {term.category}
                    </span>
                  </td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>

        {/* Mobile Card List (hidden on sm+) */}
        <div className="sm:hidden divide-y divide-slate-100/80">
          {terms.map((term) => (
            <div key={term.id} className="p-4 space-y-2.5 hover:bg-white/50 transition-colors">
              <div className="flex items-center justify-between gap-2">
                <div className="flex items-center gap-2 text-sm flex-wrap">
                  <span className="font-semibold text-slate-800">{term.source_term}</span>
                  <ArrowRight className="w-3.5 h-3.5 text-slate-300 flex-shrink-0" />
                  <span className="font-bold text-teal-700">{term.target_term}</span>
                </div>
              </div>
              <div className="flex items-center gap-2 flex-wrap">
                <span className="uppercase text-xs font-mono font-bold text-slate-500 bg-slate-50 border border-slate-100 px-2.5 py-1 rounded-xl">
                  {term.language}
                </span>
                <span className={`text-xs font-bold px-2.5 py-1 rounded-xl border ${CATEGORY_COLORS[term.category] || CATEGORY_COLORS.General}`}>
                  {term.category}
                </span>
              </div>
            </div>
          ))}
        </div>
      </div>
    </div>
  );
}
