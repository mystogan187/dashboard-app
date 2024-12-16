import React, { useState, useEffect } from 'react';
import { useAuth } from '../contexts/AuthContext';
import ChangePasswordModal from '../components/ChangePasswordModal';

const Profile = () => {
    const { user, updateProfile, setUser} = useAuth();
    const [isEditing, setIsEditing] = useState(false);
    const [isPasswordModalOpen, setIsPasswordModalOpen] = useState(false);
    const [formData, setFormData] = useState({
        name: user?.name || '',
        email: user?.email || '',
    });
    const [error, setError] = useState('');

    useEffect(() => {
        if (!isEditing) {
            setFormData({
                name: user?.name || '',
                email: user?.email || '',
            });
        }
    }, [user, isEditing]);

    const handleSubmit = async (e) => {
        e.preventDefault();
        const originalEmail = user?.email;
        try {
            const data = await updateProfile(formData);
            setError('');

            if (originalEmail !== formData.email) {
                setUser(data.user);
            }

            setIsEditing(false);
        } catch (err) {
            setError(err.message);
        }
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
                throw new Error(error.message || 'Error al cambiar la contraseña');
            }

            setIsPasswordModalOpen(false);
            setError('');
        } catch (err) {
            setError(err.message);
        }
    };

    return (
        <div>
            <h2 className="text-2xl font-bold mb-6">Perfil de Usuario</h2>
            <div className="bg-white rounded-lg shadow-sm p-6">
                {error && (
                    <div className="mb-4 p-3 bg-red-100 text-red-700 rounded-lg">
                        {error}
                    </div>
                )}

                <div className="flex items-center mb-6">
                    <div className="w-24 h-24 rounded-full bg-blue-500 mr-6"></div>
                    <div>
                        <h3 className="text-xl font-semibold">{user?.name}</h3>
                        <p className="text-gray-600">{user?.email}</p>
                    </div>
                </div>

                <form>
                    <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label className="block text-sm font-medium text-gray-700 mb-2">
                                Nombre
                            </label>
                            <input
                                type="text"
                                className="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500"
                                value={formData.name}
                                onChange={(e) => setFormData({...formData, name: e.target.value})}
                                disabled={!isEditing}
                            />
                        </div>
                        <div>
                            <label className="block text-sm font-medium text-gray-700 mb-2">
                                Email
                            </label>
                            <input
                                type="email"
                                className="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500"
                                value={formData.email}
                                onChange={(e) => setFormData({...formData, email: e.target.value})}
                                disabled={!isEditing}
                            />
                        </div>
                    </div>

                    <div className="mt-6 space-x-4">
                        {isEditing ? (
                            <>
                                <button
                                    type="button"
                                    onClick={handleSubmit}
                                    className="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 disabled:opacity-50"
                                >
                                    Guardar Cambios
                                </button>
                                <button
                                    type="button"
                                    onClick={() => {
                                        setIsEditing(false);
                                        setFormData({ name: user?.name || '', email: user?.email || '' });
                                    }}
                                    className="border px-4 py-2 rounded-lg hover:bg-gray-50"
                                >
                                    Cancelar
                                </button>
                            </>
                        ) : (
                            <>
                                <button
                                    type="button"
                                    onClick={() => setIsEditing(true)}
                                    className="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600"
                                >
                                    Editar Perfil
                                </button>
                                <button
                                    type="button"
                                    onClick={() => setIsPasswordModalOpen(true)}
                                    className="border px-4 py-2 rounded-lg hover:bg-gray-50"
                                >
                                    Cambiar Contraseña
                                </button>
                            </>
                        )}
                    </div>
                </form>
            </div>

            <ChangePasswordModal
                isOpen={isPasswordModalOpen}
                onClose={() => setIsPasswordModalOpen(false)}
                onSave={handlePasswordChange}
            />
        </div>
    );
};

export default Profile;