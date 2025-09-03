const ShowCategoryTitle = {
    name: 'ShowCategoryTitle',
    template: `
        <h1>Categories 
            <span class="badge bg-secondary" v-if="categories && categories.length">{{ categories.length }}</span>
        </h1>
    `,
    props: {
        categories: {
            type: Array,
            default: () => []
        }
    },
    setup(props) {
        return {};
    }
};