import React, { useState, useEffect } from 'react';
import UserModal from '../components/UserModal';

const Users = () => {
    const [users, setUsers] = useState([]);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState(null);
    const [modalOpen, setModalOpen] = useState(false);
    const [selectedUser, setSelectedUser] = useState(null);
    const [searchTerm, setSearchTerm] = useState('');

    useEffect(() => {
        fetchUsers();
    }, []);

    const fetchUsers = async () => {
        setLoading(true);
        try {
            const response = await fetch('/api/users', {
                headers: {
                    'Authorization': `Bearer ${localStorage.getItem('token')}`
                }
            });
            if (!response.ok) throw new Error('Error al cargar usuarios');
            const data = await response.json();
            setUsers(data);
        } catch (err) {
            setError(err.message);
        } finally {
            setLoading(false);
        }
    };

    const handleSave = async (userData) => {
        try {
            const method = selectedUser ? 'PUT' : 'POST';
            const url = selectedUser ? `/api/users/${selectedUser.id}` : '/api/users';

            const response = await fetch(url, {
                method,
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${localStorage.getItem('token')}`
                },
                body: JSON.stringify(userData)
            });

            if (!response.ok) throw new Error('Error al guardar usuario');

            await fetchUsers();
            setModalOpen(false);
            setSelectedUser(null);
        } catch (err) {
            setError(err.message);
        }
    };

    const handleDelete = async (userId) => {
        if (!confirm('¿Estás seguro de que deseas eliminar este usuario?')) return;

        try {
            const response = await fetch(`/api/users/${userId}`, {
                method: 'DELETE',
                headers: {
                    'Authorization': `Bearer ${localStorage.getItem('token')}`
                }
            });

            if (!response.ok) throw new Error('Error al eliminar usuario');

            await fetchUsers();
        } catch (err) {
            setError(err.message);
        }
    };

    const filteredUsers = users.filter(user =>
        user.name.toLowerCase().includes(searchTerm.toLowerCase()) ||
        user.email.toLowerCase().includes(searchTerm.toLowerCase())
    );

    if (loading) return <div>Cargando...</div>;
    if (error) return <div className="text-red-500">Error: {error}</div>;

    return (
        <div>
            <h2 className="text-2xl font-bold mb-6">Gestión de Usuarios</h2>
            <div className="bg-white rounded-lg shadow-sm p-6">
                <div className="flex justify-between items-center mb-6">
                    <div className="relative">
                        <input
                            type="text"
                            value={searchTerm}
                            onChange={(e) => setSearchTerm(e.target.value)}
                            placeholder="Buscar usuarios..."
                            className="pl-4 pr-4 py-2 bg-gray-100 rounded-lg w-64 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        />
                    </div>
                    <button
                        onClick={() => {
                            setSelectedUser(null);
                            setModalOpen(true);
                        }}
                        className="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600"
                    >
                        Añadir Usuario
                    </button>
                </div>

                <table className="w-full">
                    <thead>
                    <tr className="border-b">
                        <th className="text-left py-4">Nombre</th>
                        <th className="text-left py-4">Email</th>
                        <th className="text-left py-4">Rol</th>
                        <th className="text-right py-4">Acciones</th>
                    </tr>
                    </thead>
                    <tbody>
                    {filteredUsers.map((user) => (
                        <tr key={user.id} className="border-b">
                            <td className="py-4">{user.name}</td>
                            <td className="py-4">{user.email}</td>
                            <td className="py-4">
                                    <span className="bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-sm">
                                        {user.roles.includes('ROLE_ADMIN') ? 'Admin' : 'Usuario'}
                                    </span>
                            </td>
                            <td className="py-4 text-right">
                                <button
                                    onClick={() => {
                                        setSelectedUser(user);
                                        setModalOpen(true);
                                    }}
                                    className="text-blue-500 hover:text-blue-700 mr-3"
                                >
                                    Editar
                                </button>
                                <button
                                    onClick={() => handleDelete(user.id)}
                                    className="text-red-500 hover:text-red-700"
                                >
                                    Eliminar
                                </button>
                            </td>
                        </tr>
                    ))}
                    </tbody>
                </table>
            </div>

            <UserModal
                user={selectedUser}
                isOpen={modalOpen}
                onClose={() => {
                    setModalOpen(false);
                    setSelectedUser(null);
                }}
                onSave={handleSave}
            />
        </div>
    );
};

export default Users;