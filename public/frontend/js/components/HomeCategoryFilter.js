const HomeCategoryFilter = {
    name: 'HomeCategoryFilter',
    emits: ['category-selected'],
    template: `
        <h1>Home Category Filter</h1>
        <button
            type="button"
            class="list-group-item list-group-item-action d-flex justify-content-between align-items-center bg-success text-white"
            :class="{ active: selectedCategory === 0 }"
            @click="setCategory(0)"
        >
            <span>Toate categoriile</span>
        </button>

        <div
            v-for="category in categories"
            :key="category.id">

            <button
                type="button"
                class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"
                
                :class="{ active: selectedCategory === Number(category.id) }"
                v-if="category.nr_product > 0"
                @click="setCategory(category.id)"
            >
                <span>{{ category.name }}</span>
                <span class="badge bg-secondary rounded-pill">{{ category.nr_product }}</span>
            </button>

        </div>
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
                console.log('ID categorie selectată:', categoryid);
                emit('category-selected', Number(categoryid));
                //selectedCategory.value = Number(categoryid);

            }
    
            return {
                setCategory
            }
    
    
    },

}