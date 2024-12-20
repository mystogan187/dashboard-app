import React from 'react';
import { createRoot } from 'react-dom/client';
import { BrowserRouter as Router, Routes, Route, Navigate, useNavigate } from 'react-router-dom';
import { AuthProvider, useAuth } from './contexts/AuthContext';
import DashboardLayout from './layouts/DashboardLayout';
import Login from './pages/Login';
import Dashboard from './pages/Dashboard';
import Playground from './pages/Playground';
import AiGent from "./pages/AiGent";
import Users from './pages/Users';
import Profile from './pages/Profile';
import Settings from './pages/Settings';
import '../styles/app.css';
import {UserPreferencesProvider} from "./contexts/UserPreferencesContext";

const PrivateRoute = ({ children }) => {
    const { isAuthenticated, authLoading } = useAuth();

    if (authLoading) {
        return <div>Cargando...</div>;
    }

    if (!isAuthenticated) {
        return <Navigate to="/login" replace={true} />;
    }

    return children;
};

const App = () => {
    return (
        <AuthProvider>
            <UserPreferencesProvider>
                <Router>
                    <Routes>
                        <Route path="/login" element={<Login />} />
                        <Route path="/" element={
                            <PrivateRoute>
                                <DashboardLayout />
                            </PrivateRoute>
                        }>
                            <Route index element={<Dashboard />} />
                            <Route path="playground" element={<Playground />} />
                            <Route path="chatbot" element={<AiGent />} />
                            <Route path="users" element={<Users />} />
                            <Route path="profile" element={<Profile />} />
                            <Route path="settings" element={<Settings />} />
                        </Route>
                    </Routes>
                </Router>
            </UserPreferencesProvider>
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