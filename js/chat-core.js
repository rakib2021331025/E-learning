import { db, storage, collection, addDoc, query, orderBy, onSnapshot, serverTimestamp, ref, uploadBytes, getDownloadURL, where } from './firebase-config.js';

const CHAT_COLLECTION = "messages";

/**
 * Send a text message
 */
export async function sendTextMessage(senderId, senderName, senderType, messageText) {
    try {
        await addDoc(collection(db, CHAT_COLLECTION), {
            senderId: senderId,
            senderName: senderName,
            senderType: senderType, // 'student' or 'admin'
            text: messageText,
            type: 'text',
            timestamp: serverTimestamp()
        });
        return true;
    } catch (e) {
        console.error("Error sending message: ", e);
        return false;
    }
}

/**
 * Upload file (audio/video) and send message
 */
export async function sendMediaMessage(senderId, senderName, senderType, fileBlob, fileType) {
    try {
        // 1. Upload file
        const filename = `${fileType}_${Date.now()}.webm`;
        const storageRef = ref(storage, `chat_media/${filename}`);
        const snapshot = await uploadBytes(storageRef, fileBlob);
        const downloadURL = await getDownloadURL(snapshot.ref);

        // 2. Send message with URL
        await addDoc(collection(db, CHAT_COLLECTION), {
            senderId: senderId,
            senderName: senderName,
            senderType: senderType,
            mediaUrl: downloadURL,
            type: fileType, // 'audio' or 'video'
            timestamp: serverTimestamp()
        });
        return true;
    } catch (e) {
        console.error(`Error sending ${fileType}: `, e);
        return false;
    }
}

/**
 * Listen for messages
 * callback(messagesArray)
 */
export function subscribeToMessages(callback) {
    const q = query(collection(db, CHAT_COLLECTION), orderBy("timestamp", "asc"));
    return onSnapshot(q, (querySnapshot) => {
        const messages = [];
        querySnapshot.forEach((doc) => {
            messages.push({ id: doc.id, ...doc.data() });
        });
        callback(messages);
    });
}
