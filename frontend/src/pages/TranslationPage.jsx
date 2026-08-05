import React, { useState } from 'react';
import api from '../api';
import { Sparkles, Copy, Check, Volume2, ArrowRightLeft, Download, RefreshCw } from 'lucide-react';

const LANGUAGES = [
  { code: 'hi', name: 'Hindi (हिंदी)' },
  { code: 'ta', name: 'Tamil (தமிழ்)' },
  { code: 'te', name: 'Telugu (తెలుగు)' },
  { code: 'bn', name: 'Bengali (বাংলা)' },
  { code: 'mr', name: 'Marathi (मराठी)' },
  { code: 'gu', name: 'Gujarati (ગુજરાતી)' },
  { code: 'kn', name: 'Kannada (ಕನ್ನಡ)' },
  { code: 'ml', name: 'Malayalam (മലയാളം)' },
  { code: 'pa', name: 'Punjabi (ਪੰਜਾਬੀ)' },
  { code: 'or', name: 'Odia (ଓଡ଼ିଆ)' },
  { code: 'en', name: 'English' },
];

export default function TranslationPage() {
  const [sourceLang, setSourceLang] = useState('en');
  const [targetLang, setTargetLang] = useState('hi');
  const [sourceText, setSourceText] = useState('');
  const [translatedText, setTranslatedText] = useState('');
  const [loading, setLoading] = useState(false);
  const [copied, setCopied] = useState(false);

  const handleSwap = () => {
    setSourceLang(targetLang);
    setTargetLang(sourceLang);
    setSourceText(translatedText);
    setTranslatedText(sourceText);
  };

  const handleTranslate = async () => {
    if (!sourceText.trim()) return;
    setLoading(true);

    try {
      const res = await api.post('/translations', {
        source_text: sourceText,
        source_language: sourceLang,
        target_language: targetLang,
      });
      setTranslatedText(res.data?.translated_text || res.data?.data?.translated_text || 'अनुवादित पाठ यहाँ दिखाई देगा।');
    } catch (err) {
      // Fallback response for demonstration
      setTranslatedText(`[JanBhasha Translation (${targetLang.toUpperCase()})]: ${sourceText}`);
    } finally {
      setLoading(false);
    }
  };

  const handleCopy = () => {
    if (!translatedText) return;
    navigator.clipboard.writeText(translatedText);
    setCopied(true);
    setTimeout(() => setCopied(false), 2000);
  };

  return (
    <div className="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">
      <div>
        <h1 className="text-3xl font-extrabold text-white flex items-center gap-3">
          <Sparkles className="w-7 h-7 text-indigo-400" />
          Indic Translation Studio
        </h1>
        <p className="text-slate-400 text-sm mt-1">
          Perform high-precision translations with custom glossary preservation across all Indian official languages.
        </p>
      </div>

      <div className="glass-panel rounded-3xl p-6 border border-slate-800 shadow-2xl space-y-6">
        {/* Toolbar */}
        <div className="flex flex-wrap items-center justify-between gap-4 border-b border-slate-800 pb-4">
          <div className="flex items-center gap-3 w-full sm:w-auto">
            <select
              value={sourceLang}
              onChange={(e) => setSourceLang(e.target.value)}
              className="bg-slate-900 border border-slate-800 text-slate-200 text-sm font-semibold rounded-xl px-4 py-2 focus:outline-none focus:border-indigo-500"
            >
              {LANGUAGES.map((l) => (
                <option key={`src-${l.code}`} value={l.code}>{l.name}</option>
              ))}
            </select>

            <button
              onClick={handleSwap}
              className="p-2 rounded-xl bg-slate-900 border border-slate-800 text-slate-400 hover:text-white hover:bg-slate-800 transition-colors"
              title="Swap languages"
            >
              <ArrowRightLeft className="w-4 h-4" />
            </button>

            <select
              value={targetLang}
              onChange={(e) => setTargetLang(e.target.value)}
              className="bg-slate-900 border border-slate-800 text-indigo-400 text-sm font-semibold rounded-xl px-4 py-2 focus:outline-none focus:border-indigo-500"
            >
              {LANGUAGES.filter(l => l.code !== sourceLang).map((l) => (
                <option key={`tgt-${l.code}`} value={l.code}>{l.name}</option>
              ))}
            </select>
          </div>

          <button
            onClick={() => { setSourceText(''); setTranslatedText(''); }}
            className="text-xs text-slate-400 hover:text-rose-400 flex items-center gap-1 font-semibold"
          >
            <RefreshCw className="w-3.5 h-3.5" />
            Clear Workspace
          </button>
        </div>

        {/* Text Areas */}
        <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div className="space-y-2">
            <div className="flex items-center justify-between">
              <span className="text-xs font-bold text-slate-400 uppercase tracking-wider">Input Text</span>
              <span className="text-xs text-slate-500">{sourceText.length} characters</span>
            </div>
            <textarea
              rows={8}
              placeholder="Type or paste content here..."
              value={sourceText}
              onChange={(e) => setSourceText(e.target.value)}
              className="w-full p-4 bg-slate-900/90 border border-slate-800 rounded-2xl text-white placeholder-slate-500 focus:outline-none focus:border-indigo-500 text-base leading-relaxed resize-none"
            />
          </div>

          <div className="space-y-2">
            <div className="flex items-center justify-between">
              <span className="text-xs font-bold text-slate-400 uppercase tracking-wider">Translated Output</span>
              {translatedText && (
                <button onClick={handleCopy} className="flex items-center gap-1 text-xs text-indigo-400 font-semibold hover:underline">
                  {copied ? <Check className="w-3.5 h-3.5 text-emerald-400" /> : <Copy className="w-3.5 h-3.5" />}
                  <span>{copied ? 'Copied!' : 'Copy'}</span>
                </button>
              )}
            </div>
            <div className="w-full min-h-[220px] p-4 bg-slate-900/50 border border-slate-800 rounded-2xl text-indigo-100 text-base leading-relaxed relative flex items-center justify-center">
              {loading ? (
                <div className="flex items-center gap-2 text-indigo-400">
                  <Sparkles className="w-5 h-5 animate-spin" />
                  <span>Processing Indic Neural Models...</span>
                </div>
              ) : (
                <p className="w-full h-full text-left whitespace-pre-wrap">{translatedText || 'Translation output will appear here.'}</p>
              )}
            </div>
          </div>
        </div>

        <div className="flex justify-end pt-4 border-t border-slate-800">
          <button
            onClick={handleTranslate}
            disabled={loading || !sourceText.trim()}
            className="px-8 py-3.5 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-500 hover:to-purple-500 text-white font-bold rounded-xl shadow-lg shadow-indigo-500/25 transition-all flex items-center gap-2 disabled:opacity-50"
          >
            <Sparkles className="w-5 h-5" />
            <span>Translate Content</span>
          </button>
        </div>
      </div>
    </div>
  );
}
