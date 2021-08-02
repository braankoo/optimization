export default {
    data() {
        return {
            swapped: false,
            perPage: 50,
            rows: 0,
            filter: {
                name: '',
                startDate: '',
                endDate: ''
            },
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
            vm.filter.startDate = to.query.startDate;
            vm.filter.endDate = to.query.endDate;
        })
    }
    ,
    watch: {
        'filter.startDate':
            {
                handler(newValue) {
                    this.$refs.charts.reload(newValue, this.filter.endDate);

                }
            }
        ,
        'filter.endDate':
            {
                handler(newValue) {
                    this.$refs.charts.reload(this.filter.startDate, newValue);

                }
            }
    }
}
