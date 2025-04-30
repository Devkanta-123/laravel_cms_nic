const DB_NAME = 'carousel-image-cache'
const STORE_NAME = 'images'
const DB_VERSION = 1
const CACHE_DURATION = 1000 * 60 * 60 // 1 hour

let dbPromise = null

function openDB() {
    if (dbPromise) return dbPromise
    dbPromise = new Promise((resolve, reject) => {
        const request = indexedDB.open(DB_NAME, DB_VERSION)
        request.onerror = () => reject(request.error)
        request.onsuccess = () => resolve(request.result)
        request.onupgradeneeded = () => {
            const db = request.result
            if (!db.objectStoreNames.contains(STORE_NAME)) {
                db.createObjectStore(STORE_NAME, { keyPath: 'url' })
            }
        }
    })
    return dbPromise
}

async function getCachedImage(url) {
    const db = await openDB()
    return new Promise((resolve) => {
        const tx = db.transaction(STORE_NAME, 'readonly')
        const store = tx.objectStore(STORE_NAME)
        const req = store.get(url)
        req.onsuccess = () => {
            const result = req.result
            if (result && Date.now() - result.timestamp < CACHE_DURATION) {
                resolve(result.dataURL)
            } else {
                resolve(null)
            }
        }
        req.onerror = () => resolve(null)
    })
}

async function storeImage(url, dataURL) {
    const db = await openDB()
    const tx = db.transaction(STORE_NAME, 'readwrite')
    const store = tx.objectStore(STORE_NAME)
    store.put({ url, dataURL, timestamp: Date.now() })
}

export async function loadOrCacheImage(url) {
    const cached = await getCachedImage(url)
    if (cached) return cached

    const response = await fetch(url)
    const blob = await response.blob()
    const base64 = await new Promise((resolve, reject) => {
        const reader = new FileReader()
        reader.onloadend = () => resolve(reader.result)
        reader.onerror = reject
        reader.readAsDataURL(blob)
    })

    await storeImage(url, base64)
    return base64
}