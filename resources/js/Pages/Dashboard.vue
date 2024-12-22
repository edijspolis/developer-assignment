<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import moment from "moment-timezone";
import flatPickr from 'vue-flatpickr-component';
import 'flatpickr/dist/flatpickr.css';
import {
  Chart as ChartJS,
  CategoryScale,
  LinearScale,
  PointElement,
  LineElement,
  Title,
  Tooltip,
  Legend
} from 'chart.js';
import { Line } from 'vue-chartjs';
import { Head, useForm } from '@inertiajs/vue3';

const props = defineProps({
    date: {
        type: Date
    },
    enabledDates: {
        type: Array
    },
    prices: {
        type: Object
    }
});

const flatpickrConfig = {
    locale: {
        firstDayOfWeek: 1
    },
    enable: props.enabledDates
};

const getChartData = function() {
    let labels = [];
    let values = [];

    for (let item of props.prices) {
        labels.push(moment.tz(item.delivery_start, "YYYY-MM-DD HH:mm", "").tz('Europe/Riga').format("MMMM Do YYYY, HH:mm"));
        values.push(item.price);
    }

    return {
        labels: labels,
        datasets: [
            {
                backgroundColor: '#818CF8',
                data: values
            }
        ]
    }
}

let chartData = getChartData();

const chartOptions = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        legend: {
            display: false
        }
    },
    scales:{
        x: {
            display: false
        }
    }
}

ChartJS.register(
  CategoryScale,
  LinearScale,
  PointElement,
  LineElement,
  Title,
  Tooltip,
  Legend
);

const form = useForm({
    date: props.date
});

const submit = () => {
    form.post(route('dashboard'), {
    preserveScroll: true,
        onSuccess: function () {
            chartData = getChartData();
        }
    });
};

</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border shadow sm:p-6">
                    <div class="relative rounded-md overflow-auto">
                        <div class="mb-3">
                            <InputLabel for="date" value="Date" />

                            <flat-pickr :config="flatpickrConfig" @on-change="submit" id="date" class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm mt-1 block w-full" v-model="form.date" required ></flat-pickr>

                            <InputError class="mt-2" :message="form.errors.date" />
                        </div>
                        <div class="shadow-sm overflow-hidden" style="height: 400px;overflow: auto;">
                            <Line :data="chartData" :options="chartOptions" />
                        </div>
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border shadow mt-2 sm:p-6">
                    <div class="relative rounded-md overflow-auto">
                        <div class="shadow-sm overflow-hidden" style="overflow: auto;">
                            <table class="border-collapse table-auto w-full text-sm" v-if="props.prices.length">
                                <thead>
                                    <tr>
                                        <th class="border-b font-medium p-4 pl-8 pt-0 pb-3 text-slate-500 text-left">Delivery start</th>
                                        <th class="border-b font-medium p-4 pl-8 pt-0 pb-3 text-slate-500 text-left">Delivery end</th>
                                        <th class="border-b font-medium p-4 pl-8 pt-0 pb-3 text-slate-500 text-center">Price</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="odd:bg-white even:bg-slate-50" v-for="item in props.prices">
                                        <td class="border-b border-slate-100 p-4 pl-8 text-slate-500">{{moment.tz(item.delivery_start, "YYYY-MM-DD HH:mm", "").tz('Europe/Riga').format("MMMM Do YYYY, HH:mm")}}</td>
                                        <td class="border-b border-slate-100 p-4 pl-8 text-slate-500">{{moment.tz(item.delivery_end, "YYYY-MM-DD HH:mm", "").tz('Europe/Riga').format("MMMM Do YYYY, HH:mm")}}</td>
                                        <td class="border-b border-slate-100 p-4 pl-8 text-slate-500 text-center">{{item.price.toFixed(2)}} €</td>
                                    </tr>
                                </tbody>
                            </table>
                            <p v-else >No prices available.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
