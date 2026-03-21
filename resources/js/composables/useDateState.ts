import { ref } from 'vue';

const selectedYear = ref(new Date().getFullYear());
const selectedMonth = ref(new Date().getMonth());
const selectedDate = ref(new Date().getDate());

export function useDateState() {
    function setSelectedDate(year: number, month: number, date: number) {
        selectedYear.value = year;
        selectedMonth.value = month;
        selectedDate.value = date;
    }

    function setSelectedYear(year: number) {
        selectedYear.value = year;
    }

    function setSelectedMonth(month: number) {
        selectedMonth.value = month;
    }

    function setSelectedDay(date: number) {
        selectedDate.value = date;
    }

    return {
        selectedYear,
        selectedMonth,
        selectedDate,
        setSelectedDate,
        setSelectedYear,
        setSelectedMonth,
        setSelectedDay,
    };
}
