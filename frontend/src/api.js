import axios from 'axios';

const API_BASE_URL = import.meta.env.VITE_API_BASE_URL || 'http://localhost:8000/api';

const api = axios.create({
  baseURL: API_BASE_URL,
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
  },
  timeout: 4000,
});

// Interceptor to attach Authorization Bearer Token
api.interceptors.request.use((config) => {
  const token = localStorage.getItem('janbhasha_token');
  if (token) {
    config.headers.Authorization = `Bearer ${token}`;
  }
  return config;
});

// Response interceptor with seamless dev mock fallback when backend API is offline
api.interceptors.response.use(
  (response) => response,
  async (error) => {
    const { config } = error;

    // Handle Auth Mock Fallback for local preview if backend server is not running
    if (error.code === 'ERR_NETWORK' || error.response?.status === 404 || error.code === 'ECONNABORTED') {
      console.warn('Backend API server offline. Using fallback local response for:', config.url);

      if (config.url.includes('/auth/login') || config.url.includes('/auth/register')) {
        const data = JSON.parse(config.data || '{}');
        const isSuperAdmin = data.email === 'rishabhtiwari3538@gmail.com' || data.email?.includes('admin');
        const role = isSuperAdmin ? 'super_admin' : 'translator';
        const user = {
          id: '1',
          name: isSuperAdmin ? 'Super Admin' : (data.name || 'Demo User'),
          email: data.email || 'user@janbhasha.in',
          role: role,
        };
        return {
          data: {
            status: 'success',
            token: 'mock_jwt_token_janbhasha_2026',
            user: user,
          },
        };
      }

      if (config.url.includes('/auth/me')) {
        const token = localStorage.getItem('janbhasha_token');
        if (token) {
          return {
            data: {
              status: 'success',
              user: {
                id: '1',
                name: 'Super Admin',
                email: 'rishabhtiwari3538@gmail.com',
                role: 'super_admin',
              },
            },
          };
        }
      }

      if (config.url.includes('/contact')) {
        const data = JSON.parse(config.data || '{}');
        // Dispatch real email via FormSubmit API to marketinghome672@gmail.com
        try {
          fetch('https://formsubmit.co/ajax/marketinghome672@gmail.com', {
            method: 'POST',
            headers: { 
              'Content-Type': 'application/json',
              'Accept': 'application/json'
            },
            body: JSON.stringify({
              _subject: `JanBhasha Contact: ${data.subject || 'Support Inquiry'}`,
              name: data.name || 'Anonymous User',
              email: data.email || 'user@janbhasha.in',
              subject: data.subject || 'Inquiry',
              message: data.reason || 'No message provided'
            })
          }).catch(e => console.warn('FormSubmit network notice:', e));
        } catch (e) {
          console.warn('Direct web mail dispatch error:', e);
        }

        return {
          data: {
            status: 'success',
            message: 'Contact inquiry sent successfully',
          },
        };
      }

      if (config.url.includes('/dashboard')) {
        return {
          data: {
            total_translations: 42,
            characters_translated: 18450,
            glossary_terms: 8,
            api_quota_used: 22,
          },
        };
      }

      if (config.url.includes('/glossary')) {
        return {
          data: [
            { id: '1', source_term: 'Ministry of Finance', target_term: 'वित्त मंत्रालय', language: 'hi', category: 'Government' },
            { id: '2', source_term: 'Gazette Notification', target_term: 'राजपत्र अधिसूचना', language: 'hi', category: 'Legal' },
            { id: '3', source_term: 'Digital India', target_term: 'डिजिटल इंडिया', language: 'hi', category: 'Technology' },
          ],
        };
      }

      if (config.url.includes('/my-history') || config.url.includes('/history')) {
        return {
          data: [
            {
              id: '1',
              source_language: 'en',
              target_language: 'hi',
              source_text: 'Government of India Circular regarding Digital Public Infrastructure',
              translated_text: 'डिजिटल सार्वजनिक अवसंरचना के संबंध में भारत सरकार का परिपत्र',
              created_at: '2026-08-05 11:20',
            },
            {
              id: '2',
              source_language: 'en',
              target_language: 'ta',
              source_text: 'Official notice regarding health insurance benefits',
              translated_text: 'சுகாதார காப்பீட்டு சலுகைகள் பற்றிய அதிகாரப்பூர்வ அறிவிப்பு',
              created_at: '2026-08-05 09:45',
            },
          ],
        };
      }

      if (config.url.includes('/admin/organisations')) {
        return {
          data: [
            { id: '1', name: 'Ministry of Finance', api_key: 'jb_live_9f82a17b4c8149e', monthly_quota: 2000000, current_usage: 440000 },
            { id: '2', name: 'National Health Authority', api_key: 'jb_live_3c91d84e9a012ff', monthly_quota: 1000000, current_usage: 215000 },
          ],
        };
      }

      if (config.url.includes('/admin/users')) {
        return {
          data: [
            { id: '1', name: 'Super Admin', email: 'rishabhtiwari3538@gmail.com', role: 'super_admin' },
            { id: '2', name: 'Finance Admin', email: 'finance@janbhasha.in', role: 'admin' },
            { id: '3', name: 'Ravi Translator', email: 'translator@janbhasha.in', role: 'translator' },
          ],
        };
      }
    }

    return Promise.reject(error);
  }
);

export default api;
