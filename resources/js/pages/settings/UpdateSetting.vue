<template>
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Settings</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Settings</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <section class="content">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">Archieve Settings</h3>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label>Archive Duration for Latest news (no of days)</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">
                                                        <i class="far fa-calendar-alt"></i>
                                                    </span>
                                                </div>
                                                <input type="number" class="form-control" placeholder="Number of days"
                                                    v-model="archieveDuration">
                                                <div class="input-group-append">
                                                    <button class="btn btn-primary"
                                                        @click="saveArchiveData()">Save</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">Language Settings</h3>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label>Language:</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" placeholder="Enter language"
                                                    v-model="languageName">
                                                <div class="input-group-append">
                                                    <button class="btn btn-primary"
                                                        @click="saveLanguageData()">Save</button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label>Languages:</label>
                                            <div class="d-flex flex-wrap">
                                                <div class="form-check form-check-inline custom-check"
                                                    v-for="language in languageData" :key="language.id">
                                                    <input class="form-check-input d-none" type="checkbox"
                                                        :id="'lang_' + language.id" :value="language.id"
                                                        :checked="isActivated(language.id)"
                                                        @change="(e) => activateLanguage(language.id, e.target.checked)" />

                                                    <label class="form-check-label styled-checkbox"
                                                        :for="'lang_' + language.id">
                                                        {{ language.language_name }}
                                                    </label>
                                                </div>
                                            </div>
                                        </div>


                                    </div>
                                </div>
                            </div>


                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Cache Area</h3>
                                </div>
                                <div class="card-body">
                                    <!-- Local Storage -->
                                    <div class="form-group">
                                        <strong>Local Storage</strong>
                                        <div class="d-flex flex-wrap">
                                            <div class="form-check form-check-inline custom-check"
                                                v-for="(value, key) in localStorageKeys" :key="'local-' + key">
                                                <input class="form-check-input d-none" type="checkbox"
                                                    :id="'local_' + key" :value="key"
                                                    v-model="selectedCache.localStorage" />
                                                <label class="form-check-label styled-checkbox" :for="'local_' + key">
                                                    {{ key }}
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Session Storage -->
                                    <!-- <div class="form-group">
                                        <strong>Session Storage</strong>
                                        <div class="d-flex flex-wrap">
                                            <div class="form-check form-check-inline custom-check"
                                                v-for="(value, key) in sessionStorageKeys" :key="'session-' + key">
                                                <input class="form-check-input d-none" type="checkbox"
                                                    :id="'session_' + key" :value="key"
                                                    v-model="selectedCache.sessionStorage" />
                                                <label class="form-check-label styled-checkbox" :for="'session_' + key">
                                                    {{ key }}
                                                </label>
                                            </div>
                                        </div>
                                    </div> -->

                                    <!-- IndexedDB -->
                                    <div class="form-group">
                                        <strong>IndexedDB</strong>
                                        <div class="d-flex flex-wrap">
                                            <div class="form-check form-check-inline custom-check"
                                                v-for="name in indexedDBNames" :key="'idb-' + name">
                                                <input class="form-check-input d-none" type="checkbox"
                                                    :id="'idb_' + name" :value="name"
                                                    v-model="selectedCache.indexedDB" />
                                                <label class="form-check-label styled-checkbox" :for="'idb_' + name">
                                                    {{ name }}
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Buttons -->
                                    <div class="mt-3">
                                        <button class="btn btn-danger me-2" @click="clearSelected">Clear
                                            Selected</button>
                                        <button class="btn btn-secondary" @click="clearAllStorage">Clear All</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

            </div>

        </div>
    </div>

</template>

<script setup>
import { ref, defineProps, onMounted } from 'vue';
import axios from 'axios';
import { useToastr } from '../../toaster.js';

const props = defineProps({
    menu: String,
    section: Number,
});
const toastr = useToastr();

const archieveDuration = ref(null)
const languageName = ref(null)
const languageData = ref(null)
const activatelanguageData = ref(null)
// Storage data
const localStorageKeys = ref({});
const sessionStorageKeys = ref({});
const indexedDBNames = ref([]);

// Selected checkboxes
const selectedCache = ref({
    localStorage: [],
    sessionStorage: [],
    indexedDB: []
});

const saveArchiveData = async () => {
    try {
        if (
            archieveDuration.value === null ||
            archieveDuration.value < 0 ||
            archieveDuration.value > 365
        ) {
            toastr.error('Please enter a valid number of days (0-365)');
            return;
        }

        const response = await axios.post('/api/saveArchiveData', {
            archieve_duration: archieveDuration.value,
        })
        getArchiveData();
        toastr.success(response.data.message || 'Settings saved successfully!')
    } catch (error) {
        if (error.response && error.response.data && error.response.data.message) {
            toastr.error(error.response.data.message)
        } else {
            toastr.error('An error occurred while saving the settings.')
        }
    }
};

