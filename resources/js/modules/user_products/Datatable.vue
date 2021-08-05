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
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(row, index) in entities.data">
    
                    <td v-for="(column, index) in columns">
                        <router-link :to="{ path: row[column.name] }" v-if="(typeof column.link !== 'undefined')">
                            Edit
                        </router-link>
                        <span v-else>{{ row[column.name ]}} </span>
                    </td>
                    <td>
                        <button @click="$emit('editProduct', row)" type="button" class="btn btn-success btn-sm">edit</button>
                        <button @click="$emit('destroyProduct', row)" type="button" class="btn btn-danger btn-sm ms-2">delete</button>
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
        //columns: Array,
        //url: String,
        //sortedColumn: String
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
            sorted_column: "created_at",
            offset: 0,
            order: 'desc',
            columns: [
                { name: "name", label: "Name" },
                { name: "status", label: "Status" },
                { name: "created_at", label: "Created" },
            ],
            url: "/user/products",
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
        },
       
        
    }
}
</script>