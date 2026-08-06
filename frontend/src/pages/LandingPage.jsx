import React, { useState } from 'react';
import { Link } from 'react-router-dom';
import api from '../api';
import { Sparkles, ArrowRight, Languages, Zap, ShieldCheck, Database, Copy, Check, Camera, Mic, ScanLine, ArrowUpRight } from 'lucide-react';

const INDIC_LANGUAGES = [
  { code: 'hi', name: 'Hindi (हिंदी)', flag: '🇮🇳' },
  { code: 'ta', name: 'Tamil (தமிழ்)', flag: '🇮🇳' },
  { code: 'te', name: 'Telugu (తెలుగు)', flag: '🇮🇳' },
  { code: 'bn', name: 'Bengali (বাংলা)', flag: '🇮🇳' },
  { code: 'mr', name: 'Marathi (मराठी)', flag: '🇮🇳' },
  { code: 'gu', name: 'Gujarati (ગુજરાતી)', flag: '🇮🇳' },
  { code: 'kn', name: 'Kannada (ಕನ್ನಡ)', flag: '🇮🇳' },
  { code: 'ml', name: 'Malayalam (മലയാളം)', flag: '🇮🇳' },
  { code: 'pa', name: 'Punjabi (ਪੰਜਾਬੀ)', flag: '🇮🇳' },
  { code: 'or', name: 'Odia (ଓଡ଼ିଆ)', flag: '🇮🇳' },
];

const QUICK_FEATURES = [
  { icon: Camera, label: 'Camera', desc: 'Snap your text.', iconColor: 'text-violet-600', bg: 'bg-violet-100/70' },
  { icon: Mic, label: 'Voice', desc: 'Speak and translate.', iconColor: 'text-rose-600', bg: 'bg-rose-100/70' },
  { icon: Languages, label: 'Translate AI', desc: 'Smart. Fast. Now.', iconColor: 'text-teal-600', bg: 'bg-teal-100/70' },
  { icon: ScanLine, label: 'Scan', desc: 'Scan and convert.', iconColor: 'text-sky-600', bg: 'bg-sky-100/70' },
];

