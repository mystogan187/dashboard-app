import React from 'react';
import { useAuth } from '../contexts/AuthContext';

const Profile = () => {
    const { user } = useAuth();

    return (
        <div>
            <h2 className="text-2xl font-bold mb-6">Perfil de Usuario</h2>
            <div className="bg-white rounded-lg shadow-sm p-6">
                <div className="flex items-center mb-6">
                    <div className="w-24 h-24 rounded-full bg-blue-500 mr-6"></div>
                    <div>
                        <h3 className="text-xl font-semibold">{user?.name}</h3>
                        <p className="text-gray-600">{user?.email}</p>
                    </div>
                </div>

                <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label className="block text-sm font-medium text-gray-700 mb-2">
                            Nombre
                        </label>
                        <input
                            type="text"
                            className="w-full px-3 py-2 border rounded-lg"
                            value={user?.name}
                            readOnly
                        />
                    </div>
                    <div>
                        <label className="block text-sm font-medium text-gray-700 mb-2">
                            Email
                        </label>
                        <input
                            type="email"
                            className="w-full px-3 py-2 border rounded-lg"
                            value={user?.email}
                            readOnly
                        />
                    </div>
                </div>

                <div className="mt-6">
                    <button className="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">
                        Actualizar Perfil
                    </button>
                </div>
            </div>
        </div>
    );
};

export default Profile;