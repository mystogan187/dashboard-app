import React, { createContext, useState, useContext, useEffect, useCallback } from 'react';

const AuthContext = createContext(null);

export const AuthProvider = ({ children }) => {
    const [authLoading, setAuthLoading] = useState(true);
    const [loginLoading, setLoginLoading] = useState(false);

    const [user, setUser] = useState(null);
    const [error, setError] = useState(null);

    const login = async (email, password) => {
        setLoginLoading(true);
        setError(null);
        try {
            const response = await fetch('/api/login_check', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ email, password }),
            });

            const data = await response.json();

            if (!response.ok) {
                setError(data.message);
                return false;
            }
            console.log("AUTHENTICATION RESPONSE");
            console.log(data);
            setUser(data.user);
            localStorage.setItem('token', data.token);

            return true;
        } catch (err) {
            setError(err.message || 'Error al intentar iniciar sesión');
            return false;
        } finally {
            setLoginLoading(false);
        }
    };

    const logout = () => {
        setUser(null);
        localStorage.removeItem('token');
    };

    useEffect(() => {
        const originalFetch = window.fetch;

        window.fetch = async (...args) => {
            const [url, options = {}] = args;

            const token = localStorage.getItem('token');
            if (token) {
                options.headers = {
                    ...options.headers || {},
                    'Authorization': `Bearer ${token}`,
                    'Content-Type': 'application/json'
                };
            }

            console.log('Request:', url, options);

            try {
                const response = await originalFetch(url, options);

                if (response.status === 401) {
                    logout();
                    return Promise.reject('Sesión expirada');
                }

                return response;
            } catch (error) {
                if (error.message?.includes('Failed to fetch')) {
                    logout();
                }
                return Promise.reject(error);
            }
        };

        return () => {
            window.fetch = originalFetch;
        };
    }, [logout]);

    const checkAuth = useCallback(async () => {
        const token = localStorage.getItem('token');
        if (!token) {
            console.log('No hay token, cerrando sesión.');
            setAuthLoading(false);
            return;
        }

        setAuthLoading(true);
        try {
            const response = await fetch('/api/me', {
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'Content-Type': 'application/json'
                }
            });

            if (response.ok) {
                const data = await response.json();
                setUser(data.user);
            } else {
                throw new Error('Sesión inválida');
            }
        } catch (err) {
            logout();
        } finally {
            setAuthLoading(false);
        }
    }, []);

    const updateProfile = async (profileData) => {
        try {
            const response = await fetch('/api/profile/update', {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${localStorage.getItem('token')}`
                },
                body: JSON.stringify(profileData)
            });

            if (!response.ok) {
                const error = await response.json();
                throw new Error(error.message || 'Error al actualizar el perfil');
            }

            const data = await response.json();
            setUser(data.user);
            return data;
        } catch (err) {
            setError(err.message);
            throw err;
        }
    };

    useEffect(() => {
        if (!user) {
            checkAuth();
        }
    }, [user, checkAuth]);

    const isAuthenticated = !!user;

    return (
        <AuthContext.Provider value={{
            user,
            authLoading,
            loginLoading,
            error,
            login,
            logout,
            checkAuth,
            updateProfile,
            setUser,
            isAuthenticated
        }}>
            {children}
        </AuthContext.Provider>
    );
};

export const useAuth = () => {
    const context = useContext(AuthContext);
    if (!context) {
        throw new Error('useAuth debe ser usado dentro de un AuthProvider');
    }
    return context;
};