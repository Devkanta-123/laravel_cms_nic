// assets/js/indexedDBUtil.js

class IndexedDBUtil {
    constructor(dbName, version = 1) {
        this.dbName = dbName;
        this.version = version;
        this.db = null;
    }

    // Open database connection
    async openDB() {
        return new Promise((resolve, reject) => {
            const request = indexedDB.open(this.dbName, this.version);

            request.onerror = () => reject(request.error);
            request.onsuccess = () => {
                this.db = request.result;
                resolve(this.db);
            };

            request.onupgradeneeded = (event) => {
                const db = event.target.result;

                // Create object store for cards if it doesn't exist
                if (!db.objectStoreNames.contains('cards')) {
                    const store = db.createObjectStore('cards', { keyPath: 'id' });
                    store.createIndex('timestamp', 'timestamp', { unique: false });
                }

                // Create object store for timestamps
                if (!db.objectStoreNames.contains('timestamps')) {
                    const store = db.createObjectStore('timestamps', { keyPath: 'key' });
                }
            };
        });
    }

    // Get item from database
    async getItem(storeName, key) {
        await this.openDB();

        return new Promise((resolve, reject) => {
            const transaction = this.db.transaction([storeName], 'readonly');
            const store = transaction.objectStore(storeName);
            const request = store.get(key);

            request.onerror = () => reject(request.error);
            request.onsuccess = () => resolve(request.result);
        });
    }

    // Set item in database
    async setItem(storeName, key, data) {
        await this.openDB();

        return new Promise((resolve, reject) => {
            const transaction = this.db.transaction([storeName], 'readwrite');
            const store = transaction.objectStore(storeName);

            // Prepare data with timestamp for cards
            const item = storeName === 'cards' ? { id: key, data: data, timestamp: new Date().getTime() } : { key: key, value: data };

            const request = store.put(item);

            request.onerror = () => reject(request.error);
            request.onsuccess = () => resolve(request.result);
        });
    }

    // Check if cache is valid
    async isCacheValid(cacheKey, cacheDuration) {
        try {
            const timestampData = await this.getItem('timestamps', cacheKey);
            if (!timestampData) return false;

            const now = new Date().getTime();
            return (now - timestampData.value) < cacheDuration;
        } catch (error) {
            console.error('Error checking cache validity:', error);
            return false;
        }
    }

    // Clear old cache
    async clearOldCache(cacheDuration) {
        try {
            await this.openDB();
            const transaction = this.db.transaction(['cards', 'timestamps'], 'readwrite');
            const cardsStore = transaction.objectStore('cards');
            const timestampsStore = transaction.objectStore('timestamps');
            const now = new Date().getTime();

            // Clear old cards
            const cardsRequest = cardsStore.openCursor();
            cardsRequest.onsuccess = (event) => {
                const cursor = event.target.result;
                if (cursor) {
                    if (now - cursor.value.timestamp > cacheDuration) {
                        cursor.delete();
                    }
                    cursor.continue();
                }
            };

            // Clear old timestamps
            const timestampsRequest = timestampsStore.openCursor();
            timestampsRequest.onsuccess = (event) => {
                const cursor = event.target.result;
                if (cursor) {
                    if (now - cursor.value.value > cacheDuration) {
                        cursor.delete();
                    }
                    cursor.continue();
                }
            };
        } catch (error) {
            console.error('Error clearing old cache:', error);
        }
    }
}

// Create a singleton instance
export const indexedDB = new IndexedDBUtil('CardsCacheDB', 1);