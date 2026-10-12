const HomeCategoryFilter = {
    name: 'HomeCategoryFilter',
    emits: [''],
    template: `
        <h1>Home Category Filter</h1>
        <button
            type="button"
            class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"
            :class="{ active: selectedCategory === 0 }"
            @click="setCategory(0)"
        >
            <span>Toate categoriile</span>
        </button>

        <button
            type="button"
            class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"
            v-for="category in categories"
            :key="category.id"
            :class="{ active: selectedCategory === Number(category.id) }"
            @click="setCategory(category.id)"
        >
            <span>{{ category.name }}</span>
            <span class="badge bg-secondary rounded-pill">{{ category.nr_product }}</span>
        </button>
    
    `,
    props: {

        categories: {
            type: Array,
            required: true
        },

        selectedCategory: {
            type: Number
        }
    
    
    },
    setup(props, { emit }) {

            const setCategory = (categoryid) => {
                selectedCategory.value = Number(categoryid);

            }
    
            return {
                setCategory
            }
    
    
    },

}