<template>
    <div>

        <b-card-body style="background: rgb(108, 110, 250);" class="p-2">
            <b-breadcrumb :items="breadcrumbs" class="mb-0"></b-breadcrumb>
        </b-card-body>
        <b-row class="d-flex justify-content-end mb-2 mt-2">
            <b-col lg="2">
                <b-form-datepicker
                    v-model="filter.startDate"
                    placeholder="Start Date"
                    :date-format-options="{ year: 'numeric', month: 'numeric', day: 'numeric' }"
                />
            </b-col>
            <b-col lg="2">
                <b-form-datepicker
                    v-model="filter.endDate"
                    placeholder="End Date"
                    :date-format-options="{ year: 'numeric', month: 'numeric', day: 'numeric' }"
                />
            </b-col>
        </b-row>
        <default-charts
            ref="charts"
            :api-url="`/api${this.$route.path}/chart`"
            :date-range="{start: filter.startDate, end:filter.endDate}"
        />
        <b-card>
            <b-row class="d-flex justify-content-between mb-2">
                <b-col lg="2">
                    <b-dropdown
                        id="dropdown-1"
                        text="Columns"
                        size="small"
                        ref="dropdown"
                        variant="outline-primary"
                    >

                        <b-dropdown-item-button
                            v-for="column in fields"
                            v-bind:key="column.label"
                            :variant="column.visible === true ? 'success' : 'warning'"
                            @click="fields.find(({key}) => key === column.key).visible = !fields.find(({key}) => key === column.key).visible;$refs.dropdown.show(true)"

                        >{{ column.label }}
                        </b-dropdown-item-button>
                    </b-dropdown>
                </b-col>
                <b-col lg="3">
                    <b-input-group size="sm">
                        <b-form-input
                            id="filter-input"
                            v-model="filter.name"
                            type="search"
                            placeholder="Search by name"
                        ></b-form-input>
                    </b-input-group>
                </b-col>
            </b-row>
            <hr>


            <b-table
                :api-url="`/api${$route.path}`"
                :items="getData"
                :fields="fieldsToShow"
                :show-empty="true"
                :striped="true"
                :bordered="true"
                :outlined="true"
                :filter="filter"
                :current-page="currentPage"
                id="clients-table"
                label-sort-desc=""
                label-sort-asc=""
                @update:busy="swapTrTh($event)"
            >
                <template #thead-top="data">

                    <b-tr>
                        <template v-for="field in data.fields">
                            <template v-if="field.key === 'pl'">
                                <b-th v-if="total[field.key] >0" class="table-success">
                                    {{ total[field.key] }}
                                </b-th>
                                <b-th v-else-if="total[field.key] < -50" class="table-danger">
                                    {{ total[field.key] }}
                                </b-th>
                                <b-th v-else-if="total[field.key] > -50 && total[field.key] < 0"
                                      class="table-warning">
                                    {{ total[field.key] }}
                                </b-th>
                            </template>
                            <template v-else>
                                <b-th>
                                    {{
                                        total.hasOwnProperty(field.key) ? Number(total[field.key]).toLocaleString(undefined,
                                            {
                                                minimumFractionDigits: 2,
                                                maximumFractionDigits: 2
                                            }) : ''
                                    }}
                                </b-th>
                            </template>
                        </template>
                    </b-tr>
                </template>
                <template #table-busy class="d-flex justify-content-around text-center">
                    <b-spinner></b-spinner>
                </template>

                <template #cell(name)="data">

                    <router-link
                        :to="{
                        name: 'Client Single',
                        params: {
                            adPlatform: $route.params.adPlatform, client: data.item.id
                        },
                        query: {
                            startDate: filter.startDate,
                            endDate: filter.endDate,
                        }}">
                        {{ data.item.name }}
                    </router-link>
                </template>
            </b-table>
            <b-pagination
                v-model="currentPage"
                :total-rows="rows"
                :per-page="perPage"
                aria-controls="clients-table"
            ></b-pagination>
        </b-card>
    </div>
</template>

<script>

