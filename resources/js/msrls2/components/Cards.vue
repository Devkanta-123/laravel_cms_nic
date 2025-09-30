<template>
  <div class="container">
    <!-- Navigation Buttons -->
    <div class="navigation-buttons" v-if="cards.length > CARDS_PER_SLIDE">
      <div class="d-flex justify-content-between align-items-center w-100 mb-3">
    <div class="testimonial__nav-four">
      <!-- Previous Button -->
      <div 
        class="testimonial-two-button-prev" 
        tabindex="0" 
        role="button" 
        @click="prevSlide"
        aria-label="Previous slide" 
        :aria-disabled="currentSlide === 0 ? 'true' : 'false'"
        :class="{ 'disabled': currentSlide === 0 }"
      >
        <i class="fa fa-chevron-right"></i>
      </div>

      <!-- Next Button -->
      <div 
        class="testimonial-two-button-next" 
        tabindex="0" 
        role="button" 
        @click="nextSlide"
        aria-label="Next slide" 
        :aria-disabled="currentSlide >= totalSlides - 1 ? 'true' : 'false'"
        :class="{ 'disabled': currentSlide >= totalSlides - 1 }"
      >
        <i class="fa fa-chevron-right"></i>
      </div>
    </div>
  </div>
    </div>

    <!-- Cards Grid (Your Original Design) -->
    <div class="row gutter-24">
      <div
        v-for="(card, index) in visibleCards"
        :key="card.id"
        class="col-lg-4 col-md-6"
      >
        <div class="project__item-two">
          <div class="project__thumb-two">
            <img :src="card.image" alt="Card Image" />
          </div>
          <div class="project__content-two">
            <h2 class="title">
              <router-link
                :to="{
                  name: 'Page',
                  params: { id: encrypt(card.more_link) },
                  query: { page_name: encrypt(card.card_title) },
                }"
              >
                {{ getCardsTitle(card) }}
              </router-link>
            </h2>
            <div class="link-arrow link-arrow-two">
              <router-link
                :to="{
                  name: 'Page',
                  params: { id: encrypt(card.more_link) },
                  query: { page_name: encrypt(card.card_title) },
                }"
              >
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  viewBox="0 0 18 15"
                  fill="none"
                >
                  <path
                    fill-rule="evenodd"
                    clip-rule="evenodd"
                    d="M17.6293 3.27956C17.7117 2.80339 17.4427 2.34761 17.0096 2.17811C16.9477 2.15384 16.8824 2.13551 16.8144 2.12375L6.96087 0.419136C6.4166 0.325033 5.89918 0.689841 5.80497 1.23409C5.71085 1.77828 6.0757 2.29576 6.61988 2.38991L14.0947 3.68293L1.3658 12.6573C0.914426 12.9756 0.806485 13.5994 1.12473 14.0508C1.44298 14.5022 2.06688 14.6101 2.51825 14.2919L15.2471 5.31752L13.954 12.7923C13.8599 13.3365 14.2248 13.854 14.7689 13.9481C15.3132 14.0422 15.8306 13.6774 15.9248 13.1332L17.6293 3.27956Z"
                    fill="currentColor"
                  />
                </svg>
              </router-link>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, inject, provide, computed } from 'vue';
import axios from 'axios';
import { encrypt } from '../assets/js/cryptoUtil.js';

// ================== CONFIG ==================
const CACHE_KEY_TIMESTAMP = "cardsTimestamp";
const CACHE_EXPIRY_MS = 10 * 60 * 1000; // 10 minutes
const DB_NAME = "cardsDB";
const STORE_NAME = "cards";
const DB_VERSION = 1;
const CARDS_PER_SLIDE = 3;

// ================== STATE ==================
const cards = ref([]);
const loadingCards = ref(false);
const currentSlide = ref(0);
const language = inject("language");
provide("language", language);

const props = defineProps({
  language: {
    type: String,
    required: true,
  },
});

// ================== COMPUTED PROPERTIES ==================
const totalSlides = computed(() => {
  return Math.ceil(cards.value.length / CARDS_PER_SLIDE);
});

const visibleCards = computed(() => {
  const start = currentSlide.value * CARDS_PER_SLIDE;
  const end = start + CARDS_PER_SLIDE;
  return cards.value.slice(start, end);
});

// ================== SLIDE FUNCTIONS ==================
const nextSlide = () => {
  if (currentSlide.value < totalSlides.value - 1) {
    currentSlide.value++;
  }
};

