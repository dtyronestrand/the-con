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
    <div class="max-w-sm bg-surface-raised p-4 px-[2rem] shadow-md">
        <div class="mb-4 flex items-center justify-between">
            <h3 class="text-lg font-semibold text-on-surface">
                {{ locationName }}
            </h3>
            <button
                @click="getUserLocation"
                class="text-xs text-tertiary underline hover:text-tertiary-strong"
            >
                Refresh
            </button>
        </div>

        <div v-if="loading" class="animate-pulse text-panel-secondary">
            Locating you...
        </div>

        <div v-else-if="error" class="mb-4 text-sm text-error">
            {{ error }}
            <button
                @click="showManualInput = true"
                class="mt-2 block text-xs text-tertiary underline hover:text-tertiary-strong"
            >
                Enter location manually
            </button>
        </div>

        <div v-if="showManualInput" class="mb-4 rounded bg-surface-overlay p-3">
            <input
                v-model="manualLocation"
                @keyup.enter="searchLocation"
                placeholder="Enter any city, address, or location"
                class="mb-2 w-full rounded border border-border bg-surface-raised p-2 text-sm text-on-surface"
            />
            <div class="flex gap-2">
                <button
                    @click="searchLocation"
                    class="rounded bg-tertiary px-3 py-1 text-xs text-ink hover:bg-tertiary-strong"
                >
                    Search
                </button>
                <button
                    @click="showManualInput = false"
                    class="rounded bg-panel-secondary px-3 py-1 text-xs text-ink hover:bg-panel-secondary-strong"
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
                class="flex items-center space-x-4 border-b-3 border-panel-secondary-strong pb-2 last:border-0"
            >
                <button
                    popovertarget="detailedForecast"
                    @click="selectedPeriod = period"
                    class="flex w-full items-center space-x-4 text-left hover:bg-tertiary/20"
                >
                    <img
                        :src="period.icon"
                        :alt="period.shortForecast"
                        class="h-12 w-12 rounded-full bg-surface-raised"
                    />
                    <div>
                        <p class="font-semibold text-on-surface">
                            {{ period.name }}
                        </p>
                        <p
                            class="font-mono text-sm text-panel-secondary tabular-nums"
                        >
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
                class="weather-dialog max-w-md rounded-lg bg-surface-overlay p-4 text-on-surface"
            >
                <p v-if="selectedPeriod">
                    {{ selectedPeriod.detailedForecast }}
                </p>

                <button
                    popovertarget="detailedForecast"
                    popovertargetaction="hide"
                    class="mt-2 rounded bg-tertiary px-3 py-1 text-xs text-ink hover:bg-tertiary-strong"
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
