import React, { useState } from 'react';
import { Link, useNavigate, useLocation } from 'react-router-dom';
import { useAuth } from '../context/AuthContext';
import { Languages, LayoutDashboard, History, BookOpen, Shield, LogOut, Sparkles, Menu, X } from 'lucide-react';

export default function Navbar({ onOpenAuth }) {
  const { user, logout } = useAuth();
  const [mobileMenuOpen, setMobileMenuOpen] = useState(false);
  const navigate = useNavigate();
  const location = useLocation();

  const handleLogout = async () => {
    await logout();
    navigate('/');
  };

  const isActive = (path) => location.pathname === path;

  const navLinkClass = (path) =>
    `px-2.5 py-1 text-xs font-semibold rounded-lg transition-all flex items-center gap-1.5 ${
      isActive(path)
        ? 'bg-white/80 text-teal-700 shadow-sm border border-white/80'
        : 'text-slate-500 hover:text-slate-800 hover:bg-white/50'
    }`;

  return (
    <>
      {/* Teal top accent bar */}
      <div className="h-1 w-full sticky top-0 z-50" style={{ background: 'linear-gradient(90deg, #0d9488, #14b8a6, #06b6d4, #0d9488)' }} />

      <header className="sticky top-1 z-40 border-b border-white/60" style={{ background: 'rgba(255,255,255,0.72)', backdropFilter: 'blur(20px)', WebkitBackdropFilter: 'blur(20px)' }}>
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="flex items-center justify-between h-11">

            {/* Logo */}
            <Link to="/" className="flex items-center gap-2 group">
              <div className="w-7 h-7 rounded-xl flex items-center justify-center shadow-sm transition-transform duration-300 group-hover:scale-105"
                   style={{ background: 'linear-gradient(135deg, #0d9488, #14b8a6, #06b6d4)' }}>
                <Languages className="w-3.5 h-3.5 text-white" />
              </div>
              <div className="flex items-center gap-1.5">
                <span className="text-sm font-extrabold tracking-tight text-slate-800">
                  Jan<span className="text-teal-600">Bhasha</span>
                </span>
                <span className="hidden sm:inline-block px-1.5 py-0.5 text-[9px] font-bold bg-teal-50 text-teal-700 border border-teal-200 rounded-full">
                  जनभाषा AI
                </span>
              </div>
            </Link>

            {/* Desktop Nav */}
            <nav className="hidden md:flex items-center space-x-0.5">
              <Link to="/translate" className={navLinkClass('/translate')}>
                <Sparkles className="w-3 h-3 text-teal-500" />
                Translate
              </Link>
              {user && (
                <>
                  <Link to="/dashboard" className={navLinkClass('/dashboard')}>
                    <LayoutDashboard className="w-3 h-3 text-violet-500" />
                    Dashboard
                  </Link>
                  <Link to="/history" className={navLinkClass('/history')}>
                    <History className="w-3 h-3 text-sky-500" />
                    History
                  </Link>
                  <Link to="/glossary" className={navLinkClass('/glossary')}>
                    <BookOpen className="w-3 h-3 text-emerald-500" />
                    Glossary
                  </Link>
                  {user.role === 'super_admin' && (
                    <Link to="/admin" className={`${navLinkClass('/admin')} border border-amber-200 bg-amber-50/60 text-amber-700 hover:bg-amber-50`}>
                      <Shield className="w-3 h-3 text-amber-500" />
                      Admin
                    </Link>
                  )}
                </>
              )}
            </nav>

            {/* Desktop Actions */}
            <div className="hidden md:flex items-center gap-2">
              {user ? (
                <div className="flex items-center gap-1.5">
                  <div className="flex items-center gap-1.5 px-2 py-1 rounded-lg bg-white/70 border border-white/80 shadow-sm">
                    <div className="w-5 h-5 rounded-full flex items-center justify-center text-[10px] font-bold text-white"
                         style={{ background: 'linear-gradient(135deg, #0d9488, #14b8a6)' }}>
                      {user.name?.charAt(0)?.toUpperCase() || 'U'}
                    </div>
                    <p className="text-xs font-bold text-slate-700">{user.name}</p>
                  </div>
                  <button
                    onClick={handleLogout}
                    className="p-1.5 text-slate-400 hover:text-rose-500 hover:bg-rose-50 rounded-lg transition-colors"
                    title="Logout"
                  >
                    <LogOut className="w-3.5 h-3.5" />
                  </button>
                </div>
              ) : (
                <div className="flex items-center gap-1.5">
                  <button
                    onClick={() => onOpenAuth('login')}
                    className="px-3 py-1 text-xs font-semibold text-slate-600 hover:text-slate-900 hover:bg-white/60 rounded-lg transition-all"
                  >
                    Sign In
                  </button>
                  <button
                    onClick={() => onOpenAuth('register')}
                    className="px-3 py-1 text-xs font-bold text-white rounded-lg shadow-sm transition-all hover:scale-105 btn-teal"
                  >
                    Get Started
                  </button>
                </div>
              )}
            </div>

            {/* Mobile Toggle */}
            <div className="md:hidden flex items-center">
              <button
                onClick={() => setMobileMenuOpen(!mobileMenuOpen)}
                className="p-2 rounded-xl text-slate-500 hover:text-slate-800 hover:bg-white/60 transition-colors"
              >
                {mobileMenuOpen ? <X className="w-5 h-5" /> : <Menu className="w-5 h-5" />}
              </button>
            </div>
          </div>
        </div>

        {/* Mobile Menu */}
        {mobileMenuOpen && (
          <div className="md:hidden px-4 pt-2 pb-5 space-y-1 animate-fade-in border-t border-white/60" style={{ background: 'rgba(255,255,255,0.85)' }}>
            <Link to="/translate" onClick={() => setMobileMenuOpen(false)}
              className="flex items-center gap-2 px-3 py-2.5 text-sm font-semibold text-slate-600 hover:text-teal-700 hover:bg-teal-50 rounded-xl transition-colors">
              <Sparkles className="w-4 h-4 text-teal-500" /> Translate Studio
            </Link>
            {user ? (
              <>
                <Link to="/dashboard" onClick={() => setMobileMenuOpen(false)}
                  className="flex items-center gap-2 px-3 py-2.5 text-sm font-semibold text-slate-600 hover:text-violet-700 hover:bg-violet-50 rounded-xl transition-colors">
                  <LayoutDashboard className="w-4 h-4 text-violet-500" /> Dashboard
                </Link>
                <Link to="/history" onClick={() => setMobileMenuOpen(false)}
                  className="flex items-center gap-2 px-3 py-2.5 text-sm font-semibold text-slate-600 hover:text-sky-700 hover:bg-sky-50 rounded-xl transition-colors">
                  <History className="w-4 h-4 text-sky-500" /> Translation History
                </Link>
                <Link to="/glossary" onClick={() => setMobileMenuOpen(false)}
                  className="flex items-center gap-2 px-3 py-2.5 text-sm font-semibold text-slate-600 hover:text-emerald-700 hover:bg-emerald-50 rounded-xl transition-colors">
                  <BookOpen className="w-4 h-4 text-emerald-500" /> Glossary Terms
                </Link>
                {user.role === 'super_admin' && (
                  <Link to="/admin" onClick={() => setMobileMenuOpen(false)}
                    className="flex items-center gap-2 px-3 py-2.5 text-sm font-semibold text-amber-700 hover:bg-amber-50 rounded-xl transition-colors">
                    <Shield className="w-4 h-4 text-amber-500" /> Admin Portal
                  </Link>
                )}
                <div className="flex items-center justify-between pt-3 mt-1 border-t border-slate-100">
                  <div className="flex items-center gap-2">
                    <div className="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold text-white"
                         style={{ background: 'linear-gradient(135deg, #0d9488, #14b8a6)' }}>
                      {user.name?.charAt(0)?.toUpperCase() || 'U'}
                    </div>
                    <span className="text-sm font-semibold text-slate-700">{user.name}</span>
                  </div>
                  <button onClick={() => { handleLogout(); setMobileMenuOpen(false); }}
                    className="text-sm text-rose-500 font-semibold flex items-center gap-1 px-3 py-1.5 hover:bg-rose-50 rounded-lg transition-colors">
                    <LogOut className="w-4 h-4" /> Sign Out
                  </button>
                </div>
              </>
            ) : (
              <div className="pt-3 space-y-2 border-t border-slate-100">
                <button onClick={() => { onOpenAuth('login'); setMobileMenuOpen(false); }}
                  className="w-full py-2.5 text-sm font-semibold text-slate-700 border border-slate-200 rounded-xl hover:bg-white/80 transition-colors">
                  Sign In
                </button>
                <button onClick={() => { onOpenAuth('register'); setMobileMenuOpen(false); }}
                  className="w-full py-2.5 text-sm font-bold text-white rounded-xl btn-teal">
                  Get Started Free
                </button>
              </div>
            )}
          </div>
        )}
      </header>
    </>
  );
}