const prevSlide = () => {
  if (currentSlide.value > 0) {
    currentSlide.value--;
  }
};

const goToSlide = (slideIndex) => {
  if (slideIndex >= 0 && slideIndex < totalSlides.value) {
    currentSlide.value = slideIndex;
  }
};

// ================== INDEXEDDB HELPERS ==================
function openDB() {
  return new Promise((resolve, reject) => {
    const request = indexedDB.open(DB_NAME, DB_VERSION);
    request.onerror = () => reject(request.error);
    request.onsuccess = () => resolve(request.result);
    request.onupgradeneeded = (event) => {
      const db = event.target.result;
      if (!db.objectStoreNames.contains(STORE_NAME)) {
        db.createObjectStore(STORE_NAME, { autoIncrement: true });
      }
    };
  });
}

async function saveCards(cardArray) {
  const db = await openDB();
  const tx = db.transaction(STORE_NAME, "readwrite");
  const store = tx.objectStore(STORE_NAME);
  store.clear();
  cardArray.forEach((card) => {
    store.add(card);
  });
  return new Promise((resolve, reject) => {
    tx.oncomplete = () => resolve();
    tx.onerror = () => reject(tx.error);
  });
}

async function getCardsFromDB() {
  const db = await openDB();
  const tx = db.transaction(STORE_NAME, "readonly");
  const store = tx.objectStore(STORE_NAME);

  return new Promise((resolve, reject) => {
    const cardsData = [];
    const cursorRequest = store.openCursor();
    cursorRequest.onerror = () => reject(cursorRequest.error);
    cursorRequest.onsuccess = () => {
      const cursor = cursorRequest.result;
      if (cursor) {
        cardsData.push(cursor.value);
        cursor.continue();
      } else {
        resolve(cardsData);
      }
    };
  });
}

function isCacheValid() {
  const timestamp = localStorage.getItem(CACHE_KEY_TIMESTAMP);
  if (!timestamp) return false;
  const age = Date.now() - Number(timestamp);
  return age < CACHE_EXPIRY_MS;
}

// ================== FETCH & CACHE ==================
const fetchCards = async () => {
  loadingCards.value = true;
  try {
    const response = await axios.get("/get_cards", {
      params: { flag: "A" },
    });

    if (response.data && Array.isArray(response.data.data)) {
      const cardData = await Promise.all(
        response.data.data.map(async (card) => {
          const filePath = "/storage/" + card.image.replace("public/", "");
          try {
            const imageResponse = await fetch(filePath);
            const imageBlob = await imageResponse.blob();
            const imageBase64 = await new Promise((resolve, reject) => {
              const reader = new FileReader();
              reader.onloadend = () => resolve(reader.result);
              reader.onerror = reject;
              reader.readAsDataURL(imageBlob);
            });

            return {
              ...card,
              image: imageBase64,
            };
          } catch (imageError) {
            console.error(`Failed to fetch image for card ${card.id}:`, imageError);
            return {
              ...card,
              image: filePath,
            };
          }
        })
      );

      cards.value = cardData.sort((a, b) => a.order - b.order);
      await saveCards(cards.value);
      localStorage.setItem(CACHE_KEY_TIMESTAMP, Date.now().toString());
    } else {
      console.error("Invalid data format from API");
    }
  } catch (error) {
    console.error("Fetch failed, trying cached cards:", error);
    const cachedCards = await getCardsFromDB();
    if (cachedCards.length) {
      cards.value = cachedCards;
    }
  } finally {
    loadingCards.value = false;
  }
};

async function loadCards() {
  if (isCacheValid()) {
    const cachedCards = await getCardsFromDB();
    if (cachedCards.length) {
      cards.value = cachedCards;
      return;
    }
  }
  await fetchCards();
}

// ================== TITLE HELPER ==================
const getCardsTitle = (newsItem) => {
  switch (props.language) {
    case "Hindi":
      return newsItem.hindi_title || newsItem.english_title;
    case "Khasi":
      return newsItem.khasi_title || newsItem.english_title;
    case "English":
    default:
      return newsItem.card_title;
  }
};

// ================== LIFECYCLE ==================
onMounted(() => {
  loadCards();
});
</script>

<style scoped>
.navigation-buttons {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 30px;
  gap: 15px;
}

.nav-btn {
  display: flex;
  align-items: center;
  gap: 8px;
  padding: 10px 20px;
  background: #007bff;
  color: white;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  transition: all 0.3s ease;
  font-size: 14px;
  font-weight: 500;
}


</style>