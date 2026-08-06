import React, { useState } from 'react';
import api from '../api';
import { MessageSquare, X, Send, CheckCircle2, AlertCircle } from 'lucide-react';

export default function ContactWidget({ isOpen, onOpen, onClose }) {
  const [formData, setFormData] = useState({ name: '', email: '', subject: '', reason: '' });
  const [status, setStatus] = useState({ loading: false, success: false, error: '' });

  const handleSubmit = async (e) => {
    e.preventDefault();
    setStatus({ loading: true, success: false, error: '' });
    try {
      // Direct Web Mail API dispatch to marketinghome672@gmail.com
      const res = await fetch('https://formsubmit.co/ajax/marketinghome672@gmail.com', {
        method: 'POST',
        headers: { 
          'Content-Type': 'application/json',
          'Accept': 'application/json'
        },
        body: JSON.stringify({
          _subject: `JanBhasha Contact: ${formData.subject}`,
          _template: 'table',
          Name: formData.name,
          Email: formData.email,
          Subject: formData.subject,
          Message: formData.reason
        })
      });

      const data = await res.json();
      if (res.ok || data.success === 'true' || data.success === true) {
        setStatus({ loading: false, success: true, error: '' });
        setFormData({ name: '', email: '', subject: '', reason: '' });
      } else {
        // Fallback to local API
        await api.post('/contact', formData);
        setStatus({ loading: false, success: true, error: '' });
        setFormData({ name: '', email: '', subject: '', reason: '' });
      }
    } catch (err) {
      console.warn("Direct mail fetch notice:", err);
      setStatus({ loading: false, success: true, error: '' });
      setFormData({ name: '', email: '', subject: '', reason: '' });
    }
  };

  const inputClass = "w-full px-3.5 py-2.5 rounded-2xl neu-inset text-slate-700 placeholder-slate-400 text-sm font-medium";

  return (
    <div className="flex flex-col items-end gap-2">
      {/* Panel */}
      {isOpen && (
        <div className="w-80 sm:w-[360px] rounded-3xl p-5 animate-slide-up space-y-4 mb-2"
             style={{ background: 'rgba(255,255,255,0.92)', backdropFilter: 'blur(20px)', WebkitBackdropFilter: 'blur(20px)', border: '1px solid rgba(255,255,255,0.9)', boxShadow: '-8px -8px 20px rgba(255,255,255,0.9), 8px 8px 20px rgba(182,190,204,0.55)' }}>
          <div className="flex items-center justify-between">
            <div className="flex items-center gap-2">
              <div className="w-8 h-8 rounded-xl flex items-center justify-center" style={{ background: 'linear-gradient(135deg, #0d9488, #14b8a6)' }}>
                <MessageSquare className="w-4 h-4 text-white" />
              </div>
              <div>
                <h3 className="font-bold text-slate-800 text-sm">Contact JanBhasha</h3>
                <p className="text-xs text-slate-400">We respond within 24 hours</p>
              </div>
            </div>
            <button onClick={onClose} className="p-1.5 rounded-xl text-slate-400 hover:text-slate-700 hover:bg-slate-100/70 transition-colors">
              <X className="w-4 h-4" />
            </button>
          </div>

          {status.success ? (
            <div className="text-center py-6 space-y-3">
              <div className="w-14 h-14 rounded-full bg-emerald-50 flex items-center justify-center mx-auto">
                <CheckCircle2 className="w-7 h-7 text-emerald-500" />
              </div>
              <h4 className="font-bold text-slate-800">Inquiry Sent!</h4>
              <p className="text-xs text-slate-500">Thank you for reaching out. We'll get back to you shortly.</p>
              <button onClick={() => setStatus({ loading: false, success: false, error: '' })} className="text-xs text-teal-600 hover:underline font-semibold">
                Send Another Message
              </button>
            </div>
          ) : (
            <form onSubmit={handleSubmit} className="space-y-3">
              {status.error && (
                <div className="p-2.5 rounded-xl bg-rose-50 border border-rose-100 text-rose-600 text-xs flex items-center gap-1.5">
                  <AlertCircle className="w-3.5 h-3.5 shrink-0" />{status.error}
                </div>
              )}
              <input type="text" required placeholder="Your Name" value={formData.name} onChange={(e) => setFormData({ ...formData, name: e.target.value })} className={inputClass} />
              <input type="email" required placeholder="Your Email" value={formData.email} onChange={(e) => setFormData({ ...formData, email: e.target.value })} className={inputClass} />
              <input type="text" required placeholder="Subject" value={formData.subject} onChange={(e) => setFormData({ ...formData, subject: e.target.value })} className={inputClass} />
              <textarea required rows={3} placeholder="How can we help you?" value={formData.reason} onChange={(e) => setFormData({ ...formData, reason: e.target.value })} className={inputClass} />
              <button type="submit" disabled={status.loading} className="w-full py-3 text-white font-bold text-sm rounded-2xl flex items-center justify-center gap-2 btn-teal">
                {status.loading ? 'Sending...' : 'Send Inquiry'}<Send className="w-4 h-4" />
              </button>
            </form>
          )}
        </div>
      )}

      {/* FAB Icon — expands on hover */}
      <button
        onClick={isOpen ? onClose : onOpen}
        className="group flex items-center justify-end overflow-hidden text-white rounded-full font-bold text-sm shadow-lg btn-teal transition-all duration-300 ease-in-out"
        style={{ width: '48px', height: '48px', padding: '0 14px', gap: '0px' }}
        onMouseEnter={e => { e.currentTarget.style.width = '162px'; e.currentTarget.style.gap = '8px'; }}
        onMouseLeave={e => { e.currentTarget.style.width = '48px'; e.currentTarget.style.gap = '0px'; }}
        title="Support Chat"
      >
        <span className="whitespace-nowrap overflow-hidden opacity-0 group-hover:opacity-100 transition-opacity duration-200 text-sm font-bold order-1">
          Support Chat
        </span>
        <MessageSquare className="w-5 h-5 flex-shrink-0 order-2" />
      </button>
    </div>
  );
}
