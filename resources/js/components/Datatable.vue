<template>
    <div class="data-table">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th v-for="(column, index) in columns" @click="sortByColumn(column)" class="p-2">
                        <div class="d-flex justify-content-between align-self-center">
                            <span>{{ column.name }}</span>
                            <span>
                                        <font-awesome-icon icon="arrow-down" 
                                        v-if="(typeof column.orderable === 'undefined' || column.orderable === true) && column.name === this.sorted_column && this.order === 'desc'" />
                                        <font-awesome-icon icon="arrow-up" 
                                        v-if="(typeof column.orderable === 'undefined' || column.orderable === true) && column.name === this.sorted_column && this.order === 'asc'" />
                                    </span>
                        </div>
                    </th>
                    <th v-if="actionButtons.length > 0 || links.length > 0"></th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(row, index) in entities.data">
    
                    <td v-for="(column, index) in columns">
                        <span>{{ row[column.name ]}} </span>
                    </td>
                    <td v-if="links.length > 0" class="d-flex justify-content-around">
    
                        <router-link v-for="link in links" :to="{ path: link.uri+row.id }">
                            
                            <button type="button" :class="link.class">
                            {{link.text}}
                            <font-awesome-icon :icon="link.icon" v-if="link.icon"/>
                            </button>
                        </router-link>
    
                    </td>
                    <td v-if="actionButtons.length > 0" class="d-flex justify-content-around">
    
                        <button v-if="actionButtons.includes('edit')" @click="$emit('edit', row)" type="button" class="btn btn-success btn-sm">
                                <font-awesome-icon icon="edit" />
                            </button>
    
                        <button v-if="actionButtons.includes('destroy')" @click="$emit('destroy', row)" type="button" class="btn btn-danger btn-sm">
                                <font-awesome-icon icon="trash" />
                            </button>
                    </td>
                </tr>
            </tbody>
        </table>
        <nav v-if="entities.data && entities.data.length > 0">
            <ul class="pagination">
                <li class="page-item">
                    <button class="page-link" :disabled="1 === entities.meta.current_page" @click="changePage(entities.meta.current_page - 1)">
                                                                                    Previous
                                                                                </button>
                </li>
                <li v-for="page in pageNumbers" class="page-item" :class="{ 'active': (page === entities.meta.current_page)}" :key="page">
                    <button class="page-link" @click="changePage(page)">{{page}}</button>
                </li>
    
                <li class="page-item">
                    <button class="page-link" :disabled="entities.meta.last_page === entities.meta.current_page" @click="changePage(entities.meta.current_page + 1)">
                                                                                    Next
                                                                                </button>
                </li>
                <span class="pt-5"> &nbsp; <i>Displaying {{entities.data.length}} of {{entities.meta.total}} entries.</i></span>
            </ul>
        </nav>
    </div>
</template>

<script>
export default {
    name: 'Datatable',
    props: {
        columns: Array,
        url: String,
        sortedColumn: String,
        actionButtons: {
            type: Array,
            default: [],
            validator(value) {
                // The value must match one of these strings
                return ['edit', 'destroy'].includes(value)
            },
        },
        links: {
            type: Array,
            default: [],
        }
    },
    mounted() {
        this.current_page = this.entities.meta.current_page;
        this.fetchEntities();
    },
    data() {
        return {
            entities: {
                data: [],
                meta: {
                    current_page: 1,
                    from: 1,
                    last_page: 1,
                    per_page: 5,
                    to: 1,
                    total: 1,
                },
            },
            first_page: 1,
            current_page: 1,
            sorted_column: this.sortedColumn,
            offset: 0,
            order: 'desc'
        }
    },
    computed: {
        // a computed getter
        pageNumbers() {
            if (!this.entities.meta.to) {
                return [];
            }

            let from = 1;
            if (this.offset > 0) {
                from = this.entities.meta.current_page - this.offset;
            }

            if (from < 1) {
                from = 1;
            }

            let to = this.entities.meta.last_page;

            if (this.offset > 0) {
                to = from + (this.offset * 2);
            }

            if (to >= this.entities.meta.last_page) {
                to = this.entities.meta.last_page;
            }

            let pagesArray = [];
            for (let page = from; page <= to; page++) {
                pagesArray.push(page);
            }
            return pagesArray;
        }
    },
    methods: {
        fetchEntities() {
            let fetchUrl = `${this.url}/?page=${this.current_page}&column=${this.sorted_column}&order=${this.order}&per_page=${this.entities.meta.per_page}`;
            axios.get(fetchUrl)
                .then(response => {
                    this.entities = response.data;
                })
                .catch(e => {
                    console.error(e);
                });
        },
        sortByColumn(column) {
            if (typeof column.orderable != 'undefined' && column.orderable === false) {
                return false;
            }
            if (column.name === this.sorted_column) {
                if (this.order === 'asc') {
                    this.order = 'desc';
                    this.current_page = this.first_page;
                } else {
                    this.order = 'asc';
                }
            } else {
                this.sorted_column = column.name;
                this.order = 'asc';
                this.current_page = this.first_page;
            }
            this.fetchEntities()
        },
        changePage(pageNumber) {
            this.current_page = pageNumber;
            this.fetchEntities();
        }
    }
}
</script>