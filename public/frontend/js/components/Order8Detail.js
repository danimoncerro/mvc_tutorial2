const Order8Detail = {
    name: 'Order4Detail',
    props: {
        order8: {
            type: Object,
            default: null
        },
        orderDetails8: {
            type: [Object, Array],
            default: null
        },

    },
    template: `

    

       

        <!-- Modal pentru afisare detalii comanda -->
        <div class="modal fade" id="order12DetailModal" tabindex="-1" aria-labelledby="orderDetailModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="orderDetailModalLabel">Detalii comanda</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    
                    <div class="modal-body">
                        <p v-if="!order8"> Se incarca detaliile comenzii8...</p>
                        <div v-else>
                           
                            <strong>Valoarea comenzii8: </strong>
                                <span> {{ order8.total_order }} lei</span>  <br>
                            <strong>User: </strong>
                                <span> {{ order8.user_email }} </span> <br>
                            <strong>Data comenzii: </strong>
                                <span> {{ order8.created_at }} </span> <br>
                            <strong>Status: </strong>
                                <span> {{ order8.status }} </span> <br>

                                
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

        return {
        };
        
    }
       
    
};