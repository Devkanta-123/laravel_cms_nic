<template>
    <!-- <Loader v-if="isLoading" /> -->
    <br>
       <div class="content ml-4 mr-4">
            <div class="container-fluid ">
                <div class="row page-titles mx-0 mb-3">
                    <div class="col-sm-6 p-0">
                        <div class="welcome-text">
                            <h4 class="text-primary">Themes</h4>
                            <span>All Themes</span>
                        </div>
                    </div>
                    <div class="col-sm-6 p-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                        <ol class="">
                            <li class="breadcrumb-item"><a href="">Themes</a></li>
                            <li class="breadcrumb-item active "><a class="text-primary">All Themes</a></li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

    
    <div class="row ml-4 mr-4">
        <div class="col-md-5 m-4 card shadow-effect" v-for="theme in themes" :key="theme.id"   :class="{ 'selected': theme.id === selectedThemeId }" @click="selectTheme(theme.id)">
            <div class="image-container">
                <img :src="theme.image" alt="Theme Image" class="img-fluid hover-pop"/>
            </div>
            
                <br>
            <div class="form-check">
                <input class="form-check-input" type="radio" :value="theme.id" v-model="selectedThemeId">
                <label class="form-check-label">
                    {{ theme.theme_name }}
                </label>
            </div>
        </div>
    </div>
    <div class="text-center mt-4">
        <button class="btn btn-primary" @click="saveTheme">Save Theme</button>
    </div>
</template>

<script setup>
import { ref, onMounted  } from "vue";
import axios from 'axios';
import Loader from '../../components/Loader.vue';

const themes =  ref([]);
const selectedThemeId = ref(null);
const isLoading = ref(true);

const getThemes = () => {
    axios.post('/api/get_themes')  
        .then((response) => {
            themes.value = response.data.themes;
            selectedThemeId.value = response.data.currentThemeId;
            console.log(response);
        })  
        .catch((error) => {
          console.error('Error:', error);
        });
}

const selectTheme = (id) => {
    selectedThemeId.value = id;
}

const saveTheme = () => {
    if (selectedThemeId.value !== null) {
        axios.post('/api/save_theme', { theme_id: selectedThemeId.value })
            .then((response) => {
                if (response.status === 200) {
                    console.log('Theme saved:', response);
                    alert('Theme saved successfully!');
                } else {
                    alert(response.data.message);
                }
            })
            .catch((error) => {
                console.error('Error saving theme:', error);
            });
    } else {
        alert('Please select a theme before saving.');
    }
}

onMounted(() => {
    setTimeout(() => {
            isLoading.value = false; 
    }, 500); 
    getThemes();

});
</script>


<style scoped>
.image-container {
    overflow: hidden;
}

.hover-pop {
    transition: transform 0.3s ease-in-out;
}

.hover-pop:hover {
    transform: scale(1.1);
}

.shadow-effect {
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1), 0 6px 20px rgba(0, 0, 0, 0.1);
    transition: box-shadow 0.3s ease-in-out;
}

.shadow-effect:hover {
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2), 0 12px 40px rgba(0, 0, 0, 0.2);
}
.shadow-effect.selected, .shadow-effect.selected:hover {
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2), 0 12px 40px rgba(0, 0, 0, 0.2);
    transition: box-shadow 0.3s ease-in-out;
}

.card {
    cursor: pointer; /* Add cursor pointer to indicate it's clickable */
}
</style>