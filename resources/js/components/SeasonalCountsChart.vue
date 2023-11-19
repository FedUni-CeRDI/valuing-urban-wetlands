<script>
import Plotly from 'plotly.js-basic-dist-min';
import 'bootstrap-icons/font/bootstrap-icons.scss';

export default {
    props: [
        'seasonalCounts',
        'showChart',
        'index',
    ],
    data() {
        return {
            chartTitle: 'Latham\'s Snipe Project Counts By Season',
        };
    },
    methods: {
        drawSeasonalCountsChart() {
            let data = [];
            let self = this;

            let trace = {
                x: [],
                y: [],
                type: 'bar',
                orientation: 'v',
            };

            self.seasonalCounts.forEach(function(datum) {
                trace.x.push(datum.season);
                trace.y.push(datum.count);
            });

            data.push(trace);

            let container = self.$el.getElementsByClassName('seasonalCountChart')[0];

            Plotly.newPlot(container, data, {
                    showlegend: false,
                    margin: {'t': 0, 'l': 20, 'r': 30, pad: 0},
                    autosize: true,
                    height: 400,
                },
                {
                    displayModeBar: false,
                    responsive: true,
                },
            );

        },
    },
    mounted() {
        this.drawSeasonalCountsChart();
    },
};
</script>

<template>
    <div>
        <div class="seasonalCountChart"></div>
    </div>
</template>

<style scoped>
.seasonalCountChart {
    height: 400px;
    max-width: 100%;
}

</style>
