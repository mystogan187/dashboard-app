import React, { createContext, useState, useEffect, useContext } from 'react';
import { useAuth } from './AuthContext';

const UserPreferencesContext = createContext();

export const UserPreferencesProvider = ({ children }) => {
    const { user, authLoading } = useAuth();
    const [notifications, setNotifications] = useState(false);
    const [darkMode, setDarkMode] = useState(false);
    const [showAlert, setShowAlert] = useState(false);
    const [alertMessage, setAlertMessage] = useState('');
    const [isPasswordModalOpen, setIsPasswordModalOpen] = useState(false);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState(null);

    const fetchPreferences = async () => {
        try {
            const token = localStorage.getItem('token');
            if (!token) {
                throw new Error('No se encontró el token de autenticación.');
            }

            const response = await fetch('/api/settings/preferences', {
                headers: {
                    'Authorization': `Bearer ${token}`
                }
            });

            if (!response.ok) {
                throw new Error('Error al obtener las preferencias.');
            }

            const data = await response.json();
            setNotifications(data.preferences.notifications);
            setDarkMode(data.preferences.darkMode);
        } catch (err) {
            setError(err.message);
            setShowAlert(true);
            setAlertMessage(err.message);
        } finally {
            setLoading(false);
        }
    };

    useEffect(() => {
        if (user) {
            fetchPreferences();
        } else {
            setNotifications(false);
            setDarkMode(false);
            setLoading(false);
        }
    }, [user]);

    useEffect(() => {
        if (darkMode) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    }, [darkMode]);

    const showNotification = (message, duration = 3000) => {
        setAlertMessage(message);
        setShowAlert(true);
        setTimeout(() => setShowAlert(false), duration);
    };

    const handlePreferenceUpdate = async (key, value) => {
        const token = localStorage.getItem('token');

        if (!token) {
            showNotification('No se encontró el token de autenticación.', 5000);
            return;
        }

        const newPreferences = {
            notifications,
            darkMode,
            [key]: value
        };

        try {
            const response = await fetch('/api/settings/preferences', {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${token}`
                },
                body: JSON.stringify(newPreferences)
            });

            if (!response.ok) {
                const errorData = await response.json();
                throw new Error(errorData.error || 'Error al actualizar las preferencias.');
            }

            showNotification(`Preferencia de ${key} actualizada correctamente.`);
        } catch (error) {
            showNotification('Error al actualizar la preferencia.', 5000);
            if (key === 'notifications') setNotifications(!value);
            if (key === 'darkMode') setDarkMode(!value);
        }
    };

    const handleNotificationsChange = async (checked) => {
        setNotifications(checked);
        await handlePreferenceUpdate('notifications', checked);
    };

    const handleDarkModeChange = async (checked) => {
        setDarkMode(checked);
        await handlePreferenceUpdate('darkMode', checked);
    };

    const handlePasswordChange = async (passwords) => {
        try {
            const response = await fetch('/api/profile/change-password', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${localStorage.getItem('token')}`
                },
                body: JSON.stringify(passwords)
            });

            if (!response.ok) {
                const error = await response.json();
                throw new Error(error.message || 'Error al cambiar la contraseña.');
            }

            showNotification('Contraseña actualizada correctamente.');
            setIsPasswordModalOpen(false);
        } catch (error) {
            showNotification(error.message, 5000);
        }
    };

    const handle2FASetup = async () => {
        try {
            const response = await fetch('/api/settings/setup-2fa', {
                headers: {
                    'Authorization': `Bearer ${localStorage.getItem('token')}`
                }
            });

            if (!response.ok) {
                throw new Error('Error al configurar 2FA.');
            }

            showNotification('2FA configurado correctamente.');
        } catch (error) {
            showNotification('Error al configurar 2FA.', 5000);
        }
    };

    return (
        <UserPreferencesContext.Provider
            value={{
                notifications,
                darkMode,
                showAlert,
                alertMessage,
                isPasswordModalOpen,
                setIsPasswordModalOpen,
                loading,
                error,
                handleNotificationsChange,
                handleDarkModeChange,
                handlePasswordChange,
                handle2FASetup
            }}
        >
            {children}
        </UserPreferencesContext.Provider>
    );
};

export const useUserPreferencesContext = () => {
    return useContext(UserPreferencesContext);
};