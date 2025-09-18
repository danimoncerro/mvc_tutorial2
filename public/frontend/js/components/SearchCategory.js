const SearchCategory = {
    name: 'SearchCategory',
    emits: ['search-categories'],
    template: `

    <div class="mb-3">
        <div class="col-md-3">
                <input v-model="search" type="text" class="form-control" placeholder="Caută categorie   ...">
        </div>
    </div>
    `,
    props: {
        
        
    },
    setup(props, { emit }) {
        const { watch, ref } = Vue;

        const search = ref('');

        watch(search, (newValue) => {
            emit('search-categories', newValue);
        });

        

        return {
            search,
        };
    }
};