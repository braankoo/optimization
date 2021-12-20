<template>
    <div>
        <b-row class="mb-5">
            <b-col>
                <b-card>
                    <h5>Cost VS Income</h5>
                    <line-chart
                        style="height:350px"
                        :chart-data="charts.CostVsIncome.data"
                        :options="charts.CostVsIncome.options"
                        :datasets-options="{
                    cost : {
                        backgroundColor: 'transparent',
                        borderColor: 'red',
                        borderWidth: 1
                    },
                    earned : {
                        backgroundColor: 'transparent',
                        borderColor: 'green',
                        borderWidth: 1
                    }
                }"
                    />
                </b-card>
            </b-col>
            <b-col>
                <b-card>
                    <h5>P/L</h5>
                    <line-chart
                        style="height:350px"
                        :chart-data="charts.PL.data"
                        :options="charts.PL.options"
                        :datasets-options="{
                    pl : {
                        backgroundColor: 'transparent',
                        borderColor: 'purple',
                        borderWidth: 1
                    },

                }"
                    />
                </b-card>
            </b-col>
        </b-row>
        <b-row class="mb-5">
            <b-col>
                <b-card>
                    <h5>Profiles VS Upgrades</h5>
                    <line-chart
                        style="height:350px"
                        :chart-data="charts.ProfilesVsUpgrades.data"
                        :options="charts.ProfilesVsUpgrades.options"
                        :datasets-options="{
                    profiles : {
                        backgroundColor: 'transparent',
                        borderColor: 'purple',
                        borderWidth: 1
                    },
                    upgrades : {
                        backgroundColor: 'transparent',
                        borderColor: 'blue',
                        borderWidth: 1
                    }
                }"
                    />
                </b-card>
            </b-col>
            <b-col>
                <b-card>
                    <h5>Upgrade Ratio</h5>
                    <line-chart
                        style="height:350px"
                        :chart-data="charts.UpgradeRatio.data"
                        :options="charts.UpgradeRatio.options"
                        :datasets-options="{
                    ur : {
                        backgroundColor: 'transparent',
                        borderColor: 'blue',
                        borderWidth: 1
                    },

                }"
                    />
                </b-card>
            </b-col>
        </b-row>
        <b-row class="mb-5">
            <b-col>
                <b-card>
                    <h5>Actual CPA</h5>
                    <line-chart
                        style="height:350px"
                        :chart-data="charts.ActualCPA.data"
                        :options="charts.ActualCPA.options"
                        :datasets-options="{
                    cpa : {
                        backgroundColor: 'transparent',
                        borderColor: 'blue',
                        borderWidth: 1
                    },
                }"
                    />
                </b-card>
            </b-col>
        </b-row>
        <b-row class="mb-5">
            <b-col>
                <b-card>
                    <h5>CTR</h5>
                    <line-chart
                        style="height:350px"
                        :chart-data="charts.CTR.data"
                        :options="charts.CTR.options"
                        :datasets-options="{
                    ctr : {
                        backgroundColor: 'transparent',
                        borderColor: 'blue',
                        borderWidth: 1
                    },
                }"
                    />
                </b-card>
            </b-col>
            <b-col>
                <b-card>
                    <h5>CR</h5>
                    <line-chart
                        style="height:350px"
                        :chart-data="charts.CR.data"
                        :options="charts.CR.options"
                        :datasets-options="{
                    cr : {
                        backgroundColor: 'transparent',
                        borderColor: 'blue',
                        borderWidth: 1
                    },
                }"
                    />
                </b-card>
            </b-col>
        </b-row>
    </div>
</template>

<script>
import LineChart from "../chart/LineChart";


