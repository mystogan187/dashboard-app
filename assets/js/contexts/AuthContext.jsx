import React, { createContext, useState, useContext, useEffect, useCallback } from 'react';

const AuthContext = createContext(null);

export const AuthProvider = ({ children }) => {
    const [authLoading, setAuthLoading] = useState(true);
    const [loginLoading, setLoginLoading] = useState(false);

    const [user, setUser] = useState(null);
    const [error, setError] = useState(null);

    // ✅ 1️⃣ Control de login
    const login = async (email, password) => {
        setLoginLoading(true);
        setError(null);
        try {
            const response = await fetch('/api/login_check', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ email, password }),
            });

            if (!response.ok) {
                throw new Error('Credenciales inválidas');
            }

            const data = await response.json();

            setUser(data.user);
            localStorage.setItem('token', data.token);

            return true;
        } catch (err) {
            setError(err.message);
            return false;
        } finally {
            setLoginLoading(false);
        }
    };

    // ✅ 2️⃣ Control de logout
    const logout = () => {
        setUser(null);
        localStorage.removeItem('token');
    };

    // ✅ 3️⃣ Control de checkAuth
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
                headers: { 'Authorization': `Bearer ${token}` }
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

    // ✅ 4️⃣ Control de useEffect
    useEffect(() => {
        if (!user) {
            checkAuth();
        }
    }, [user, checkAuth]);

    // ✅ 5️⃣ Control de isAuthenticated
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