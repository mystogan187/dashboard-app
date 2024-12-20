import React, { useState, useRef, useEffect } from 'react';
import { Send, User, Bot } from 'lucide-react';

const ChatBot = () => {
    const [messages, setMessages] = useState([]);
    const [inputMessage, setInputMessage] = useState('');
    const messagesEndRef = useRef(null);
    const inputRef = useRef(null);

    const scrollToBottom = () => {
        messagesEndRef.current?.scrollIntoView({ behavior: "smooth" });
    };

    useEffect(() => {
        scrollToBottom();
    }, [messages]);

    const handleSubmit = (e) => {
        e.preventDefault();
        if (inputMessage.trim() === '') return;

        // Añadir mensaje del usuario
        const userMessage = {
            content: inputMessage,
            isUser: true,
            timestamp: new Date()
        };

        setMessages(prev => [...prev, userMessage]);
        setInputMessage('');

        // Simular respuesta del bot
        setTimeout(() => {
            const botMessage = {
                content: "Esta es una respuesta de ejemplo. Aquí conectarías con tu API de chatbot.",
                isUser: false,
                timestamp: new Date()
            };
            setMessages(prev => [...prev, botMessage]);
        }, 1000);
    };

    return (
        <div className="flex flex-col h-full">
            {/* Área de mensajes */}
            <div className="flex-1 min-h-0 p-4 overflow-y-auto">
                {messages.map((message, index) => (
                    <div
                        key={index}
                        className={`flex items-start gap-2 mb-4 ${
                            message.isUser ? 'flex-row-reverse' : 'flex-row'
                        }`}
                    >
                        <div className={`flex items-center justify-center w-8 h-8 rounded-full 
                            ${message.isUser ? 'bg-blue-500' : 'bg-gray-200'}`}>
                            {message.isUser ?
                                <User className="w-5 h-5 text-white" /> :
                                <Bot className="w-5 h-5 text-gray-600" />
                            }
                        </div>
                        <div
                            className={`px-4 py-2 rounded-lg max-w-[70%] ${
                                message.isUser
                                    ? 'bg-blue-500 text-white'
                                    : 'bg-gray-100 text-gray-800'
                            }`}
                        >
                            {message.content}
                        </div>
                    </div>
                ))}
                <div ref={messagesEndRef} />
            </div>

            {/* Área de input */}
            <form onSubmit={handleSubmit} className="flex-none p-4 border-t border-gray-200">
                <div className="flex gap-2">
                    <input
                        ref={inputRef}
                        type="text"
                        value={inputMessage}
                        onChange={(e) => setInputMessage(e.target.value)}
                        placeholder="Escribe un mensaje..."
                        className="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500"
                    />
                    <button
                        type="submit"
                        className="px-4 py-2 text-white bg-blue-500 rounded-lg hover:bg-blue-600 focus:outline-none"
                    >
                        <Send className="w-5 h-5" />
                    </button>
                </div>
            </form>
        </div>
    );
};

export default ChatBot;