export default {
    name: "defaultCharts",
    props: {
        platform: {
            type: String,
            required: false
        }
    },
    components: {LineChart},
    data() {
        return {
            charts: {
                CostVsIncome: {
                    request: 'cost|earned',
                    data: {
                        labels: [],
                        datasets: []
                    },
                    options: {
                        maintainAspectRatio: false,
                        responsive: true,
                        xAxes: [{
                            gridLines: {
                                display: false,
                            },
                            ticks: {
                                fontSize: 15,
                                fontColor: 'lightgrey'
                            }
                        }],
                        scales: {
                            xAxes: [{
                                gridLines: {
                                    drawBorder: false,
                                    display: false,
                                }
                            }],
                            yAxes: [{
                                gridLines: {
                                    drawBorder: false,
                                    display: false,
                                },
                                beginAtZero: true,
                                ticks: {
                                    beginAtZero: true,
                                    callback: function (value) {

                                        return `$${value / 1000}k`;


                                    }
                                }
                            }]
                        },
                        legend: {
                            position: 'bottom'
                        },
                        animation: {
                            duration: 1000,
                            easing: 'linear'
                        },
                    }
                },
                PL: {
                    request: 'pl',
                    data: {
                        labels: [],
                        datasets: []
                    },
                    options: {
                        maintainAspectRatio: false,
                        responsive: true,

                        scales: {
                            xAxes: [{
                                gridLines: {

                                    color: "red",
                                    display: false,
                                    drawBorder: false,
                                    zeroLineColor: "red",
                                    zeroLineWidth: 1
                                }
                            }],
                            yAxes: [{
                                gridLines: {
                                    color: "transparent",
                                    display: true,
                                    drawBorder: false,
                                    zeroLineColor: "red",
                                    zeroLineWidth: 1
                                },
                                beginAtZero: true,
                                ticks: {
                                    beginAtZero: true,
                                    callback: function (value) {

                                        return `$${value / 1000}k`;


                                    }
                                }
                            }]
                        },
                        legend: {
                            position: 'bottom'
                        },
                    },


                },
                ProfilesVsUpgrades: {
                    request: 'profiles|upgrades',
                    data: {
                        labels: [],
                        datasets: []
                    },
                    options: {
                        maintainAspectRatio: false,
                        responsive: true,
                        xAxes: [{
                            gridLines: {
                                display: false,
                            },
                            ticks: {
                                fontSize: 15,
                                fontColor: 'lightgrey'
                            }
                        }],
                        scales: {
                            xAxes: [{
                                gridLines: {
                                    drawBorder: false,
                                    display: false,
                                }
                            }],
                            yAxes: [{
                                gridLines: {
                                    drawBorder: false,
                                    display: false,
                                }
                            }]
                        },
                        legend: {
                            position: 'bottom'
                        },
                    },

                },
                UpgradeRatio: {
                    request: 'ur',
                    data: {
                        labels: [],
                        datasets: []
                    },
                    options: {
                        maintainAspectRatio: false,
                        responsive: true,
                        xAxes: [{
                            gridLines: {
                                display: false,
                            },
                            ticks: {
                                fontSize: 15,
                                fontColor: 'lightgrey'
                            }
                        }],
                        scales: {
                            xAxes: [{
                                gridLines: {
                                    drawBorder: false,
                                    display: false,
                                }
                            }],
                            yAxes: [{
                                gridLines: {
                                    drawBorder: false,
                                    display: false,
                                }
                            }]
                        },
                        legend: {
                            position: 'bottom'
                        },
                    },

                },
                ActualCPA: {
                    request: 'cpa',
                    data: {
                        labels: [],
                        datasets: []
                    },
                    options: {
                        maintainAspectRatio: false,
                        responsive: true,
                        xAxes: [{
                            gridLines: {
                                display: false,
                            },
                            ticks: {
                                fontSize: 15,
                                fontColor: 'lightgrey'
                            }
                        }],
                        scales: {
                            xAxes: [{
                                gridLines: {
                                    drawBorder: false,
                                    display: false,
                                }
                            }],
                            yAxes: [{
                                gridLines: {
                                    drawBorder: false,
                                    display: false,
                                }
                            }]
                        },
                        legend: {
                            position: 'bottom'
                        },
                    },

                },
                CTR: {
                    request: 'ctr',
                    data: {
                        labels: [],
                        datasets: []
                    },
                    options: {
                        maintainAspectRatio: false,
                        responsive: true,
                        xAxes: [{
                            gridLines: {
                                display: false,
                            },
                            ticks: {
                                fontSize: 15,
                                fontColor: 'lightgrey'
                            }
                        }],
                        scales: {
                            xAxes: [{
                                gridLines: {
                                    drawBorder: false,
                                    display: false,
                                }
                            }],
                            yAxes: [{
                                gridLines: {
                                    drawBorder: false,
                                    display: false,
                                }
                            }]
                        },
                        legend: {
                            position: 'bottom'
                        },
                    },

                },
                CR: {
                    request: 'cr',
                    data: {
                        labels: [],
                        datasets: []
                    },
                    options: {
                        maintainAspectRatio: false,
                        responsive: true,
                        xAxes: [{
                            gridLines: {
                                display: false,
                            },
                            ticks: {
                                fontSize: 15,
                                fontColor: 'lightgrey'
                            }
                        }],
                        scales: {
                            xAxes: [{
                                gridLines: {
                                    drawBorder: false,
                                    display: false,
                                }
                            }],
                            yAxes: [{
                                gridLines: {
                                    drawBorder: false,
                                    display: false,
                                }
                            }]
                        },
                        legend: {
                            position: 'bottom'
                        },
                    },

                },
            }
        }
    },
    methods: {
        reload(startDate, endDate) {
            //refactor
            let apiUrl = null;

            if (['google', 'bing', 'gemini'].includes(this.platform)) {
                apiUrl = `/api${this.$route.path}/${this.platform}/chart`;
            } else {
                apiUrl = `/api${this.$route.path}/chart`;
            }

            for (const chart in this.charts) {
                this.$http.get(apiUrl, {
                    params: {
                        type: this.charts[chart].request,
                        startDate: startDate,
                        endDate: endDate
                    }
                }).then((response) => {
                    this.charts[chart].data.labels = response.data.labels;
                    this.charts[chart].data.datasets = response.data.datasets;
                });
            }
        }
    }
}
</script>

<style scoped>

</style>