const saveLanguageData = async () => {
    try {

        if (languageName.value === null) {
            toastr.error('Please enter a language')
            return
        }
        const response = await axios.post('/api/saveLanguage', {
            language_name: languageName.value,
        })
        languageName.value = '';
        getMasterLanguages();
        toastr.success(response.data.message || 'Language saved successfully!')
    } catch (error) {
        if (error.response && error.response.data && error.response.data.message) {
            toastr.error(error.response.data.message)
        } else {
            toastr.error('An error occurred while saving the Language.')
        }
    }
};

// Load archive setting when component mounts
const getArchiveData = async () => {
    try {
        const response = await axios.get('/api/getArchieveData');
        if (Array.isArray(response.data)) {
            const recordWithValue = response.data.find(item => item.archieve_duration !== null && item.archieve_duration !== undefined);
            if (recordWithValue) {
                archieveDuration.value = recordWithValue.archieve_duration;
            }
        }
    } catch (error) {
        toastr.error('Failed to fetch archive settings.');
    }
}


const getMasterLanguages = async () => {
    try {

        const response = await axios.get('/api/getMasterLanguages')
        if (response.data) {
            languageData.value = response.data;
        }
    }
    catch (error) {
        toastr.error('Failed to fetch languages')
    }
}

const isActivated = (languageId) => {
    return activatelanguageData.value.some(item => item.language_id === languageId);
};
const activateLanguage = async (languageId, isChecked) => {
    try {

        if (isChecked) {
            await axios.post('/api/saveArchiveData', { language_id: languageId });
            activatelanguageData.value.push({ language_id: languageId });
            toastr.success('Language activated!');
        } else {
            await axios.post('/api/deleteArchiveData', { language_id: languageId });
            activatelanguageData.value = activatelanguageData.value.filter(
                item => item.language_id !== languageId
            );
            toastr.success('Language deactivated!');
        }
    } catch (error) {
        toastr.error('Failed to update language');
    }
};
const getActivateLanguages = async () => {
    try {
        const response = await axios.get('/getActivateLanguages')
        if (response.data) {
            activatelanguageData.value = response.data;
        }
    }
    catch (error) {
        toastr.error('Failed to fetch  activate languages')
    }
}

// Fetch all cache items
const fetchCacheData = async () => {
    // Get localStorage
    for (let i = 0; i < localStorage.length; i++) {
        const key = localStorage.key(i);
        localStorageKeys.value[key] = localStorage.getItem(key);
    }

    // Get sessionStorage
    for (let i = 0; i < sessionStorage.length; i++) {
        const key = sessionStorage.key(i);
        sessionStorageKeys.value[key] = sessionStorage.getItem(key);
    }

    // Get IndexedDB names (if supported)
    if (indexedDB.databases) {
        const dbs = await indexedDB.databases();
        indexedDBNames.value = dbs.map(db => db.name).filter(Boolean);
    } else {
        console.warn('IndexedDB list not supported in this browser');
    }
};

// Clear only selected
const clearSelected = () => {
    // Clear selected localStorage
    selectedCache.value.localStorage.forEach(key => localStorage.removeItem(key));

    // Clear selected sessionStorage
    selectedCache.value.sessionStorage.forEach(key => sessionStorage.removeItem(key));

    // Clear selected IndexedDB
    selectedCache.value.indexedDB.forEach(name => {
        indexedDB.deleteDatabase(name);
    });

    // Refresh lists
    resetAndFetch();
};

// Clear all
const clearAllStorage = async () => {
    localStorage.clear();
    sessionStorage.clear();

    if (indexedDB.databases) {
        const dbs = await indexedDB.databases();
        for (const db of dbs) {
            if (db.name) {
                indexedDB.deleteDatabase(db.name);
            }
        }
    }

    resetAndFetch();
};

const resetAndFetch = () => {
    localStorageKeys.value = {};
    sessionStorageKeys.value = {};
    indexedDBNames.value = [];
    selectedCache.value = {
        localStorage: [],
        sessionStorage: [],
        indexedDB: []
    };
    fetchCacheData();
};

onMounted(() => {
    getArchiveData()
    getMasterLanguages()
    getActivateLanguages()
    fetchCacheData()
})

</script>
<style scoped></style>
