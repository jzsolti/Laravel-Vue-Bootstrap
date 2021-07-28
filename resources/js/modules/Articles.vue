<template>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Articles</div>
    
                    <div class="card-body">
    
                        <form method="POST" @submit.prevent="onSubmit">
    
                            <div class="form-group">
                                <label for='search'>Search</label>
                                <input v-model.trim="form.search" type="text" class="form-control" name="search" id="search" />
                            </div>
    
                            <ul class="list-inline">
                                <li class="list-inline-item p-1" :key="label.id" v-for="label in labels">
    
                                    <div class="form-check">
                                        <input class="form-check-input" name="labels" type="checkbox" :value="label.id" v-model="form.labels" :id="`label-${label.id}`" />
                                        <label class="form-check-label" :for="`label-${label.id}`">
                                    {{ label.name }}
                                </label>
                                    </div>
                                </li>
                            </ul>
    
                            <div class="d-flex justify-content-between">
                                <button type="submit" class="btn btn-primary">
                        Apply
                    </button>
    
                            </div>
    
                        </form>
    
                        <Loading size="3" v-if="!loaded" />
    
                        <ul class="list-group mt-2">
    
    
                            <li class="list-group-item" :key="article.id" v-for="article in entities.data">
                                <div class="row">
                                    <div class="col-md-3">
                                        <img :src="article.image_src" class="img-thumbnail" />
                                    </div>
                                    <div class="col-md-9">
                                        <div class="d-flex justify-content-center">
                                            <h2>{{article.title}}</h2>
                                        </div>
                                        <div class="d-flex justify-content-start">
                                            {{article.lead}}
                                        </div>
                                        <router-link :to="{ path: article.detail }">
                                            Details
                                        </router-link>
                                    </div>
                                </div>
                            </li>
    
    
                        </ul>
                    </div>
                    <div class="card-footer">
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
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Loading from '../components/Loading.vue';
import qs from 'qs';
export default {
    components: {
        Loading
    },
    data() {
        return {
            loaded: false,
            form: {
                search: '',
                labels: []
            },
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
            offset: 4,
        }
    },
    mounted() {

        axios.get(`labels`)
            .then((response) => {
                this.labels = response.data.data;
            })
            .then(() => {
                this.current_page = this.entities.meta.current_page
                this.fetchEntities()
            })
            .catch((error) => {
                console.error(error);
            });
    },
    methods: {
        onSubmit() {
            this.changePage(1);
        },
        fetchEntities() {
            let fetchUrl = `articles?page=${this.current_page}&per_page=${this.entities.meta.per_page}`;

            if (this.form.search !== '') {
                fetchUrl += `&search=${this.form.search}`;
            }

            if (this.form.labels.length > 0) {
                fetchUrl += `&${ qs.stringify({ 'labels': this.form.labels }, { arrayFormat: 'indices', encode: false }) }`;
            }

            axios.get(fetchUrl)
                .then(response => {
                    this.entities = response.data;
                    this.loaded = true;
                })
                .catch(e => {
                    console.error(e);
                });
        },
        changePage(pageNumber) {
            this.current_page = pageNumber;
            this.fetchEntities();
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
}
</script>