<script setup >
import { ref, onMounted } from 'vue';
import axios from 'axios';

const weather = ref([]);
const loading = ref(true);
const error = ref(null);
const locationName = ref("Current Location");
const showManualInput = ref(false);
const manualLocation = ref('');
const selectedPeriod = ref(null);
const dialogRef = ref(null);


function closeModal() {
    if (dialogRef.value) {
        dialogRef.value.close();
    }
    selectedPeriod.value = null;
}
const searchLocation = async () => {
    if (!manualLocation.value.trim()) return;
    
    loading.value = true;
    error.value = null;
    
    try {
        // Use OpenStreetMap Nominatim API for geocoding
        const response = await axios.get('https://nominatim.openstreetmap.org/search', {
            params: {
                q: manualLocation.value,
                format: 'json',
                limit: 1
            }
        });
        
        if (response.data && response.data.length > 0) {
            const result = response.data[0];
            const lat = parseFloat(result.lat);
            const lng = parseFloat(result.lon);
            
            locationName.value = result.display_name.split(',')[0];
            await fetchWeather(lat, lng);
            showManualInput.value = false;
        } else {
            error.value = "Location not found. Please try a different search.";
        }
    } catch (err) {
        error.value = "Failed to search location.";
    } finally {
        loading.value = false;
    }
};



// 1. The function to get coordinates from the browser
const getUserLocation = () => {
    loading.value = true;
    error.value = null;

    if (!navigator.geolocation) {
        error.value = "Geolocation is not supported by this browser.";
        loading.value = false;
        return;
    }

    // Request the position
    navigator.geolocation.getCurrentPosition(
        // Success Callback
        (position) => {
            fetchWeather(position.coords.latitude, position.coords.longitude);
        },
        // Error Callback
        (err) => {
            console.error(err);
            loading.value = false;
            switch(err.code) {
                case err.PERMISSION_DENIED:
                    error.value = "Location permission denied. Please enable it to see weather.";
                    break;
                case err.TIMEOUT:
                    error.value = "Location request timed out.";
                    break;
                default:
                    error.value = "Unable to retrieve location.";
            }
        },
        // Options
        {
            enableHighAccuracy: false, // Set true only if you need street-level precision (uses more battery)
            timeout: 10000,            // Wait 10 seconds max
            maximumAge: 60000          // Reuse cached location if it's less than 1 min old
        }
    );
};

// 2. Fetch weather using the coordinates we just found
const fetchWeather = async (lat, lon) => {
    try {
        // Pass the coordinates to your Laravel controller
        const response = await axios.get('/api/weather', {
            params: { lat, lng: lon }
        });
        
        // Take the first 3 periods
        weather.value = response.data.slice(0, 3);
    } catch (err) {
        error.value = "Weather unavailable.";
        console.error(err);
    } finally {
        loading.value = false;
    }
};

// 3. Trigger on mount
onMounted(() => {
    getUserLocation();
});
</script>

<template>
    <div class="p-4 px-[2rem] bg-black rounded-lg shadow-md max-w-sm">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-bold text-white">
                {{ locationName }}

            </h3>
            <button 
                @click="getUserLocation" 
                class="text-xs text-blue-500 hover:text-blue-700 underline"
            >
                Refresh
            </button>
        </div>

        <div v-if="loading" class="text-(--periwinkle) animate-pulse">
            Locating you...
        </div>

        <div v-else-if="error" class="text-red-500 text-sm mb-4">
            {{ error }}
            <button 
                @click="showManualInput = true" 
                class="block mt-2 text-xs text-blue-500 hover:text-blue-700 underline"
            >
                Enter location manually
            </button>
        </div>

        <div v-if="showManualInput" class="mb-4 p-3 bg-gray-50 rounded">
            <input 
                v-model="manualLocation" 
                @keyup.enter="searchLocation"
                placeholder="Enter any city, address, or location"
                class="w-full p-2 border rounded text-sm mb-2"
            >
            <div class="flex gap-2">
                <button 
                    @click="searchLocation" 
                    class="px-3 py-1 bg-blue-500 text-white rounded text-xs hover:bg-blue-600"
                >
                    Search
                </button>
                <button 
                    @click="showManualInput = false" 
                    class="px-3 py-1 bg-gray-300 text-gray-700 rounded text-xs hover:bg-gray-400"
                >
                    Cancel
                </button>
            </div>
        </div>

        <div v-else class="space-y-4">
            <div
                id="forecast"
                v-for="period in weather" 
                :key="period.number" 
                class="flex items-center space-x-4 border-b-3 border-(--blue) pb-2 last:border-0"
            >
            <button popovertarget="detailedForecast" @click="selectedPeriod=period" class="flex items-center space-x-4 w-full text-left hover:bg-blue-500/30">
                <img 
                    :src="period.icon" 
                    :alt="period.shortForecast" 
                    class="w-12 h-12 rounded-full bg-gray-100"
                >
                <div>
                    <p class="font-semibold text-white">{{ period.name }}</p>
                    <p class="text-sm text-white">
                        {{ period.temperature }}°{{ period.temperatureUnit }} - {{ period.shortForecast }}
                    </p>
                </div>
            </button>
            </div>
               <div anchor="forecast" id="detailedForecast" popover class="weather-dialog p-4 rounded-lg bg-(--moonbeam) text-black max-w-md">
            <p v-if="selectedPeriod">{{ selectedPeriod.detailedForecast }}</p>
         
                <button popovertarget="detailedForecast" popovertargetaction="hide" class="mt-2 px-3 py-1 bg-blue-500 text-white rounded text-xs hover:bg-blue-600">Close</button>
           
        </div>
        </div>
        
     
    </div>
</template>

<style scoped>
.weather-dialog::backdrop {
    background-image: var(--weather-icon);
    background-size:cover;
    background-repeat: no-repeat;
    background-position: center;
    opacity: 0.5;
}
#detailedForecast {
    top: anchor(bottom);
    left: anchor(center);
    translate: -50% 0;
}
</style>