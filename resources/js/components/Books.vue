<template>
    <div class="row justify-content-center">
        <div class="col-md-3">
            <div class="row">
                <div class="col-md-12 d-flex mb-3">
                    <input
                        class="form-control me-2"
                        type="search"
                        placeholder="Search"
                        aria-label="Search"
                        v-model="searchText"
                    />
                    <button
                        class="btn btn-outline-success"
                        @click="handleSearch"
                    >
                        Search
                    </button>
                </div>
                <div class="col-md-12 mb-3">
                    <label for="author" class="form-label">Author</label>
                    <select
                        class="form-select"
                        id="author"
                        v-model="filter.author_id"
                        aria-label="select author"
                    >
                        <option value="">Select all author</option>
                        <option
                            v-for="author in authors"
                            :key="author.id"
                            :value="author.id"
                        >
                            {{ author.name }}
                        </option>
                    </select>
                </div>
                <div class="col-md-12 mb-3">
                    <label for="category" class="form-label">Category</label>
                    <select
                        class="form-select"
                        id="category"
                        v-model="filter.category_id"
                    >
                        <option value="">Select all category</option>
                        <option
                            v-for="category in categories"
                            :key="category.id"
                            :value="category.id"
                        >
                            {{ category.name }}
                        </option>
                    </select>
                </div>
                <div class="col-md-12">
                    <label for="publisher" class="form-label">Publisher</label>
                    <select
                        class="form-select"
                        id="publisher"
                        v-model="filter.publisher_id"
                    >
                        <option value="">Select all publisher</option>
                        <option
                            v-for="publisher in publishers"
                            :key="publisher.id"
                            :value="publisher.id"
                        >
                            {{ publisher.name }}
                        </option>
                    </select>
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <div class="row row-cols-1 row-cols-md-3 g-4">
                <div class="col" v-for="(book, index) in books" :key="book.id">
                    <div class="card" style="width: 20rem">
                        <img
                            :src="
                                book.image
                                    ? `/storage/images/${book.image}`
                                    : `https://picsum.photos/318?random=${
                                          index + 1
                                      }`
                            "
                            class="card-img-top"
                            alt="..."
                        />
                        <div class="card-body">
                            <h5 class="card-title">
                                <a :href="`/books/${book.id}`">{{
                                    book.title
                                }}</a>
                            </h5>
                            <p class="card-text text-truncate">
                                {{ book.description }}
                            </p>
                        </div>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">
                                Author: {{ book.author.name }}
                            </li>
                            <li class="list-group-item">
                                Category: {{ book.category.name }}
                            </li>
                            <li class="list-group-item">
                                Publisher: {{ book.publisher?.name || "---" }}
                            </li>
                            <li class="list-group-item">
                                Published: {{ book.published || "---" }}
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="row" v-if="loading">
                <div class="col-md-12">
                    <h2 class="mt-3 text-center text-secondary">Loading...</h2>
                </div>
            </div>
            <div class="row" v-else>
                <div class="col-md-12" v-if="books.length === 0">
                    <h2 class="mt-3 text-center text-secondary">
                        No data found!
                    </h2>
                </div>
                <div
                    class="col-md-12 text-center"
                    v-if="paginate.current_page !== paginate.last_page"
                >
                    <button
                        class="btn btn-primary mt-3"
                        @click="handleLoadMore"
                    >
                        Load More
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import "bootstrap";
import BookList from "./BookList.vue";

export default {
    components: { BookList },
    props: ["authors", "categories", "publishers"],
    data() {
        return {
            loading: true,
            paginate: { data: [] },
            books: [],
            searchText: "",
            filter: {
                search: "",
                author_id: "",
                category_id: "",
                publisher_id: "",
            },
        };
    },
    created() {
        this.fetchBooks();
    },
    mounted() {},
    watch: {
        filter: {
            handler: function () {
                this.books = [];
                this.fetchBooks();
            },
            deep: true,
        },
    },
    methods: {
        fetchBooks(page = 1) {
            const { search, author_id, category_id, publisher_id } =
                this.filter;

            this.loading = true;

            axios
                .get(
                    `/books/published?page=${page}&search=${search}&author_id=${author_id}&category_id=${category_id}&publisher_id=${publisher_id}`
                )
                .then((response) => {
                    this.paginate = response.data;
                    this.books =
                        page === 1
                            ? this.paginate.data
                            : this.books.concat(this.paginate.data);

                    this.$nextTick(() => (this.loading = false));
                });
        },
        handleLoadMore() {
            this.fetchBooks(this.paginate.current_page + 1);
        },
        handleSearch() {
            this.filter.search = this.searchText;
        },
    },
};
</script>
