<script setup>
import moment from 'moment';
import { usePage } from "@inertiajs/inertia-vue3";
import { computed, watchEffect, onMounted, ref, nextTick } from "vue";
import Chart from 'chart.js/auto';
import AppSelect from '@/Components/Select.vue'

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

const chartOptions = ref(null);

const _chartData = ref(null);


const chartData = computed({
    get: () => {
        return _chartData;
    },
    set: (value) => {
        _chartData.value = value
    }
})

const ctx = ref(null);

const bumpUp = () => {
    if ((dateIndex.value.length + 1) > dates.value.length) {
        return;
    }
    dateIndex.value += 1;

    updateChart();
}

const bumpDown = () => {
    if (dateIndex.value == 0) {
        return;
    }
    dateIndex.value -= 1;
    updateChart();
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

    updateChart();

});



const updateChart = async () => {



    const datasets = [];
    console.log(selectedOptions.value);
    selectedOptions.value.forEach((element, index) => {
        datasets.push({
            label: element.option,
            data: windData.value.map(row => {
                return row[element.code].value;
            }),
            fill: false,
            borderColor: `rgb(${255 / Math.random() * 255}, ${Math.random() * 255 / index}, ${Math.random() * 255 / index})`,
            tension: 0.1
        })
    });

    const dayLabels = windData.value.map((hour) => {
        return hour.formattedTime;
    });

    const chartData = {
        labels: dayLabels,
        datasets,
    };

    const ctx = document.getElementById('app-chart');


    if (chartOptions.value) {

        chartOptions.value.stop();

        chartOptions.value.destroy();
    }

    chartOptions.value = new Chart(ctx, {
        type: 'line',
        data: chartData,
        options: {
            animation: {
                duration: 0
            }
        }
    })


};



</script>


<template>
    <div>

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
            @input="selectedOptions; updateChart();"
            placeholder="Select Options you wish to chart"
        ></AppSelect>

        <canvas
            id="app-chart"
            width="400"
            height="200"
            ref="chart"
        ></canvas>
    </div>
</template>
