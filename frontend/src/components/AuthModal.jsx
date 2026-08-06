import React, { useState } from 'react';
import { useAuth } from '../context/AuthContext';
import { X, Mail, Lock, User, AlertCircle, ArrowRight } from 'lucide-react';

export default function AuthModal({ isOpen, onClose, mode, setMode }) {
  if (!isOpen) return null;

  const { login, register } = useAuth();
  const [formData, setFormData] = useState({ name: '', email: '', password: '', password_confirmation: '' });
  const [error, setError] = useState('');
  const [submitting, setSubmitting] = useState(false);

  const handleSubmit = async (e) => {
    e.preventDefault();
    setError('');
    setSubmitting(true);
    try {
      if (mode === 'login') {
        await login(formData.email, formData.password);
      } else {
        if (formData.password !== formData.password_confirmation) {
          setError('Passwords do not match');
          setSubmitting(false);
          return;
        }
        await register(formData.name, formData.email, formData.password, formData.password_confirmation);
      }
      onClose();
    } catch (err) {
      setError(err.response?.data?.message || 'Authentication failed. Please check credentials.');
    } finally {
      setSubmitting(false);
    }
  };

  const inputClass = "w-full pl-10 pr-4 py-3 rounded-2xl neu-inset text-slate-800 placeholder-slate-400 text-sm font-medium transition-all";

  return (
    <div className="fixed inset-0 z-50 flex items-center justify-center p-4 animate-fade-in"
         style={{ background: 'rgba(148, 163, 184, 0.35)', backdropFilter: 'blur(16px)', WebkitBackdropFilter: 'blur(16px)' }}>
      <div className="relative w-full max-w-md rounded-3xl p-6 sm:p-8 animate-slide-up"
           style={{ background: 'rgba(255,255,255,0.75)', backdropFilter: 'blur(24px)', WebkitBackdropFilter: 'blur(24px)', border: '1px solid rgba(255,255,255,0.85)', boxShadow: '-12px -12px 24px rgba(255,255,255,0.9), 12px 12px 24px rgba(182,190,204,0.55)' }}>

        {/* Close */}
        <button
          onClick={onClose}
          className="absolute top-4 right-4 p-2 text-slate-400 hover:text-slate-700 hover:bg-slate-100/80 rounded-xl transition-colors"
        >
          <X className="w-4 h-4" />
        </button>

        {/* Header */}
        <div className="text-center mb-7">
          <div className="w-14 h-14 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-md"
               style={{ background: 'linear-gradient(135deg, #0d9488, #14b8a6)' }}>
            <span className="text-2xl">🌐</span>
          </div>
          <h2 className="text-2xl font-extrabold text-slate-800 tracking-tight">
            {mode === 'login' ? 'Welcome Back' : 'Create Account'}
          </h2>
          <p className="text-sm text-slate-500 mt-1.5">
            {mode === 'login' ? 'Access your Indic translation suite' : 'Join JanBhasha — AI translation for India'}
          </p>
        </div>

        {error && (
          <div className="mb-4 p-3 rounded-2xl bg-rose-50 border border-rose-200 text-rose-600 text-sm flex items-center gap-2">
            <AlertCircle className="w-4 h-4 shrink-0" />
            <span>{error}</span>
          </div>
        )}

        <form onSubmit={handleSubmit} className="space-y-4">
          {mode === 'register' && (
            <div>
              <label className="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Full Name</label>
              <div className="relative">
                <User className="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 z-10" />
                <input
                  type="text" required placeholder="Aarav Sharma"
                  value={formData.name}
                  onChange={(e) => setFormData({ ...formData, name: e.target.value })}
                  className={inputClass}
                />
              </div>
            </div>
          )}

          <div>
            <label className="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Email Address</label>
            <div className="relative">
              <Mail className="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 z-10" />
              <input
                type="email" required placeholder="aarav@example.com"
                value={formData.email}
                onChange={(e) => setFormData({ ...formData, email: e.target.value })}
                className={inputClass}
              />
            </div>
          </div>

          <div>
            <label className="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Password</label>
            <div className="relative">
              <Lock className="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 z-10" />
              <input
                type="password" required placeholder="••••••••"
                value={formData.password}
                onChange={(e) => setFormData({ ...formData, password: e.target.value })}
                className={inputClass}
              />
            </div>
          </div>

          {mode === 'register' && (
            <div>
              <label className="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Confirm Password</label>
              <div className="relative">
                <Lock className="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 z-10" />
                <input
                  type="password" required placeholder="••••••••"
                  value={formData.password_confirmation}
                  onChange={(e) => setFormData({ ...formData, password_confirmation: e.target.value })}
                  className={inputClass}
                />
              </div>
            </div>
          )}

          <button
            type="submit"
            disabled={submitting}
            className="w-full py-3.5 text-white font-bold rounded-2xl flex items-center justify-center gap-2 group btn-teal mt-2"
          >
            {submitting ? 'Processing...' : (mode === 'login' ? 'Sign In' : 'Create Account')}
            <ArrowRight className="w-4 h-4 group-hover:translate-x-1 transition-transform" />
          </button>
        </form>

        <div className="mt-5 text-center text-sm text-slate-500">
          {mode === 'login' ? (
            <p>
              Don't have an account?{' '}
              <button onClick={() => setMode('register')} className="text-teal-600 hover:underline font-semibold">
                Register here
              </button>
            </p>
          ) : (
            <p>
              Already registered?{' '}
              <button onClick={() => setMode('login')} className="text-teal-600 hover:underline font-semibold">
                Sign in
              </button>
            </p>
          )}
        </div>
      </div>
    </div>
  );
}
