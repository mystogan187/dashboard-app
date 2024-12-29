import React, { useState, useRef, useEffect } from 'react';
import { Send, User, Bot } from 'lucide-react';

const ChatBot = () => {
    const [messages, setMessages] = useState([]);
    const [inputMessage, setInputMessage] = useState('');
    const [streamingMessage, setStreamingMessage] = useState('');
    const messagesEndRef = useRef(null);
    const inputRef = useRef(null);

    const scrollToBottom = () => {
        messagesEndRef.current?.scrollIntoView({ behavior: "smooth" });
    };

    useEffect(() => {
        scrollToBottom();
    }, [messages, streamingMessage]);

    const handleSubmit = async (e) => {
        e.preventDefault();
        if (inputMessage.trim() === '') return;

        const userMessage = {
            content: inputMessage,
            isUser: true,
            timestamp: new Date()
        };

        setMessages(prev => [...prev, userMessage]);
        setInputMessage('');
        setStreamingMessage('');

        try {
            const token = localStorage.getItem('token');
            const response = await fetch('/api/chat/send', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${token}`
                },
                body: JSON.stringify({ message: inputMessage })
            });

            if (!response.ok) {
                throw new Error(await response.text());
            }

            const reader = response.body.getReader();
            const decoder = new TextDecoder();
            let accumulatedResponse = '';

            try {
                while (true) {
                    const {value, done} = await reader.read();
                    if (done) break;

                    const chunk = decoder.decode(value);
                    const lines = chunk.split('\n');

                    for (const line of lines) {
                        if (line.startsWith('data: ')) {
                            try {
                                const data = JSON.parse(line.slice(6));
                                accumulatedResponse += data.content;
                                setStreamingMessage(accumulatedResponse);
                            } catch (e) {
                                console.log('Error parsing chunk:', e);
                            }
                        }
                    }
                }
            } finally {
                reader.releaseLock();
            }

            // Solo cuando termine el streaming, añadimos el mensaje completo
            setMessages(prev => [...prev, {
                content: accumulatedResponse || "No se recibió una respuesta del servidor.",
                isUser: false,
                timestamp: new Date()
            }]);
            setStreamingMessage('');

        } catch (error) {
            console.error('Error:', error);
            setMessages(prev => [...prev, {
                content: error.message || "Error al procesar la respuesta. Por favor, intenta nuevamente.",
                isUser: false,
                timestamp: new Date()
            }]);
        }
    };

    const formatMessage = (content) => {
        const parts = [];
        let currentText = '';
        let inCodeBlock = false;
        const lines = content.split('\n');

        lines.forEach((line, index) => {
            if (line.includes('```')) {
                if (currentText) {
                    parts.push({ type: 'text', content: currentText });
                    currentText = '';
                }
                inCodeBlock = !inCodeBlock;

                if (!inCodeBlock && parts.length > 0 && parts[parts.length - 1].type === 'code') {
                    const lang = line.replace('```', '');
                    parts[parts.length - 1].language = lang;
                }
            } else if (inCodeBlock) {
                if (parts.length > 0 && parts[parts.length - 1].type === 'code') {
                    parts[parts.length - 1].content += line + '\n';
                } else {
                    parts.push({ type: 'code', content: line + '\n' });
                }
            } else {
                currentText += line + '\n';
            }
        });

        if (currentText) {
            parts.push({ type: 'text', content: currentText });
        }

        return parts;
    };

    const MessageContent = ({ content }) => {
        const parts = formatMessage(content);

        return (
            <div className="space-y-2">
                {parts.map((part, index) => {
                    if (part.type === 'code') {
                        return (
                            <div key={index} className="relative">
                                <div className="bg-gray-900 text-gray-100 p-4 rounded-lg font-mono text-sm overflow-x-auto">
                                    {part.content}
                                </div>
                                <button
                                    onClick={() => navigator.clipboard.writeText(part.content)}
                                    className="absolute top-2 right-2 px-2 py-1 bg-gray-700 hover:bg-gray-600 text-white text-xs rounded-md"
                                >
                                    Copiar
                                </button>
                            </div>
                        );
                    }
                    return (
                        <div key={index} className="whitespace-pre-wrap">
                            {part.content}
                        </div>
                    );
                })}
            </div>
        );
    };

    return (
        <div className="flex flex-col h-full">
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
                            <MessageContent content={message.content} />
                        </div>
                    </div>
                ))}
                {streamingMessage && (
                    <div className="flex items-start gap-2 mb-4">
                        <div className="flex items-center justify-center w-8 h-8 rounded-full bg-gray-200">
                            <Bot className="w-5 h-5 text-gray-600" />
                        </div>
                        <div className="px-4 py-2 rounded-lg max-w-[70%] bg-gray-100 text-gray-800">
                            <MessageContent content={streamingMessage} />
                        </div>
                    </div>
                )}
                <div ref={messagesEndRef} />
            </div>

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