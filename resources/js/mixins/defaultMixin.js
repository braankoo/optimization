import moment from "moment";

export default {
    data() {
        return {
            swapped: false,
            perPage: 50,
            rows: 0,
            filter: {
                name: '',
                startDate: moment().subtract('10', 'd').format('YYYY-MM-DD'),
                endDate: moment().format('YYYY-MM-DD')
            },
            currentPage: 1,
            total: {}
        }
    },
    methods: {
        swapTrTh($event) {
            if (!$event && !this.swapped) {
                this.swapped = true;
                this.$nextTick().then(() => {
                    document.querySelector('thead > tr:last-child')
                        .parentNode.insertBefore(
                        document.querySelector('thead > tr:last-child'),
                        document.querySelector('thead > tr:first-child')
                    );
                })

            }

        }
    }
    ,
    computed: {
        fieldsToShow() {
            return this.fields.filter(({visible}) => visible === true);
        }
    }
    ,
    beforeRouteEnter(to, from, next) {
        next(vm => {
            if (to.query.startDate) {
                vm.filter.startDate = to.query.startDate;
            }
            if (to.query.endDate) {
                vm.filter.endDate = to.query.endDate;
            }

        })
    }
    ,
    watch: {
        'filter.startDate':
            {
                handler(newValue) {
                    if (this.$refs.hasOwnProperty('charts')) {
                        this.$refs.charts.reload(newValue, this.filter.endDate);
                    }


                }
            }
        ,
        'filter.endDate':
            {
                handler(newValue) {
                    if (this.$refs.hasOwnProperty('charts')) {
                        this.$refs.charts.reload(this.filter.startDate, newValue);
                    }

                }
            },
        $route(to, from) {
            if (from.name === 'Client Index' && to.name === 'Client Index') {
                this.$refs.charts.reload(this.filter.startDate, this.filter.endDate);
            }
        }
    },
    mounted() {
        if (this.$refs.hasOwnProperty('charts')) {
            this.$refs.charts.reload(this.filter.startDate, this.filter.endDate);
        }
    }
}
