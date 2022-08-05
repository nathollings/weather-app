<script setup>
import moment from 'moment';
import { computed } from "vue";
import { usePage } from "@inertiajs/inertia-vue3";
import { onMounted, ref } from "vue";

const dateIndex = ref(0);

const dates = ref([10, 11, 12, 13, 14, 15, 16, 17, 18])

const data = computed(() => usePage().props.value.weatherData);
const windLabels = data.value.map((hour) => {
    return hour.time;
});

const windData = data.value
    .filter(row => {
        console.log(row.time.slice(8, 10));
        return row.time.slice(8, 10) == dates.value[dateIndex.value];
    })
    .map((hour) => {

        return {
            time: moment(hour.time).format("ddd, hA"),
            wd: hour.wd.value + hour.wd.unit,
            wsp: hour.wsp.value + hour.wsp.unit,
            gst: hour.gst.value + hour.gst.unit,
            vis: hour.vis.value + hour.vis.unit,
        };
    });


const bumpUp = () => {
    if ((dateIndex.value.length + 1) > dates.value.length) {
        return;
    }
    dateIndex.value += 1;
}

const bumpDown = () => {
    if (dateIndex.value == 0) {
        return;
    }
    dateIndex.value -= 1;
}



</script>

<template>

    <button
        :disabled="dateIndex < 1"
        :class="{ 'opacity-50': dateIndex < 1 }"
        class="m-2 px-4 py-2 font-semibold text-sm bg-sky-500 text-white rounded-none shadow-sm"
        @click="bumpDown()"
    >Prev</button>
    <button
        :disabled="dateIndex >= dates.length"
        :class="{ 'opacity-50': dateIndex >= dates.length }"
        class="m-2 px-4 py-2 font-semibold text-sm bg-sky-500 text-white rounded-none shadow-sm"
        @click="bumpUp()"
    >Next</button>


    <table class="border-separate border-spacing-4 border border-slate-500">
        <thead>
            <tr>
                <th class="px-2">Date Time</th>
                <th class="px-2">Wind Direction</th>
                <th class="px-2">Wind Speed</th>
                <th class="px-2">Typical Gust Speed</th>
                <th class="px-2">Visibility</th>
            </tr>
        </thead>
        <tbody>
            <tr
                v-for="row in windData"
                :key="row.time"
            >
                <td>{{ row.time }}</td>
                <td>{{ row.wd }}</td>
                <td>{{ row.wsp }}</td>
                <td>{{ row.gst }}</td>
                <td>{{ row.vis }}</td>
            </tr>
        </tbody>
    </table>
</template>
