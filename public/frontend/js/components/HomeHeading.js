const HomeHeading = {
    name: 'HomeHeading',
    emits: [''],
    template: `
        <h1 class="mb-0">{{ title }}</h1>
        <span class="badge bg-primary fs-6">Total produse: {{ totalproducts }}</span>
    
    `,
    props: {
        title: {
            type: String,
            required: true
        },
        totalproducts: {
            type: Number,
            required: true

        }
    
    
    },
    setup(props, { emit }) {
    
    
    
    },

}