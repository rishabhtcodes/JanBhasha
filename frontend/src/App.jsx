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
  if (loading) return <div className="min-h-screen flex items-center justify-center text-amber-400">Loading JanBhasha...</div>;
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
    <div className="min-h-screen flex flex-col justify-between bg-slate-950 text-slate-100">
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

      <footer className="glass-panel border-t border-slate-800/80 py-8 mt-16 text-center text-xs text-slate-500">
        <div className="max-w-7xl mx-auto px-4 flex flex-col sm:flex-row items-center justify-between gap-4">
          <p>© 2026 JanBhasha (जनभाषा) — Multi-Tenant Indic SaaS Translation Platform.</p>
          <div className="flex gap-4 font-semibold text-slate-400">
            <span className="text-amber-400">Vercel React SPA</span>
            <span>•</span>
            <span className="text-emerald-400">Render Laravel REST API</span>
          </div>
        </div>
      </footer>

      <AuthModal
        isOpen={authModalOpen}
        onClose={() => setAuthModalOpen(false)}
        mode={authMode}
        setMode={setAuthMode}
      />

      <NewsWidget
        isOpen={newsOpen}
        onToggle={() => setNewsOpen(!newsOpen)}
        onCloseOther={() => setContactOpen(false)}
      />

      <ContactWidget
        isOpen={contactOpen}
        onToggle={() => setContactOpen(!contactOpen)}
        onCloseOther={() => setNewsOpen(false)}
      />
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
