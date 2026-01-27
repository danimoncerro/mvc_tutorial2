const ShowCategoryTitle = {
    name: 'ShowCategoryTitle',
    template: `
        <h1>Categories 
            <span class="badge bg-secondary">{{ total }}</span>
        </h1>
    `,
    props: {
        total: {
            type: Number,
            default: 0
        }
    },
    setup(props) {
        return {};
    }
};