import React from 'react';
import ChatBot from '../components/ChatBot';

const AiGent = () => {
    return (
        <div className="h-full flex flex-col pb-6">
            <div className="flex-none">
                <h1 className="text-2xl font-bold text-gray-800 mb-4">Ai-Gent Chat</h1>
            </div>
            <div className="flex-1 min-h-0">
                <div className="h-full bg-white rounded-lg shadow-lg">
                    <ChatBot />
                </div>
            </div>
        </div>
    );
};

export default AiGent;