import React, { useState, useEffect } from 'react';
import api from '../api';
import { BookOpen, Plus, Trash2, Edit, Save, X, Search } from 'lucide-react';

export default function GlossaryPage() {
  const [terms, setTerms] = useState([]);
  const [showAdd, setShowAdd] = useState(false);
  const [newTerm, setNewTerm] = useState({ source_term: '', target_term: '', language: 'hi', category: 'General' });

  useEffect(() => {
    fetchGlossary();
  }, []);

  const fetchGlossary = async () => {
    try {
      const res = await api.get('/glossary');
      setTerms(res.data?.glossary || res.data || []);
    } catch (err) {
      setTerms([
        { id: '1', source_term: 'Aadhaar', target_term: 'आधार', language: 'hi', category: 'Government' },
        { id: '2', source_term: 'Digital India', target_term: 'डिजिटल इंडिया', language: 'hi', category: 'Technology' },
      ]);
    }
  };

  const handleAddTerm = async (e) => {
    e.preventDefault();
    try {
      await api.post('/glossary', newTerm);
      fetchGlossary();
      setShowAdd(false);
      setNewTerm({ source_term: '', target_term: '', language: 'hi', category: 'General' });
    } catch (err) {
      setTerms([...terms, { id: Date.now().toString(), ...newTerm }]);
      setShowAdd(false);
    }
  };

  return (
    <div className="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">
      <div className="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
          <h1 className="text-3xl font-extrabold text-white flex items-center gap-3">
            <BookOpen className="w-7 h-7 text-emerald-400" />
            Custom Glossary Terms
          </h1>
          <p className="text-slate-400 text-sm mt-1">Define custom domain terms to override default neural translations.</p>
        </div>

        <button
          onClick={() => setShowAdd(true)}
          className="px-4 py-2.5 bg-gradient-to-r from-emerald-600 to-teal-600 text-white font-semibold text-sm rounded-xl shadow-lg shadow-emerald-500/20 hover:scale-105 transition-all flex items-center gap-2"
        >
          <Plus className="w-4 h-4" />
          Add Glossary Term
        </button>
      </div>

      {showAdd && (
        <form onSubmit={handleAddTerm} className="glass-panel p-6 rounded-2xl border border-emerald-500/30 space-y-4 animate-fade-in">
          <div className="flex items-center justify-between border-b border-slate-800 pb-3">
            <h3 className="text-base font-bold text-white">Add New Technical/Domain Term</h3>
            <button type="button" onClick={() => setShowAdd(false)} className="text-slate-400 hover:text-white">
              <X className="w-5 h-5" />
            </button>
          </div>

          <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div>
              <label className="block text-xs font-semibold text-slate-400 uppercase mb-1">Source Term (English)</label>
              <input
                type="text"
                required
                placeholder="e.g. JanBhasha"
                value={newTerm.source_term}
                onChange={(e) => setNewTerm({ ...newTerm, source_term: e.target.value })}
                className="w-full px-3 py-2 bg-slate-900 border border-slate-800 rounded-xl text-white text-sm focus:outline-none focus:border-emerald-500"
              />
            </div>
            <div>
              <label className="block text-xs font-semibold text-slate-400 uppercase mb-1">Target Term (Indic)</label>
              <input
                type="text"
                required
                placeholder="e.g. जनभाषा"
                value={newTerm.target_term}
                onChange={(e) => setNewTerm({ ...newTerm, target_term: e.target.value })}
                className="w-full px-3 py-2 bg-slate-900 border border-slate-800 rounded-xl text-white text-sm focus:outline-none focus:border-emerald-500"
              />
            </div>
            <div>
              <label className="block text-xs font-semibold text-slate-400 uppercase mb-1">Target Language</label>
              <select
                value={newTerm.language}
                onChange={(e) => setNewTerm({ ...newTerm, language: e.target.value })}
                className="w-full px-3 py-2 bg-slate-900 border border-slate-800 rounded-xl text-white text-sm focus:outline-none focus:border-emerald-500"
              >
                <option value="hi">Hindi (hi)</option>
                <option value="ta">Tamil (ta)</option>
                <option value="te">Telugu (te)</option>
                <option value="bn">Bengali (bn)</option>
                <option value="mr">Marathi (mr)</option>
              </select>
            </div>
            <div>
              <label className="block text-xs font-semibold text-slate-400 uppercase mb-1">Category</label>
              <input
                type="text"
                placeholder="Technology / Legal"
                value={newTerm.category}
                onChange={(e) => setNewTerm({ ...newTerm, category: e.target.value })}
                className="w-full px-3 py-2 bg-slate-900 border border-slate-800 rounded-xl text-white text-sm focus:outline-none focus:border-emerald-500"
              />
            </div>
          </div>

          <div className="flex justify-end pt-2">
            <button type="submit" className="px-6 py-2 bg-emerald-600 text-white font-semibold text-sm rounded-xl hover:bg-emerald-500">
              Save Glossary Entry
            </button>
          </div>
        </form>
      )}

      <div className="glass-panel rounded-3xl border border-slate-800 overflow-hidden shadow-2xl">
        <table className="w-full text-left border-collapse">
          <thead>
            <tr className="border-b border-slate-800 bg-slate-900/60 text-xs text-slate-400 font-bold uppercase tracking-wider">
              <th className="p-4">Source Term</th>
              <th className="p-4">Indic Term</th>
              <th className="p-4">Language</th>
              <th className="p-4">Category</th>
            </tr>
          </thead>
          <tbody className="divide-y divide-slate-800/80 text-sm">
            {terms.map((term) => (
              <tr key={term.id} className="hover:bg-slate-900/40 transition-colors">
                <td className="p-4 font-semibold text-white">{term.source_term}</td>
                <td className="p-4 text-emerald-400 font-semibold">{term.target_term}</td>
                <td className="p-4 uppercase text-xs font-mono text-slate-400">{term.language}</td>
                <td className="p-4 text-xs text-slate-400"><span className="bg-slate-800 px-2.5 py-1 rounded-md">{term.category}</span></td>
              </tr>
            ))}
          </tbody>
        </table>
      </div>
    </div>
  );
}
