const express = require('express');
const http = require('http');
const { Server } = require("socket.io");
const cors = require('cors');

const app = express();
app.use(cors());

const server = http.createServer(app);
const io = new Server(server, {
    cors: {
        origin: "*", // Allow all origins for development
        methods: ["GET", "POST"],
        credentials: true
    }
});

io.on('connection', (socket) => {
    console.log('User connected:', socket.id);

    socket.on('send_message', (data) => {
        // data: { senderId, senderName, senderType, text, type }
        // Broadcast to all clients (simple global chat)
        // In real app, verify with PHP/DB or use rooms

        const timestamp = new Date(); // Or just pass through

        io.emit('chat_message', {
            ...data,
            message: data.text,
            message_type: data.type,
            created_at: timestamp.toISOString(),
            sender_id: data.senderId,
            sender_name: data.senderName,
            sender_type: data.senderType
            // format matches PHP JSON output roughly
        });
    });

    socket.on('media_uploaded', (data) => {
        // data: { senderId, senderName, senderType, mediaUrl, type }
        io.emit('chat_message', {
            ...data,
            message: '',
            message_type: data.type,
            media_url: data.mediaUrl,
            created_at: new Date().toISOString(),
            sender_id: data.senderId,
            sender_name: data.senderName,
            sender_type: data.senderType
        });
    });

    socket.on('disconnect', () => {
        console.log('User disconnected:', socket.id);
    });
});

const PORT = 3000;
server.listen(PORT, () => {
    console.log(`Socket server running on port ${PORT}`);
});
