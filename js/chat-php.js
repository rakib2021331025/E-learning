/**
 * Chat Logic using PHP + MySQL + AJAX (SAFE VERSION)
 */

let PHP_HANDLER = "../student/chat_action.php";

export function setHandlerPath(path) {
    PHP_HANDLER = path;
}

const POLLING_INTERVAL = 3000;

let socket = null;
let useSocket = false;
let pollingIntervalId = null;

/* ---------------- SOCKET SETUP (OPTIONAL) ---------------- */
export function setupSocket(serverUrl, onMessageReceived) {
    try {
        if (typeof io !== 'undefined') {
            socket = io(serverUrl);

            socket.on('connect', () => {
                console.log("✅ Socket connected");
                useSocket = true;
                if (pollingIntervalId) clearInterval(pollingIntervalId);
            });

            socket.on('chat_message', msg => {
                // If using socket, we need to filter if it's meant for us
                // But typically socket rooms are better. 
                // For global broadcast socket (current simple setup), we must filter in callback or here.
                // Let's pass to callback and let UI filter.
                onMessageReceived([msg]);
            });

            socket.on('disconnect', () => {
                console.log("❌ Socket disconnected → fallback to polling");
                useSocket = false;
            });
        }
    } catch (e) {
        console.warn("Socket unavailable, using AJAX polling.");
    }
}

/* ---------------- SEND TEXT MESSAGE ---------------- */
export async function sendTextMessage(senderId, senderName, senderType, receiverId, messageText) {

    if (useSocket && socket) {
        socket.emit('send_message', {
            senderId, senderName, senderType, receiverId,
            text: messageText,
            type: 'text'
        });
    }

    const formData = new FormData();
    formData.append('action', 'sendMessage');
    formData.append('senderId', senderId);
    formData.append('senderName', senderName);
    formData.append('senderType', senderType);
    formData.append('receiverId', receiverId);
    formData.append('text', messageText);

    try {
        const res = await fetch(PHP_HANDLER, { method: 'POST', body: formData });
        const text = await res.text();
        const json = JSON.parse(text);
        return json.status === 'success';

    } catch (e) {
        console.error("❌ Text send failed:", e);
        return false;
    }
}

/* ---------------- SEND MEDIA / IMAGE MESSAGE ---------------- */
export async function sendMediaMessage(senderId, senderName, senderType, receiverId, fileBlob, fileType) {

    const formData = new FormData();
    formData.append('action', 'uploadMedia');
    formData.append('senderId', senderId);
    formData.append('senderName', senderName);
    formData.append('senderType', senderType);
    formData.append('receiverId', receiverId);
    formData.append('fileType', fileType);

    const ext = fileType === 'image' ? 'jpg' : (fileType === 'video' ? 'webm' : 'webm');
    formData.append('file', fileBlob, `${fileType}_${Date.now()}.${ext}`);

    try {
        const res = await fetch(PHP_HANDLER, { method: 'POST', body: formData });
        const text = await res.text();
        const json = JSON.parse(text);

        if (useSocket && socket && json.status === 'success') {
            socket.emit('media_uploaded', {
                senderId, senderName, senderType, receiverId,
                mediaUrl: json.url,
                type: fileType
            });
        }

        return json.status === 'success';

    } catch (e) {
        console.error("❌ Media upload failed:", e);
        return false;
    }
}

/* ---------------- POLLING ---------------- */
export function subscribeToMessages(senderId, otherId, callback) {
    let lastCount = 0;

    // Clear previous if any
    if (pollingIntervalId) clearInterval(pollingIntervalId);

    const fetchMessages = async () => {
        try {
            const formData = new FormData();
            formData.append('action', 'getMessages');
            formData.append('senderId', senderId);
            formData.append('otherId', otherId);

            const res = await fetch(PHP_HANDLER, { method: 'POST', body: formData });
            const text = await res.text();

            let messages;
            try {
                messages = JSON.parse(text);
            } catch {
                return;
            }

            // Error check: If API returns error object instead of array
            if (!Array.isArray(messages)) {
                console.warn("Polling returned non-array:", messages);
                if (messages.status === 'error') {
                    // Start over or retry?
                    // Maybe empty callback to clear loading state if we want?
                    // But usually we should treat as empty or log.
                }
                return;
            }

            if (messages.length > lastCount || messages.length === 0) {
                callback(messages);
                lastCount = messages.length;
            }
        } catch (e) {
            console.error("Polling failed:", e);
        }
    };

    fetchMessages();
    pollingIntervalId = setInterval(fetchMessages, POLLING_INTERVAL);

    return pollingIntervalId;
}

/* ---------------- GET USERS (Admin) ---------------- */
export async function getChatUsers() {
    try {
        const formData = new FormData();
        formData.append('action', 'getChatUsers');

        const res = await fetch(PHP_HANDLER, { method: 'POST', body: formData });
        const text = await res.text();
        return JSON.parse(text);
    } catch (e) {
        console.error("Failed to fetch users");
        return [];
    }
}
