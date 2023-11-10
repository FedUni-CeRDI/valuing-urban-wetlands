<script>
import Plotly from 'plotly.js-basic-dist-min';

import 'bootstrap-icons/font/bootstrap-icons.scss';
import {forEach} from 'lodash';

export default {
    props: [
        'landUseData',
        'index',
    ],
    data() {
        return {
            showAllData: false,
        };
    },
    methods: {
        toggleTable() {
            this.showAllData = !this.showAllData;
        },
        showDataRow(row) {
            return row === 0 || this.showAllData;
        },
    },
    mounted() {
        let data = [];
        let self = this;
        let usage = self.landUseData;

        forEach(usage.data, function(datum) {
            let trace = {
                name: datum.usage,
                hoverinfo: 'name',
                x: [],
                y: [usage.label],
                type: 'bar',
                orientation: 'h',
            };

            trace.x.push(datum.percentage);
            data.push(trace);
        });

        let container = self.$el.getElementsByClassName('landUseChart')[0];

        Plotly.newPlot(container, data, {
                showlegend: false,
                margin: {'t': 0, 'b': 0, 'l': 0, 'r': 0, pad: 0},
                barmode: 'stack',
                height: 20,
                yaxis: {
                    zeroline: false,
                    showgrid: false,
                    visible: false,
                },
                xaxis: {
                    visible: false,
                    range: [0, 100],
                },
            },
            {
                displayModeBar: false,
                responsive: true,
            },
        );

    },
};
</script>

<template>
    <div>
        <button
            type="button"
            class="btn btn-sm float-end"
            @click="toggleTable()"
            v-show="landUseData.data.length > 1"
        >
            <i class="bi bi-clipboard-data"></i>
        </button>
        <h3>{{ landUseData.label }}</h3>
        <div class="landUseChart"></div>
        <div class="landUseTable">
            <table class="table">
                <tr v-show="showDataRow(key)" v-for="(data, key) in landUseData.data">
                    <td>{{ data.usage }}</td>
                    <td class="text-end">{{ data.percentage }}</td>
                </tr>
            </table>
        </div>
    </div>
</template>

<style scoped>

</style>
