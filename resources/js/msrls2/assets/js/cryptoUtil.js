// cryptoUtil.js
import CryptoJS from 'crypto-js';

const passphrase = 'ea773c44abff21506d727a244b64cc7dd0f9b5e225d73eaa9e500cc68d9cd546';
const SECRET_KEY = CryptoJS.SHA256(passphrase).toString();

export function encrypt(value) {
    try {
        if (value === null || value === undefined) {
            throw new Error('Invalid value for encryption');
        }
        const text = String(value); // 🔒 Ensure string conversion
        return CryptoJS.AES.encrypt(text, SECRET_KEY).toString();
    } catch (e) {
        console.error('Encryption failed:', e.message, value);
        return '';
    }
}

export function decrypt(ciphertext) {
    try {
        const decoded = decodeURIComponent(ciphertext); // revert URL encoding
        const bytes = CryptoJS.AES.decrypt(decoded, SECRET_KEY);
        return bytes.toString(CryptoJS.enc.Utf8);
    } catch (e) {
        console.error('Decryption failed', e);
        return '';
    }
}