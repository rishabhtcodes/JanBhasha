import React, { useState, useEffect } from 'react';
import api from '../api';
import { Shield, Building, Users, Key } from 'lucide-react';

const ROLE_STYLE = {
  super_admin: 'bg-amber-50 text-amber-700 border-amber-200',
  admin: 'bg-violet-50 text-violet-700 border-violet-100',
  translator: 'bg-teal-50 text-teal-700 border-teal-100',
};

export default function AdminPage() {
  const [organisations, setOrganisations] = useState([]);
  const [users, setUsers] = useState([]);

  useEffect(() => { fetchAdminData(); }, []);

  const fetchAdminData = async () => {
    try {
      const orgRes = await api.get('/admin/organisations');
      setOrganisations(orgRes.data || []);
    } catch {
      setOrganisations([
        { id: '1', name: 'Ministry of Finance', api_key: 'jb_live_9f82a17b4c8149e', monthly_quota: 2000000, current_usage: 440000 },
        { id: '2', name: 'National Health Authority', api_key: 'jb_live_3c91d84e9a012ff', monthly_quota: 1000000, current_usage: 215000 },
        { id: '3', name: 'Digital India Corp.', api_key: 'jb_live_7de3891cfa22100', monthly_quota: 500000, current_usage: 98000 },
      ]);
    }
    try {
      const userRes = await api.get('/admin/users');
      setUsers(userRes.data || []);
    } catch {
      setUsers([
        { id: '1', name: 'Super Admin', email: 'rishabhtiwari3538@gmail.com', role: 'super_admin' },
        { id: '2', name: 'Finance Admin', email: 'finance@janbhasha.in', role: 'admin' },
        { id: '3', name: 'Ravi Translator', email: 'translator@janbhasha.in', role: 'translator' },
      ]);
    }
  };

  return (
    <div className="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8 space-y-8">
      {/* Header */}
      <div>
        <h1 className="text-2xl sm:text-3xl font-extrabold text-slate-900 flex items-center gap-3">
          <div className="w-10 h-10 rounded-2xl flex items-center justify-center bg-amber-50 flex-shrink-0">
            <Shield className="w-5 h-5 text-amber-600" />
          </div>
          Super Admin Console
        </h1>
        <p className="text-slate-500 text-sm mt-1.5">Manage API keys, client organisation quotas, and user privileges.</p>
      </div>

      {/* Organisations */}
      <div className="neu-card overflow-hidden">
        <div className="px-5 sm:px-6 py-5 border-b border-slate-100 flex items-center gap-2">
          <Building className="w-5 h-5 text-teal-600 flex-shrink-0" />
          <h3 className="font-bold text-slate-800">Registered Client Organisations</h3>
        </div>

        {/* Desktop Table */}
        <div className="hidden sm:block overflow-x-auto">
          <table className="w-full text-left border-collapse">
            <thead>
              <tr className="border-b border-slate-100 text-xs text-slate-400 font-bold uppercase tracking-wider"
                  style={{ background: 'rgba(255,255,255,0.5)' }}>
                <th className="px-5 py-4">Organisation</th>
                <th className="px-5 py-4">API Key</th>
                <th className="px-5 py-4">Monthly Quota</th>
                <th className="px-5 py-4">Usage</th>
              </tr>
            </thead>
            <tbody className="divide-y divide-slate-100/80 text-sm">
              {organisations.map((org) => {
                const pct = Math.round(((org.current_usage || 0) / (org.monthly_quota || 1)) * 100);
                const barColor = pct > 80 ? '#f43f5e' : pct > 50 ? '#f59e0b' : '#0d9488';
                return (
                  <tr key={org.id} className="hover:bg-white/50 transition-colors">
                    <td className="px-5 py-4 font-bold text-slate-800">{org.name}</td>
                    <td className="px-5 py-4">
                      <span className="font-mono text-xs text-teal-700 bg-teal-50 border border-teal-100 px-3 py-1.5 rounded-xl flex items-center gap-1.5 w-fit">
                        <Key className="w-3 h-3" />
                        {org.api_key}
                      </span>
                    </td>
                    <td className="px-5 py-4 text-slate-600 font-semibold">{org.monthly_quota?.toLocaleString()} chars</td>
                    <td className="px-5 py-4">
                      <div className="flex items-center gap-2">
                        <div className="w-20 h-1.5 rounded-full overflow-hidden" style={{ background: '#dde2e9' }}>
                          <div className="h-full rounded-full" style={{ width: `${pct}%`, background: barColor }} />
                        </div>
                        <span className="text-xs font-bold" style={{ color: barColor }}>{pct}%</span>
                      </div>
                    </td>
                  </tr>
                );
              })}
            </tbody>
          </table>
        </div>

        {/* Mobile Card List */}
        <div className="sm:hidden divide-y divide-slate-100/80">
          {organisations.map((org) => {
            const pct = Math.round(((org.current_usage || 0) / (org.monthly_quota || 1)) * 100);
            const barColor = pct > 80 ? '#f43f5e' : pct > 50 ? '#f59e0b' : '#0d9488';
            return (
              <div key={org.id} className="p-4 space-y-3">
                <p className="font-bold text-slate-800 text-sm">{org.name}</p>
                <div className="font-mono text-xs text-teal-700 bg-teal-50 border border-teal-100 px-3 py-2 rounded-xl flex items-center gap-1.5 overflow-x-auto">
                  <Key className="w-3 h-3 flex-shrink-0" />
                  <span className="truncate">{org.api_key}</span>
                </div>
                <div className="flex items-center justify-between text-xs">
                  <span className="text-slate-500 font-semibold">{org.monthly_quota?.toLocaleString()} chars quota</span>
                  <div className="flex items-center gap-2">
                    <div className="w-20 h-1.5 rounded-full overflow-hidden" style={{ background: '#dde2e9' }}>
                      <div className="h-full rounded-full" style={{ width: `${pct}%`, background: barColor }} />
                    </div>
                    <span className="font-bold" style={{ color: barColor }}>{pct}%</span>
                  </div>
                </div>
              </div>
            );
          })}
        </div>
      </div>

      {/* Users */}
      <div className="neu-card overflow-hidden">
        <div className="px-5 sm:px-6 py-5 border-b border-slate-100 flex items-center gap-2">
          <Users className="w-5 h-5 text-violet-600 flex-shrink-0" />
          <h3 className="font-bold text-slate-800">Platform User Accounts</h3>
        </div>

        {/* Desktop Table */}
        <div className="hidden sm:block overflow-x-auto">
          <table className="w-full text-left border-collapse">
            <thead>
              <tr className="border-b border-slate-100 text-xs text-slate-400 font-bold uppercase tracking-wider"
                  style={{ background: 'rgba(255,255,255,0.5)' }}>
                <th className="px-5 py-4">Full Name</th>
                <th className="px-5 py-4">Email Address</th>
                <th className="px-5 py-4">Role</th>
              </tr>
            </thead>
            <tbody className="divide-y divide-slate-100/80 text-sm">
              {users.map((usr) => (
                <tr key={usr.id} className="hover:bg-white/50 transition-colors">
                  <td className="px-5 py-4">
                    <div className="flex items-center gap-2.5">
                      <div className="w-8 h-8 rounded-xl flex items-center justify-center text-xs font-bold text-white flex-shrink-0"
                           style={{ background: 'linear-gradient(135deg, #0d9488, #14b8a6)' }}>
                        {usr.name?.charAt(0)?.toUpperCase()}
                      </div>
                      <span className="font-bold text-slate-800">{usr.name}</span>
                    </div>
                  </td>
                  <td className="px-5 py-4 text-slate-500 font-mono text-xs">{usr.email}</td>
                  <td className="px-5 py-4">
                    <span className={`text-xs font-bold uppercase tracking-wider px-2.5 py-1 rounded-xl border ${ROLE_STYLE[usr.role] || ROLE_STYLE.translator}`}>
                      {usr.role?.replace('_', ' ')}
                    </span>
                  </td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>

        {/* Mobile Card List */}
        <div className="sm:hidden divide-y divide-slate-100/80">
          {users.map((usr) => (
            <div key={usr.id} className="p-4 flex items-center gap-3">
              <div className="w-10 h-10 rounded-xl flex items-center justify-center text-sm font-bold text-white flex-shrink-0"
                   style={{ background: 'linear-gradient(135deg, #0d9488, #14b8a6)' }}>
                {usr.name?.charAt(0)?.toUpperCase()}
              </div>
              <div className="flex-1 min-w-0">
                <p className="font-bold text-slate-800 text-sm">{usr.name}</p>
                <p className="text-slate-500 font-mono text-xs truncate">{usr.email}</p>
              </div>
              <span className={`text-xs font-bold uppercase tracking-wider px-2.5 py-1 rounded-xl border flex-shrink-0 ${ROLE_STYLE[usr.role] || ROLE_STYLE.translator}`}>
                {usr.role?.replace('_', ' ')}
              </span>
            </div>
          ))}
        </div>
      </div>
    </div>
  );
}
