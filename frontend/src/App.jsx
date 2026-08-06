import React, { useState } from 'react';
import { BrowserRouter as Router, Routes, Route, Navigate } from 'react-router-dom';
import { AuthProvider, useAuth } from './context/AuthContext';
import Navbar from './components/Navbar';
import AuthModal from './components/AuthModal';
import ContactWidget from './components/ContactWidget';
import NewsWidget from './components/NewsWidget';

import LandingPage from './pages/LandingPage';
import TranslationPage from './pages/TranslationPage';
import DashboardPage from './pages/DashboardPage';
import HistoryPage from './pages/HistoryPage';
import GlossaryPage from './pages/GlossaryPage';
import AdminPage from './pages/AdminPage';

function ProtectedRoute({ children, adminOnly = false }) {
  const { user, loading } = useAuth();
  if (loading) return (
    <div className="min-h-screen flex items-center justify-center">
      <div className="flex flex-col items-center gap-4">
        <div className="w-14 h-14 rounded-full neu-card flex items-center justify-center animate-float">
          <span className="text-2xl">🌐</span>
        </div>
        <p className="text-slate-500 text-sm font-medium">Loading JanBhasha...</p>
      </div>
    </div>
  );
  if (!user) return <Navigate to="/" replace />;
  if (adminOnly && user.role !== 'super_admin') return <Navigate to="/dashboard" replace />;
  return children;
}

function MainApp() {
  const [authModalOpen, setAuthModalOpen] = useState(false);
  const [authMode, setAuthMode] = useState('login');
  const [newsOpen, setNewsOpen] = useState(false);
  const [contactOpen, setContactOpen] = useState(false);

  const handleOpenAuth = (mode = 'login') => {
    setAuthMode(mode);
    setAuthModalOpen(true);
  };

  return (
    <div className="min-h-screen flex flex-col justify-between" style={{ background: '#e8ecf1' }}>
      <Navbar onOpenAuth={handleOpenAuth} />

      <main className="flex-1">
        <Routes>
          <Route path="/" element={<LandingPage onOpenAuth={handleOpenAuth} />} />
          <Route path="/translate" element={<TranslationPage />} />
          <Route path="/dashboard" element={<ProtectedRoute><DashboardPage /></ProtectedRoute>} />
          <Route path="/history" element={<ProtectedRoute><HistoryPage /></ProtectedRoute>} />
          <Route path="/glossary" element={<ProtectedRoute><GlossaryPage /></ProtectedRoute>} />
          <Route path="/admin" element={<ProtectedRoute adminOnly={true}><AdminPage /></ProtectedRoute>} />
          <Route path="*" element={<Navigate to="/" replace />} />
        </Routes>
      </main>

      {/* Footer — compact pill layout */}
      <footer className="py-4 mt-12 flex justify-center">
        <div className="glass-panel px-6 py-2 rounded-full border border-white/70 shadow-sm text-center">
          <p className="text-slate-400 text-[11px] font-medium">
            © 2026 JanBhasha (जनभाषा) — Multi-Tenant Indic SaaS Translation Platform
          </p>
        </div>
      </footer>

      <AuthModal
        isOpen={authModalOpen}
        onClose={() => setAuthModalOpen(false)}
        mode={authMode}
        setMode={setAuthMode}
      />

      {/* Unified FAB Group — extreme bottom right corner */}
      <div className="fixed bottom-4 right-4 z-40 flex flex-col-reverse items-end gap-2.5">
        <ContactWidget
          isOpen={contactOpen}
          onOpen={() => { setContactOpen(true); setNewsOpen(false); }}
          onClose={() => setContactOpen(false)}
        />
        <NewsWidget
          isOpen={newsOpen}
          onOpen={() => { setNewsOpen(true); setContactOpen(false); }}
          onClose={() => setNewsOpen(false)}
        />
      </div>
    </div>
  );
}

export default function App() {
  return (
    <AuthProvider>
      <Router>
        <MainApp />
      </Router>
    </AuthProvider>
  );
}
