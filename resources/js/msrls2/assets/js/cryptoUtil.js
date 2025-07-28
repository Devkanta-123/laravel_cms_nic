// cryptoUtil.js
import CryptoJS from 'crypto-js';

const passphrase = 'ea773c44abff21506d727a244b64cc7dd0f9b5e225d73eaa9e500cc68d9cd546';
const SECRET_KEY = CryptoJS.SHA256(passphrase).toString();

/**
 * Encrypts a value and returns a URL-safe string.
 */
export function encrypt(value) {
    try {
        if (value === null || value === undefined) {
            throw new Error('Invalid value for encryption');
        }
        const text = String(value);
        const encrypted = CryptoJS.AES.encrypt(text, SECRET_KEY).toString();
        // 🔐 Make the result URL-safe
        return encodeURIComponent(encrypted);
    } catch (e) {
        console.error('Encryption failed:', e.message, value);
        return '';
    }
}

/**
 * Decrypts a URL-safe string back to the original value.
 */
export function decrypt(ciphertext) {
    try {
        const decoded = decodeURIComponent(ciphertext);
        const bytes = CryptoJS.AES.decrypt(decoded, SECRET_KEY);
        const decrypted = bytes.toString(CryptoJS.enc.Utf8);
        if (!decrypted) throw new Error('Invalid decryption result');
        return decrypted;
    } catch (e) {
        console.error('Decryption failed:', e.message, ciphertext);
        return '';
    }
}