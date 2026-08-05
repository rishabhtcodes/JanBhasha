import React, { useState } from 'react';
import { Link, useNavigate } from 'react-router-dom';
import { useAuth } from '../context/AuthContext';
import { Languages, LayoutDashboard, History, BookOpen, Shield, LogOut, Sparkles, Menu, X } from 'lucide-react';

export default function Navbar({ onOpenAuth }) {
  const { user, logout } = useAuth();
  const [mobileMenuOpen, setMobileMenuOpen] = useState(false);
  const navigate = useNavigate();

  const handleLogout = async () => {
    await logout();
    navigate('/');
  };

  return (
    <>
      {/* Tricolor India Flag Top Bar */}
      <div className="h-1.5 w-full bg-gradient-to-r from-amber-500 via-white to-emerald-600 sticky top-0 z-50"></div>

      <header className="sticky top-1.5 z-40 glass-panel border-b border-slate-800/80 bg-slate-950/90 backdrop-blur-md">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="flex items-center justify-between h-16">
            {/* Logo */}
            <Link to="/" className="flex items-center gap-3 group">
              <div className="w-10 h-10 rounded-xl bg-gradient-to-tr from-amber-500 via-orange-600 to-emerald-600 flex items-center justify-center shadow-lg shadow-orange-500/25 group-hover:scale-105 transition-transform duration-300">
                <Languages className="w-5 h-5 text-white" />
              </div>
              <div>
                <span className="text-xl font-extrabold tracking-tight text-white group-hover:text-amber-400 transition-colors">
                  Jan<span className="text-amber-500">Bhasha</span>
                </span>
                <span className="hidden sm:inline-block ml-2 px-2 py-0.5 text-[10px] font-bold bg-amber-500/10 text-amber-400 border border-amber-500/20 rounded-full">
                  जनभाषा AI
                </span>
              </div>
            </Link>

            {/* Navigation Links */}
            <nav className="hidden md:flex items-center space-x-1">
              <Link to="/translate" className="px-3.5 py-2 text-sm font-medium text-slate-300 hover:text-white hover:bg-slate-800/60 rounded-lg transition-all flex items-center gap-2">
                <Sparkles className="w-4 h-4 text-amber-400" />
                Translate Studio
              </Link>

              {user && (
                <>
                  <Link to="/dashboard" className="px-3.5 py-2 text-sm font-medium text-slate-300 hover:text-white hover:bg-slate-800/60 rounded-lg transition-all flex items-center gap-2">
                    <LayoutDashboard className="w-4 h-4 text-purple-400" />
                    Dashboard
                  </Link>
                  <Link to="/history" className="px-3.5 py-2 text-sm font-medium text-slate-300 hover:text-white hover:bg-slate-800/60 rounded-lg transition-all flex items-center gap-2">
                    <History className="w-4 h-4 text-cyan-400" />
                    History
                  </Link>
                  <Link to="/glossary" className="px-3.5 py-2 text-sm font-medium text-slate-300 hover:text-white hover:bg-slate-800/60 rounded-lg transition-all flex items-center gap-2">
                    <BookOpen className="w-4 h-4 text-emerald-400" />
                    Glossary
                  </Link>
                  {user.role === 'super_admin' && (
                    <Link to="/admin" className="px-3.5 py-2 text-sm font-medium text-amber-400 hover:text-amber-300 hover:bg-amber-500/10 rounded-lg transition-all flex items-center gap-2 border border-amber-500/20">
                      <Shield className="w-4 h-4" />
                      Admin
                    </Link>
                  )}
                </>
              )}
            </nav>

            {/* Actions */}
            <div className="hidden md:flex items-center gap-3">
              {user ? (
                <div className="flex items-center gap-3">
                  <div className="flex items-center gap-2 px-3 py-1.5 rounded-lg bg-slate-900 border border-slate-800">
                    <div className="w-7 h-7 rounded-full bg-gradient-to-r from-amber-500 to-orange-600 flex items-center justify-center text-xs font-bold text-white uppercase">
                      {user.name?.charAt(0) || 'U'}
                    </div>
                    <span className="text-sm font-medium text-slate-200">{user.name}</span>
                  </div>
                  <button
                    onClick={handleLogout}
                    className="p-2 text-slate-400 hover:text-rose-400 hover:bg-rose-500/10 rounded-lg transition-colors"
                    title="Logout"
                  >
                    <LogOut className="w-5 h-5" />
                  </button>
                </div>
              ) : (
                <div className="flex items-center gap-2">
                  <button
                    onClick={() => onOpenAuth('login')}
                    className="px-4 py-2 text-sm font-medium text-slate-300 hover:text-white transition-colors"
                  >
                    Sign In
                  </button>
                  <button
                    onClick={() => onOpenAuth('register')}
                    className="px-4 py-2 text-sm font-bold text-white bg-gradient-to-r from-amber-500 to-orange-600 hover:from-amber-400 hover:to-orange-500 rounded-lg shadow-md shadow-amber-500/20 transition-all hover:scale-105"
                  >
                    Get Started Free
                  </button>
                </div>
              )}
            </div>

            {/* Mobile menu toggle */}
            <div className="md:hidden flex items-center">
              <button
                onClick={() => setMobileMenuOpen(!mobileMenuOpen)}
                className="p-2 rounded-lg text-slate-400 hover:text-white hover:bg-slate-800"
              >
                {mobileMenuOpen ? <X className="w-6 h-6" /> : <Menu className="w-6 h-6" />}
              </button>
            </div>
          </div>
        </div>

        {/* Mobile Menu */}
        {mobileMenuOpen && (
          <div className="md:hidden glass-panel border-b border-slate-800 px-4 pt-2 pb-6 space-y-2">
            <Link to="/translate" onClick={() => setMobileMenuOpen(false)} className="block px-3 py-2 text-slate-300 hover:bg-slate-800 rounded-md">Translate Studio</Link>
            {user ? (
              <>
                <Link to="/dashboard" onClick={() => setMobileMenuOpen(false)} className="block px-3 py-2 text-slate-300 hover:bg-slate-800 rounded-md">Dashboard</Link>
                <Link to="/history" onClick={() => setMobileMenuOpen(false)} className="block px-3 py-2 text-slate-300 hover:bg-slate-800 rounded-md">Translation History</Link>
                <Link to="/glossary" onClick={() => setMobileMenuOpen(false)} className="block px-3 py-2 text-slate-300 hover:bg-slate-800 rounded-md">Glossary Terms</Link>
                {user.role === 'super_admin' && (
                  <Link to="/admin" onClick={() => setMobileMenuOpen(false)} className="block px-3 py-2 text-amber-400 hover:bg-slate-800 rounded-md">Admin Portal</Link>
                )}
                <button onClick={() => { handleLogout(); setMobileMenuOpen(false); }} className="w-full text-left px-3 py-2 text-rose-400 hover:bg-slate-800 rounded-md">Sign Out</button>
              </>
            ) : (
              <div className="pt-4 border-t border-slate-800 space-y-2">
                <button onClick={() => { onOpenAuth('login'); setMobileMenuOpen(false); }} className="w-full text-center px-4 py-2 border border-slate-700 text-slate-200 rounded-lg">Sign In</button>
                <button onClick={() => { onOpenAuth('register'); setMobileMenuOpen(false); }} className="w-full text-center px-4 py-2 bg-amber-500 text-slate-950 font-bold rounded-lg">Get Started</button>
              </div>
            )}
          </div>
        )}
      </header>
    </>
  );
}
