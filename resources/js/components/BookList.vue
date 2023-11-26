<template>
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-2 mb-3">
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
                <div class="col-md-2 mb-3">
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
                <div class="col-md-2">
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
                <div class="col-md-6 d-flex mb-3 gap-2">
                    <input
                        class="form-control"
                        type="search"
                        placeholder="Search"
                        aria-label="Search"
                        v-model="searchText"
                    />
                    <button
                        class="btn btn-outline-success w-25"
                        @click="handleSearch"
                    >
                        Search
                    </button>
                    <button
                        class="btn btn-outline-danger w-25"
                        @click="resetFilter"
                    >
                        Reset all
                    </button>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    Book List
                    <a
                        href="/books/create"
                        class="btn btn-sm btn-primary float-end"
                    >
                        Add Book
                    </a>
                </div>
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Author</th>
                                <th>Genre</th>
                                <th>Description</th>
                                <th>Published</th>
                                <th>Publisher</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="book in books.data" :key="book.id">
                                <td>{{ book.title }}</td>
                                <td>{{ book.author.name }}</td>
                                <td>{{ book.category.name }}</td>
                                <td>{{ book.description }}</td>
                                <td>{{ book.published }}</td>
                                <td>{{ book.publisher?.name }}</td>
                                <td>
                                    <a :href="`/books/${book.id}/edit`">Edit</a>
                                    <a
                                        href="#"
                                        @click.prevent="handleDelete(book.id)"
                                        class="ms-2"
                                        >Delete</a
                                    >
                                </td>
                            </tr>
                            <tr v-if="books.data.length === 0">
                                <td colspan="7" align="center">
                                    No data found!
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <nav aria-label="Page navigation example">
                        <ul class="pagination">
                            <li
                                class="page-item"
                                :class="
                                    books.current_page === 1 ? 'disabled' : ''
                                "
                            >
                                <a
                                    @click.prevent="
                                        changePage(books.current_page - 1)
                                    "
                                    class="page-link"
                                    href="#"
                                    >Previous</a
                                >
                            </li>
                            <li
                                v-for="pageNo in books.last_page"
                                :key="pageNo"
                                class="page-item"
                            >
                                <a
                                    @click.prevent="changePage(pageNo)"
                                    class="page-link"
                                    :class="
                                        books.current_page === pageNo
                                            ? 'active'
                                            : ''
                                    "
                                    href="#"
                                    >{{ pageNo }}</a
                                >
                            </li>
                            <li
                                class="page-item"
                                :class="
                                    books.current_page === books.last_page
                                        ? 'disabled'
                                        : ''
                                "
                            >
                                <a
                                    @click.prevent="
                                        changePage(books.current_page + 1)
                                    "
                                    class="page-link"
                                    href="#"
                                    >Next</a
                                >
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: ["authors", "categories", "publishers"],
    data() {
        return {
            books: { data: [] },
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
                this.fetchBooks();
            },
            deep: true,
        },
    },
    methods: {
        fetchBooks(page = 1) {
            const { search, author_id, category_id, publisher_id } =
                this.filter;

            axios
                .get(
                    `/books/all?page=${page}&search=${search}&author_id=${author_id}&category_id=${category_id}&publisher_id=${publisher_id}`
                )
                .then((response) => {
                    this.books = response.data;
                });
        },
        changePage(page) {
            this.fetchBooks(page);
        },
        handleSearch() {
            this.filter.search = this.searchText;
        },
        resetFilter() {
            this.searchText = "";

            this.filter = {
                search: "",
                author_id: "",
                category_id: "",
                publisher_id: "",
            };
        },
        handleDelete(bookId) {
            if (confirm("Do you really want to delete this book?")) {
                axios.delete(`/books/${bookId}`).then(() => {
                    window.location.reload();
                });
            }
        },
    },
};
</script>
