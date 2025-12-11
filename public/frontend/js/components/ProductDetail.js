const ProductDetail = {
    name: 'ProductDetail',
    props: {
        product: {
            type: Object,
            default: null
        }

    },
    template: `   

        <!-- Modal pentru afisare detalii produs -->
        <div class="modal fade" id="productDetail" tabindex="-1" aria-labelledby="productDetailModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="productDetailModalLabel">Detalii produs</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                
                        <p v-if="!product"> Se incarca detaliile produsului...</p>
                        <div v-else>
                            <strong>Product id: </strong>
                                <span> {{ product.id }} </span>  <br>
                            <strong>Product name: </strong>
                                <span> {{ product.name }} </span> <br>
                            <strong>Product price: </strong>
                                <span> {{ product.price }} lei </span> <br>
                        </div>

                    </div>
                    
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Inchide</button>
                        
                    </div>
                </div>
            </div>
        </div>


    `,

    setup(props) {
         
    }
       
    
};