export default function LandingPage({ onOpenAuth }) {
  const [sourceText, setSourceText] = useState('Welcome to JanBhasha. Empowering seamless multi-lingual Indic AI translation for India.');
  const [targetLang, setTargetLang] = useState('hi');
  const [translatedText, setTranslatedText] = useState('');
  const [loading, setLoading] = useState(false);
  const [copied, setCopied] = useState(false);

  const handleDemoTranslate = async () => {
    if (!sourceText.trim()) return;
    setLoading(true);
    try {
      const res = await api.post('/translations/demo', {
        text: sourceText,
        source_lang: 'en',
        target_lang: targetLang
      });
      setTranslatedText(res.data?.translated_text || 'Translation complete.');
    } catch (err) {
      console.error("Demo translation error", err);
      setTranslatedText(`[Demo – ${targetLang.toUpperCase()}]: ${sourceText}`);
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
    <div className="space-y-12 sm:space-y-16 lg:space-y-20 pb-12 sm:pb-16 lg:pb-20">

      {/* ─── Hero Section ───────────────────────────────────────── */}
      <section className="relative pt-8 sm:pt-12 lg:pt-20 overflow-hidden">
        {/* Soft background blobs */}
        <div className="absolute top-10 left-1/4 w-72 h-72 rounded-full opacity-25 blur-[80px] pointer-events-none"
             style={{ background: 'radial-gradient(circle, #99f6e4, transparent)' }} />
        <div className="absolute top-20 right-1/4 w-56 h-56 rounded-full opacity-20 blur-[70px] pointer-events-none"
             style={{ background: 'radial-gradient(circle, #c4b5fd, transparent)' }} />

        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
          <div className="flex flex-col lg:flex-row items-center gap-8 lg:gap-16">

            {/* Left: Text */}
            <div className="flex-1 text-center lg:text-left">
              <div className="inline-flex items-center gap-2 px-4 py-2 rounded-full text-xs font-bold uppercase tracking-wider mb-6 text-teal-700"
                   style={{ background: 'rgba(255,255,255,0.65)', border: '1px solid rgba(13,148,136,0.2)', boxShadow: '0 2px 8px rgba(13,148,136,0.1)' }}>
                <Sparkles className="w-3.5 h-3.5 text-teal-500" />
                Next-Gen Indic Neural Translation
              </div>

              <h1 className="text-4xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight text-slate-900 leading-tight">
                Your <span className="gradient-text">AI Translator</span>
                <br className="hidden sm:block" />
                <span className="text-slate-700"> for Bharat</span>
              </h1>

              <p className="mt-5 text-base sm:text-lg text-slate-500 max-w-lg mx-auto lg:mx-0 leading-relaxed">
                Translate voice, text, and images instantly with powerful AI assistance across 22+ official Indic languages.
              </p>

              <div className="mt-8 flex flex-col sm:flex-row items-center justify-center lg:justify-start gap-3">
                <button
                  onClick={() => onOpenAuth('register')}
                  className="w-full sm:w-auto px-7 py-3.5 text-white font-bold rounded-2xl flex items-center justify-center gap-2 btn-teal"
                >
                  Start Free Trial
                  <ArrowRight className="w-4 h-4" />
                </button>
                <Link
                  to="/translate"
                  className="w-full sm:w-auto px-7 py-3.5 font-semibold text-slate-700 rounded-2xl flex items-center justify-center gap-2 neu-btn text-sm"
                >
                  Open Studio
                  <ArrowUpRight className="w-4 h-4" />
                </Link>
              </div>
            </div>

            {/* Right: Holographic Orb — hidden on mobile to save space */}
            <div className="hidden sm:flex flex-shrink-0 flex-col items-center gap-6">
              <div className="relative w-56 h-56 sm:w-64 sm:h-64 lg:w-72 lg:h-72 animate-float">
                <div className="holographic-orb w-full h-full relative" />
                <div className="absolute inset-5 rounded-full"
                     style={{ background: 'radial-gradient(circle at 35% 35%, rgba(255,255,255,0.85), rgba(255,255,255,0.05))' }} />
              </div>
              <p className="text-slate-500 text-sm font-medium italic text-center">
                Translate voice, text, and images instantly
                <br />with powerful AI assistance.
              </p>
            </div>
          </div>
        </div>
      </section>

      {/* ─── Quick Feature Grid (from screenshot) ──────────────── */}
      <section className="max-w-4xl mx-auto px-4 sm:px-6">
        <div className="grid grid-cols-2 sm:grid-cols-4 gap-4">
          {QUICK_FEATURES.map(({ icon: Icon, label, desc, iconColor, bg }) => (
            <div key={label}
                 className="rounded-3xl p-5 cursor-pointer transition-all hover:scale-105 glass-card group relative overflow-hidden">
              <button className="absolute top-3 right-3 w-6 h-6 rounded-full bg-white/70 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                <ArrowUpRight className="w-3 h-3 text-slate-500" />
              </button>
              <div className={`w-11 h-11 rounded-2xl flex items-center justify-center mb-3 ${bg}`}>
                <Icon className={`w-5 h-5 ${iconColor}`} />
              </div>
              <p className="font-bold text-slate-800 text-sm">{label}</p>
              <p className="text-xs text-slate-500 mt-0.5">{desc}</p>
            </div>
          ))}
        </div>
      </section>

      {/* ─── Live Translation Sandbox ───────────────────────────── */}
      <section className="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <div className="neu-card p-6 sm:p-8 space-y-6">
          {/* Header */}
          <div className="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 pb-4 border-b border-slate-100">
            <div className="flex items-center gap-2">
              <div className="w-8 h-8 rounded-xl flex items-center justify-center" style={{ background: 'linear-gradient(135deg, #0d9488, #14b8a6)' }}>
                <Languages className="w-4 h-4 text-white" />
              </div>
              <h2 className="text-base font-bold text-slate-800">Live AI Translation Sandbox</h2>
            </div>
            <div className="flex items-center gap-3">
              <span className="text-xs font-semibold text-slate-400">Target Language:</span>
              <select
                value={targetLang}
                onChange={(e) => setTargetLang(e.target.value)}
                className="neu-inset rounded-xl text-sm font-semibold text-teal-700 px-3 py-1.5"
              >
                {INDIC_LANGUAGES.map((lang) => (
                  <option key={lang.code} value={lang.code}>{lang.flag} {lang.name}</option>
                ))}
              </select>
            </div>
          </div>

          {/* Text Areas */}
          <div className="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div className="space-y-2">
              <label className="text-xs font-bold text-slate-400 uppercase tracking-wider">Source (English)</label>
              <textarea
                rows={5}
                value={sourceText}
                onChange={(e) => setSourceText(e.target.value)}
                placeholder="Type or paste text here..."
                className="w-full p-4 rounded-2xl neu-inset text-slate-800 placeholder-slate-400 text-sm leading-relaxed resize-none"
              />
            </div>
            <div className="space-y-2">
              <div className="flex items-center justify-between">
                <label className="text-xs font-bold text-slate-400 uppercase tracking-wider">Translation Output</label>
                {translatedText && (
                  <button onClick={handleCopy} className="flex items-center gap-1 text-xs text-teal-600 font-semibold hover:text-teal-700">
                    {copied ? <Check className="w-3.5 h-3.5 text-emerald-500" /> : <Copy className="w-3.5 h-3.5" />}
                    {copied ? 'Copied!' : 'Copy'}
                  </button>
                )}
              </div>
              <div className="w-full min-h-[130px] p-4 rounded-2xl flex items-center justify-center relative overflow-hidden"
                   style={{ background: 'rgba(255,255,255,0.6)', border: '1px solid rgba(255,255,255,0.8)', boxShadow: 'inset 2px 2px 8px rgba(163,175,194,0.2)' }}>
                {loading ? (
                  <div className="flex items-center gap-2 text-teal-600">
                    <Sparkles className="w-5 h-5 animate-spin" />
                    <span className="text-sm font-medium">Translating via JanBhasha AI...</span>
                  </div>
                ) : (
                  <p className="w-full text-left text-slate-700 text-sm leading-relaxed">
                    {translatedText || 'Click Translate Now to see live output.'}
                  </p>
                )}
              </div>
            </div>
          </div>

          <div className="flex justify-end pt-1">
            <button
              onClick={handleDemoTranslate}
              disabled={loading}
              className="px-6 py-3 text-white font-bold text-sm rounded-2xl flex items-center gap-2 btn-teal"
            >
              <Sparkles className="w-4 h-4" />
              Translate Now
            </button>
          </div>
        </div>
      </section>

      {/* ─── Feature Grid ───────────────────────────────────────── */}
      <section className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div className="text-center max-w-2xl mx-auto mb-12">
          <h2 className="text-3xl font-extrabold text-slate-900">
            Built for <span className="gradient-text">High-Scale</span> Enterprise Localization
          </h2>
          <p className="mt-3 text-slate-500 text-base">
            Designed with modern API key authentication, domain glossary term retention, and real-time analytics.
          </p>
        </div>

        <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
          {[
            { Icon: Zap, title: 'Sub-Second Latency', desc: 'Streamlined neural translation API optimized for web, mobile, and government portals.', iconColor: 'text-amber-600', bg: 'bg-amber-100/70' },
            { Icon: Database, title: 'Custom Domain Glossaries', desc: 'Enforce specific technical, medical, or legal terms across all translated content automatically.', iconColor: 'text-violet-600', bg: 'bg-violet-100/70' },
            { Icon: ShieldCheck, title: 'Organisation Key Control', desc: 'Issue separate API keys per client or department with monthly quota enforcement and tracking.', iconColor: 'text-teal-600', bg: 'bg-teal-100/70' },
          ].map(({ Icon, title, desc, iconColor, bg }) => (
            <div key={title} className="glass-card p-6 rounded-3xl space-y-4 hover:shadow-lg transition-all hover:-translate-y-0.5 border border-white/70">
              <div className={`w-12 h-12 rounded-2xl flex items-center justify-center ${bg}`}>
                <Icon className={`w-6 h-6 ${iconColor}`} />
              </div>
              <h3 className="text-lg font-bold text-slate-800">{title}</h3>
              <p className="text-sm text-slate-500 leading-relaxed">{desc}</p>
            </div>
          ))}
        </div>
      </section>
    </div>
  );
}
