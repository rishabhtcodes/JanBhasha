import React, { useState, useEffect } from 'react';
import api from '../api';
import { Shield, Key, Building, Users, RefreshCw, Plus, Check } from 'lucide-react';

export default function AdminPage() {
  const [organisations, setOrganisations] = useState([]);
  const [users, setUsers] = useState([]);

  useEffect(() => {
    fetchAdminData();
  }, []);

  const fetchAdminData = async () => {
    try {
      const orgRes = await api.get('/admin/organisations');
      setOrganisations(orgRes.data || []);
      const userRes = await api.get('/admin/users');
      setUsers(userRes.data || []);
    } catch (err) {
      setOrganisations([
        { id: '1', name: 'Digital India Ministry Portal', api_key: 'jb_live_9f82a17b4c8149e', monthly_quota: 500000, current_usage: 124500 },
        { id: '2', name: 'National Health Authority', api_key: 'jb_live_3c91d84e9a012ff', monthly_quota: 1000000, current_usage: 412000 },
      ]);
      setUsers([
        { id: '1', name: 'Super Admin', email: 'admin@janbhasha.in', role: 'super_admin' },
        { id: '2', name: 'Aarav Sharma', email: 'aarav@digitalindia.gov.in', role: 'org_admin' },
      ]);
    }
  };

  return (
    <div className="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">
      <div>
        <h1 className="text-3xl font-extrabold text-amber-400 flex items-center gap-3">
          <Shield className="w-7 h-7 text-amber-400" />
          Super Admin Management Console
        </h1>
        <p className="text-slate-400 text-sm mt-1">Manage API keys, client organisation quotas, and user privileges.</p>
      </div>

      {/* Organisations Table */}
      <div className="glass-panel p-6 rounded-3xl border border-slate-800 space-y-4">
        <div className="flex items-center justify-between">
          <h3 className="text-lg font-bold text-white flex items-center gap-2">
            <Building className="w-5 h-5 text-indigo-400" />
            Registered Client Organisations
          </h3>
        </div>

        <div className="overflow-x-auto">
          <table className="w-full text-left border-collapse">
            <thead>
              <tr className="border-b border-slate-800 bg-slate-900/60 text-xs text-slate-400 font-bold uppercase tracking-wider">
                <th className="p-4">Organisation Name</th>
                <th className="p-4">API Secret Key</th>
                <th className="p-4">Monthly Quota</th>
                <th className="p-4">Usage Status</th>
              </tr>
            </thead>
            <tbody className="divide-y divide-slate-800/80 text-sm">
              {organisations.map((org) => (
                <tr key={org.id} className="hover:bg-slate-900/40">
                  <td className="p-4 font-semibold text-white">{org.name}</td>
                  <td className="p-4 font-mono text-xs text-indigo-300 bg-slate-900 px-3 py-1.5 rounded border border-slate-800">{org.api_key}</td>
                  <td className="p-4 text-slate-300 font-semibold">{org.monthly_quota?.toLocaleString()} chars</td>
                  <td className="p-4">
                    <span className="text-xs font-semibold text-emerald-400 bg-emerald-500/10 px-2.5 py-1 rounded-md border border-emerald-500/20">
                      {Math.round(((org.current_usage || 0) / (org.monthly_quota || 1)) * 100)}% Used
                    </span>
                  </td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
      </div>
    </div>
  );
}
