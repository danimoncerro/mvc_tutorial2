const ProductCard = {
    name: 'ProductCard',
    emits: [''],
    template: `
        <h1>Card</h1>
        <div class="card-body">
            <h5 class="card-title">{{ product.name }}</h5>
            <p class="card-text mb-2">
                <strong>Preț:</strong> {{ product.price }} RON
            </p>
            <p class="card-text mb-2" v-if="product.category_name">
                <small class="text-muted">Categorie: {{ product.category_name }}</small>
            </p>
            <p class="card-text mb-1 text-danger" v-if="product.discount">
                <small>Discount: {{ product.discount }}%</small>
            </p>
            <p class="card-text text-danger" v-if="product.discount">
                <small>Preț cu discount: {{ product.price_discount }} RON</small>
            </p>
        </div>
    
    `,
    props: {
        product: {
            type: Object,
            required: true
        }
        
    
    
    },
    setup(props, { emit }) {
    
    
    
    },

}