<script setup>
import moment from 'moment';

import AppSelect from '@/Components/Select.vue';
import { usePage } from "@inertiajs/inertia-vue3";
import { computed, onMounted, ref } from "vue";

const dateIndex = ref(0);

const dates = ref([10, 11, 12, 13, 14, 15, 16, 17, 18])

const data = computed(() => usePage().props.value.weatherData);

const options = computed(() => usePage().props.value.dataOptions);


const _selectedOptions = ref([]);

const selectedOptions = computed({
    set: (_value) => {
        _selectedOptions.push(value)
    },

    get: () => {
        return _selectedOptions.value;
    }
})


const windData = computed(() => {
    return data.value
        .map(row => {
            return {
                formattedTime: moment(row.time).format("ddd, hA"),
                localTime: moment(row.time).format(),
                localTimeFormatted: moment(row.time).format('dddd Do MMMM YYYY'),
                ...row
            }
        })
        .filter(row => {
            console.log(row.localTime.slice(8, 10));
            return row.localTime.slice(8, 10) == dates.value[dateIndex.value];
        });
})

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

onMounted(() => {
    const defaultOptions = [
        'wd',
        'wsp',
        'gst',
        'vis',
    ];

    options.value.forEach(option => {
        if (defaultOptions.includes(option.code)) {
            selectedOptions.value.push(option);
        }
    });
})



</script>

<template>

    <div class="grid grid-cols-3 gap-4 content-center">
        <button
            :disabled="dateIndex < 1"
            :class="{ 'opacity-50': dateIndex < 1 }"
            class="m-2 px-4 py-2 font-semibold text-sm bg-sky-500 text-white rounded-none shadow-sm"
            @click="bumpDown()"
        >Prev</button>

        <span
            v-if="windData && windData[0]"
            class="m-2 px-4 py-2 font-semibold text-sm shadow-sm text-center"
        >
            {{ windData[0].localTimeFormatted }}
        </span>

        <button
            :disabled="dateIndex + 1 >= dates.length"
            :class="{ 'opacity-50': dateIndex + 1 >= dates.length }"
            class="m-2 px-4 py-2 font-semibold text-sm bg-sky-500 text-white rounded-none shadow-sm"
            @click="bumpUp()"
        >Next</button>

    </div>

    <AppSelect
        :options="options"
        :value="selectedOptions"
        @input="selectedOptions"
        placeholder="Select Options you wish to review"
    ></AppSelect>


    <table class="border-separate border-spacing-4 border min-w-full border-sky-200">
        <thead>
            <tr>
                <th class="w-32">Time</th>
                <th
                    v-for="option in selectedOptions"
                    :key="option.code"
                >{{ option.option }}</th>
            </tr>
        </thead>
        <tbody>
            <tr
                v-for="row in windData"
                :key="row.time"
            >
                <td>{{ row.formattedTime }}</td>
                <td
                    v-for="option in selectedOptions"
                    :key="`data_row_${option.code}`"
                    class="text-right"
                >

                    {{ row[option.code].value }}{{ row[option.code].unit || '' }}

                </td>
            </tr>
        </tbody>
    </table>
</template>
