<script setup lang="ts">
import axios from 'axios';
import { onMounted, ref } from 'vue';

const weather = ref<any[]>([]);
const loading = ref(true);
const error = ref<string | null>(null);
const locationName = ref('Current Location');
const showManualInput = ref(false);
const manualLocation = ref('');
const selectedPeriod = ref<any | null>(null);

const searchLocation = async () => {
    if (!manualLocation.value.trim()) return;

    loading.value = true;
    error.value = null;

    try {
        // Use OpenStreetMap Nominatim API for geocoding
        const response = await axios.get(
            'https://nominatim.openstreetmap.org/search',
            {
                params: {
                    q: manualLocation.value,
                    format: 'json',
                    limit: 1,
                },
            },
        );

        if (response.data && response.data.length > 0) {
            const result = response.data[0];
            const lat = parseFloat(result.lat);
            const lng = parseFloat(result.lon);

            locationName.value = result.display_name.split(',')[0];
            await fetchWeather(lat, lng);
            showManualInput.value = false;
        } else {
            error.value = 'Location not found. Please try a different search.';
        }
    } catch {
        error.value = 'Failed to search location.';
    } finally {
        loading.value = false;
    }
};

// 1. The function to get coordinates from the browser
const getUserLocation = () => {
    loading.value = true;
    error.value = null;

    if (!navigator.geolocation) {
        error.value = 'Geolocation is not supported by this browser.';
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
        (err: GeolocationPositionError) => {
            console.error(err);
            loading.value = false;
            switch (err.code) {
                case err.PERMISSION_DENIED:
                    error.value =
                        'Location permission denied. Please enable it to see weather.';
                    break;
                case err.TIMEOUT:
                    error.value = 'Location request timed out.';
                    break;
                default:
                    error.value = 'Unable to retrieve location.';
            }
        },
        // Options
        {
            enableHighAccuracy: false, // Set true only if you need street-level precision (uses more battery)
            timeout: 10000, // Wait 10 seconds max
            maximumAge: 60000, // Reuse cached location if it's less than 1 min old
        },
    );
};

// 2. Fetch weather using the coordinates we just found
const fetchWeather = async (lat: number, lon: number) => {
    try {
        // Pass the coordinates to your Laravel controller
        const response = await axios.get('/api/weather', {
            params: { lat, lng: lon },
        });

        // Take the first 3 periods
        weather.value = response.data.slice(0, 3);
    } catch (err) {
        error.value = 'Weather unavailable.';
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
    <div class="max-w-sm rounded-lg bg-black p-4 px-[2rem] shadow-md">
        <div class="mb-4 flex items-center justify-between">
            <h3 class="text-lg font-bold text-white">
                {{ locationName }}
            </h3>
            <button
                @click="getUserLocation"
                class="text-xs text-blue-500 underline hover:text-blue-700"
            >
                Refresh
            </button>
        </div>

        <div v-if="loading" class="animate-pulse text-(--periwinkle)">
            Locating you...
        </div>

        <div v-else-if="error" class="mb-4 text-sm text-red-500">
            {{ error }}
            <button
                @click="showManualInput = true"
                class="mt-2 block text-xs text-blue-500 underline hover:text-blue-700"
            >
                Enter location manually
            </button>
        </div>

        <div v-if="showManualInput" class="mb-4 rounded bg-gray-50 p-3">
            <input
                v-model="manualLocation"
                @keyup.enter="searchLocation"
                placeholder="Enter any city, address, or location"
                class="mb-2 w-full rounded border p-2 text-sm"
            />
            <div class="flex gap-2">
                <button
                    @click="searchLocation"
                    class="rounded bg-blue-500 px-3 py-1 text-xs text-white hover:bg-blue-600"
                >
                    Search
                </button>
                <button
                    @click="showManualInput = false"
                    class="rounded bg-gray-300 px-3 py-1 text-xs text-gray-700 hover:bg-gray-400"
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
                <button
                    popovertarget="detailedForecast"
                    @click="selectedPeriod = period"
                    class="flex w-full items-center space-x-4 text-left hover:bg-blue-500/30"
                >
                    <img
                        :src="period.icon"
                        :alt="period.shortForecast"
                        class="h-12 w-12 rounded-full bg-gray-100"
                    />
                    <div>
                        <p class="font-semibold text-white">
                            {{ period.name }}
                        </p>
                        <p class="text-sm text-white">
                            {{ period.temperature }}°{{
                                period.temperatureUnit
                            }}
                            - {{ period.shortForecast }}
                        </p>
                    </div>
                </button>
            </div>
            <div
                anchor="forecast"
                id="detailedForecast"
                popover
                class="weather-dialog max-w-md rounded-lg bg-(--moonbeam) p-4 text-black"
            >
                <p v-if="selectedPeriod">
                    {{ selectedPeriod.detailedForecast }}
                </p>

                <button
                    popovertarget="detailedForecast"
                    popovertargetaction="hide"
                    class="mt-2 rounded bg-blue-500 px-3 py-1 text-xs text-white hover:bg-blue-600"
                >
                    Close
                </button>
            </div>
        </div>
    </div>
</template>

<style scoped>
.weather-dialog::backdrop {
    background-image: var(--weather-icon);
    background-size: cover;
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
