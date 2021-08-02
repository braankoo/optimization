<script>
import {Line, mixins} from 'vue-chartjs';

const {reactiveProp, reactiveData} = mixins;
export default {
    extends: Line,
    name: "LineChart",
    mixins: [reactiveProp],
    props: ['chartData', 'options', 'datasetsOptions'],
    mounted() {
        // this.chartData is created in the mixin.
        // If you want to pass options please create a local options object
        console.log(this.chartData, this.options);
        this.renderChart(this.chartData, this.options)
    },
    watch: {
        chartData: {
            deep: true,
            handler(newVal) {

                for (const dataSetOption in this.datasetsOptions) {

                    const dataset = this.chartData.datasets.find(({label}) => dataSetOption === label);
                    for (const option in this.datasetsOptions[dataSetOption]) {
                        dataset[option] = this.datasetsOptions[dataSetOption][option];
                    }

                }
                this.renderChart(this.chartData, this.options);
            }
        }
    }
}
</script>

<style scoped>

</style>
