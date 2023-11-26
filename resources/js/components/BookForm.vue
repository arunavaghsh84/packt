<template>
    <form class="row g-3 needs-validation" novalidate>
        <div class="col-md-12">
            <label for="title" class="form-label">Title</label>
            <input
                type="text"
                class="form-control"
                :class="{ 'is-invalid': errors.title }"
                id="title"
                placeholder="Title"
                v-model="formData.title"
            />
            <div v-if="errors.title" class="invalid-feedback">
                {{ errors.title[0] }}
            </div>
        </div>
        <div class="col-md-12">
            <label for="description" class="form-label">Description</label>
            <textarea
                class="form-control"
                :class="{ 'is-invalid': errors.description }"
                id="description"
                placeholder="Description"
                v-model="formData.description"
                required
            ></textarea>
            <div v-if="errors.description" class="invalid-feedback">
                {{ errors.description[0] }}
            </div>
        </div>
        <div class="col-md-6">
            <label for="author" class="form-label">Author</label>
            <select
                class="form-select"
                :class="{ 'is-invalid': errors.author_id }"
                id="author"
                v-model="formData.author_id"
                aria-label="select author"
            >
                <option value="">Select author</option>
                <option
                    v-for="author in authors"
                    :key="author.id"
                    :value="author.id"
                >
                    {{ author.name }}
                </option>
            </select>
            <div v-if="errors.author_id" class="invalid-feedback">
                {{ errors.author_id[0] }}
            </div>
        </div>
        <div class="col-md-6">
            <label for="category" class="form-label">Category</label>
            <select
                class="form-select"
                :class="{ 'is-invalid': errors.category_id }"
                id="category"
                v-model="formData.category_id"
            >
                <option value="">Select category</option>
                <option
                    v-for="category in categories"
                    :key="category.id"
                    :value="category.id"
                >
                    {{ category.name }}
                </option>
            </select>
            <div v-if="errors.category_id" class="invalid-feedback">
                {{ errors.category_id[0] }}
            </div>
        </div>
        <div class="col-md-6">
            <label for="publisher" class="form-label">Publisher</label>
            <select
                class="form-select"
                :class="{ 'is-invalid': errors.publisher_id }"
                id="publisher"
                v-model="formData.publisher_id"
            >
                <option value="">Select publisher</option>
                <option
                    v-for="publisher in publishers"
                    :key="publisher.id"
                    :value="publisher.id"
                >
                    {{ publisher.name }}
                </option>
            </select>
            <div v-if="errors.publisher_id" class="invalid-feedback">
                {{ errors.publisher_id[0] }}
            </div>
        </div>
        <div class="col-md-6">
            <label for="publisher" class="form-label">Published on</label>
            <VueDatePicker
                v-model="formData.published"
                model-type="yyyy-MM-dd"
                :class="{ 'is-invalid': errors.published }"
                :enable-time-picker="false"
                :format="format"
                :preview-format="format"
                auto-apply
            ></VueDatePicker>
            <div v-if="errors.published" class="invalid-feedback">
                {{ errors.published[0] }}
            </div>
        </div>
        <div class="col-md-3" v-if="book?.image">
            <label for="validationImage" class="form-label"
                >Uploaded Image</label
            >
            <img :src="`/storage/images/${book.image}`" class="w-100" />
        </div>
        <div class="col-md-12">
            <label for="validationImage" class="form-label">Image</label>
            <input
                type="file"
                class="form-control"
                :class="{ 'is-invalid': errors.image }"
                aria-label="file example"
                @change="onFileChange"
                required
            />
            <div v-if="errors.image" class="invalid-feedback">
                {{ errors.image[0] }}
            </div>
        </div>
        <div class="col-md-12">
            <button
                type="button"
                class="btn btn-primary float-start"
                @click="submitForm"
            >
                {{ !book ? "Add" : "Edit" }}
            </button>
        </div>
    </form>
</template>

<script>
import VueDatePicker from "@vuepic/vue-datepicker";
import "@vuepic/vue-datepicker/dist/main.css";

export default {
    components: { VueDatePicker },
    props: ["authors", "categories", "publishers", "book"],
    data() {
        return {
            formData: { ...this.book },
            errors: {},
        };
    },
    methods: {
        format(date) {
            const day = date.getDate();
            const month = date.getMonth() + 1;
            const year = date.getFullYear();

            return `${day}/${month}/${year}`;
        },
        onFileChange(e) {
            this.formData.image = e.target.files[0];
        },
        async submitForm() {
            let formData = new FormData();

            for (let key in this.formData) {
                formData.append(key, this.formData[key] || "");
            }

            try {
                const headers = {
                    "Content-Type": "multipart/form-data",
                };

                if (this.book?.id) {
                    await axios.post(`/books/${this.book.id}`, formData, {
                        headers,
                    });
                } else {
                    await axios.post("/books", formData, {
                        headers,
                    });
                }

                window.location.href = "/books";
            } catch (error) {
                this.errors = error.response.data.errors;
            }
        },
    },
    created() {
        if (!this.formData) {
            this.formData = {
                id: null,
                title: "",
                author_id: "",
                category_id: "",
                description: "",
                image: null,
                published: null,
                publisher_id: "",
            };
        } else {
            this.formData.image = null;
        }
    },
};
</script>
