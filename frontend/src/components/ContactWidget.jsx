import React, { useState } from 'react';
import api from '../api';
import { MessageSquare, X, Send, CheckCircle2, AlertCircle } from 'lucide-react';

export default function ContactWidget() {
  const [open, setOpen] = useState(false);
  const [formData, setFormData] = useState({ name: '', email: '', subject: '', reason: '' });
  const [status, setStatus] = useState({ loading: false, success: false, error: '' });

  const handleSubmit = async (e) => {
    e.preventDefault();
    setStatus({ loading: true, success: false, error: '' });

    try {
      await api.post('/contact', formData);
      setStatus({ loading: false, success: true, error: '' });
      setFormData({ name: '', email: '', subject: '', reason: '' });
    } catch (err) {
      setStatus({ loading: false, success: false, error: 'Failed to submit inquiry. Please try again.' });
    }
  };

  return (
    <div className="fixed bottom-6 right-6 z-40">
      {!open ? (
        <button
          onClick={() => setOpen(true)}
          className="flex items-center gap-2.5 px-4 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-full shadow-2xl shadow-indigo-500/30 hover:scale-105 transition-all font-semibold text-sm"
        >
          <MessageSquare className="w-5 h-5" />
          <span>Support & Feedback</span>
        </button>
      ) : (
        <div className="w-80 sm:w-96 glass-panel rounded-2xl p-5 shadow-2xl border border-slate-800 animate-slide-up">
          <div className="flex items-center justify-between border-b border-slate-800 pb-3 mb-4">
            <div className="flex items-center gap-2">
              <MessageSquare className="w-5 h-5 text-indigo-400" />
              <h3 className="font-bold text-white text-base">Contact JanBhasha Team</h3>
            </div>
            <button onClick={() => setOpen(false)} className="text-slate-400 hover:text-white p-1">
              <X className="w-5 h-5" />
            </button>
          </div>

          {status.success ? (
            <div className="text-center py-6 space-y-3">
              <CheckCircle2 className="w-12 h-12 text-emerald-400 mx-auto" />
              <h4 className="font-bold text-white text-lg">Inquiry Sent!</h4>
              <p className="text-xs text-slate-400">Thank you for reaching out. We have sent a confirmation copy to your email.</p>
              <button
                onClick={() => setStatus({ loading: false, success: false, error: '' })}
                className="mt-2 text-xs text-indigo-400 hover:underline font-semibold"
              >
                Send Another Message
              </button>
            </div>
          ) : (
            <form onSubmit={handleSubmit} className="space-y-3">
              {status.error && (
                <div className="p-2 rounded bg-rose-500/10 border border-rose-500/20 text-rose-400 text-xs flex items-center gap-1">
                  <AlertCircle className="w-3.5 h-3.5 shrink-0" />
                  <span>{status.error}</span>
                </div>
              )}
              <div>
                <input
                  type="text"
                  required
                  placeholder="Your Name"
                  value={formData.name}
                  onChange={(e) => setFormData({ ...formData, name: e.target.value })}
                  className="w-full px-3 py-2 text-sm bg-slate-900 border border-slate-800 rounded-lg text-white placeholder-slate-500 focus:outline-none focus:border-indigo-500"
                />
              </div>
              <div>
                <input
                  type="email"
                  required
                  placeholder="Your Email"
                  value={formData.email}
                  onChange={(e) => setFormData({ ...formData, email: e.target.value })}
                  className="w-full px-3 py-2 text-sm bg-slate-900 border border-slate-800 rounded-lg text-white placeholder-slate-500 focus:outline-none focus:border-indigo-500"
                />
              </div>
              <div>
                <input
                  type="text"
                  required
                  placeholder="Subject"
                  value={formData.subject}
                  onChange={(e) => setFormData({ ...formData, subject: e.target.value })}
                  className="w-full px-3 py-2 text-sm bg-slate-900 border border-slate-800 rounded-lg text-white placeholder-slate-500 focus:outline-none focus:border-indigo-500"
                />
              </div>
              <div>
                <textarea
                  required
                  rows={3}
                  placeholder="How can we help you?"
                  value={formData.reason}
                  onChange={(e) => setFormData({ ...formData, reason: e.target.value })}
                  className="w-full px-3 py-2 text-sm bg-slate-900 border border-slate-800 rounded-lg text-white placeholder-slate-500 focus:outline-none focus:border-indigo-500"
                ></textarea>
              </div>
              <button
                type="submit"
                disabled={status.loading}
                className="w-full py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white font-medium text-sm rounded-lg flex items-center justify-center gap-2 transition-all disabled:opacity-50"
              >
                {status.loading ? 'Sending...' : 'Send Inquiry'}
                <Send className="w-4 h-4" />
              </button>
            </form>
          )}
        </div>
      )}
    </div>
  );
}
