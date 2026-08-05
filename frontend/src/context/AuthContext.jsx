import React, { createContext, useContext, useState, useEffect } from 'react';
import api from '../api';

const AuthContext = createContext();

export const AuthProvider = ({ children }) => {
  const [user, setUser] = useState(null);
  const [token, setToken] = useState(localStorage.getItem('janbhasha_token') || null);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    if (token) {
      fetchUser();
    } else {
      setLoading(false);
    }
  }, [token]);

  const fetchUser = async () => {
    try {
      const response = await api.get('/auth/me');
      if (response.data?.user) {
        setUser(response.data.user);
      }
    } catch (err) {
      console.error('Auth verification failed:', err);
      logout();
    } finally {
      setLoading(false);
    }
  };

  const login = async (email, password) => {
    const response = await api.post('/auth/login', { email, password });
    const { token: newToken, user: userData } = response.data;
    localStorage.setItem('janbhasha_token', newToken);
    setToken(newToken);
    setUser(userData);
    return response.data;
  };

  const register = async (name, email, password, password_confirmation) => {
    const response = await api.post('/auth/register', { name, email, password, password_confirmation });
    const { token: newToken, user: userData } = response.data;
    localStorage.setItem('janbhasha_token', newToken);
    setToken(newToken);
    setUser(userData);
    return response.data;
  };

  const logout = async () => {
    try {
      if (token) {
        await api.post('/auth/logout');
      }
    } catch (err) {
      console.warn('Logout API error:', err);
    } finally {
      localStorage.removeItem('janbhasha_token');
      setToken(null);
      setUser(null);
    }
  };

  return (
    <AuthContext.Provider value={{ user, token, loading, login, register, logout, fetchUser }}>
      {children}
    </AuthContext.Provider>
  );
};

export const useAuth = () => useContext(AuthContext);
