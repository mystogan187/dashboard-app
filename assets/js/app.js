import React, { useEffect } from 'react';
import { createRoot } from 'react-dom/client';
import { BrowserRouter as Router, Routes, Route, Navigate, useNavigate } from 'react-router-dom';
import { AuthProvider, useAuth } from './contexts/AuthContext';
import DashboardLayout from './layouts/DashboardLayout';
import Login from './pages/Login';
import Dashboard from './pages/Dashboard';
import '../styles/app.css';

const PrivateRoute = ({ children }) => {
    const { isAuthenticated, loading, checkAuth } = useAuth();
    const navigate = useNavigate();

    useEffect(() => {
        checkAuth();
    }, [checkAuth]);

    if (loading) {
        return <div>Cargando...</div>;
    }

    if (!isAuthenticated) {
        return <Navigate to="/login" />;
    }

    return children;
};

const App = () => {
    return (
        <AuthProvider>
            <Router>
                <Routes>
                    <Route path="/login" element={<Login />} />
                    <Route
                        path="/"
                        element={
                            <PrivateRoute>
                                <DashboardLayout />
                            </PrivateRoute>
                        }
                    >
                        <Route index element={<Dashboard />} />
                    </Route>
                </Routes>
            </Router>
        </AuthProvider>
    );
};

const container = document.getElementById('app');
const root = createRoot(container);
root.render(
    <React.StrictMode>
        <App />
    </React.StrictMode>
);