import defaultCharts from '../charts/defaultCharts';
import defaultMixin from "../mixins/defaultMixin";
import moment from "moment";

export default {
    name: "index",
    components: {defaultCharts},
    mixins: [defaultMixin],
    data() {
        return {
            fields: [
                {
                    key: 'name',
                    label: 'Account',
                    visible: true,
                    sortable: true

                },
                {
                    key: 'clicks',
                    label: 'Clicks',
                    visible: false,
                    sortable: true
                },
                {
                    key: 'impressions',
                    label: 'Impressions',
                    visible: false,
                    sortable: true
                },
                {
                    key: 'cost',
                    label: 'Spent',
                    visible: true,
                    sortable: true,
                    formatter(value, key, item) {
                        return `$${Number(value).toLocaleString(undefined, {
                            minimumFractionDigits: 2,
                            maximumFractionDigits: 2
                        })}`
                    }
                },
                {
                    key: 'earned',
                    label: 'Income',
                    visible: true,
                    sortable: true,
                    formatter(value, key, item) {
                        return `$${Number(value).toLocaleString(undefined, {
                            minimumFractionDigits: 2,
                            maximumFractionDigits: 2
                        })}`
                    }
                },
                {
                    key: 'actual_cpa',
                    label: 'Actual CPA',
                    visible: false,
                    sortable: true
                },
                {
                    key: 'actual_cps',
                    label: 'Actual CPS',
                    visible: false,
                    sortable: true
                },
                {
                    key: 'avg_cpc',
                    label: 'Avg CPC',
                    visible: false,
                    sortable: true
                },
                {
                    key: 'pl',
                    label: 'P/L',
                    visible: true,
                    sortable: true,
                    tdClass(value, key, item) {
                        const number = parseFloat(value);

                        if (number < -50) {
                            return 'table-danger';
                        } else if (number >= -50 && number <= 0) {
                            return 'table-warning';
                        } else {
                            return 'table-success';
                        }
                    }
                },
                {
                    key: 'avg_position',
                    label: 'Avg Position',
                    visible: true,
                    sortable: true
                },
                {
                    key: 'ur',
                    label: 'UR',
                    visible: true,
                    sortable: true
                },
                {
                    key: 'roi',
                    label: '%ROI',
                    visible: true,
                    sortable: true,
                    formatter(value, key, item) {
                        return `%${value}`
                    }
                },
                {
                    key: 'actual_epa',
                    label: 'Actual EPA',
                    visible: false,
                    sortable: true
                },
                {
                    key: 'actual_eps',
                    label: 'Actual EPS',
                    visible: false,
                    sortable: true
                },
                {
                    key: 'cr',
                    label: 'CR',
                    visible: true,
                    sortable: true
                },
                {
                    key: 'ctr',
                    label: 'CTR',
                    visible: true,
                    sortable: true
                },
                {
                    key: 'profiles',
                    label: 'Profiles',
                    visible: true,
                    sortable: true
                },
                {
                    key: 'upgrades',
                    label: 'Upgrades',
                    visible: true,
                    sortable: true
                },
            ],
            breadcrumbs: [
                {
                    text: 'Platforms',
                    href: '/adPlatforms'
                },
                {
                    text: this.$route.params.adPlatform[0].toLocaleUpperCase() + this.$route.params.adPlatform.slice(1)

                }
            ],
        }
    },
    methods: {
        async getData(ctx) {

            try {
                const response = await this.$http.get(`${ctx.apiUrl}`, {
                    params: {
                        page: ctx.currentPage,
                        perPage: ctx.perPage,
                        name: ctx.filter.name,
                        startDate: ctx.filter.startDate,
                        endDate: ctx.filter.endDate,
                        sortBy: ctx.sortBy,
                        sortDesc: ctx.sortDesc
                    }
                });
                this.total = response.data.data.total;
                this.rows = response.data.data.pagination.total;
                return response.data.data.pagination.data;
            } catch (error) {
                return []
            }

        }
    }

}


</script>

<style scoped>

</style>
