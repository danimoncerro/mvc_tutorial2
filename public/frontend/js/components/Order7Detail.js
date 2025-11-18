const Order7Detail = {
    name: 'Order7Detail',
    props: {
        order: {
            type: Object,
            default: null
        }
    },
    template: `

    

       

        <!-- Modal pentru afisare detalii comanda -->
        <div class="modal fade" id="order7DetailModal" tabindex="-1" aria-labelledby="orderDetailModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="orderDetailModalLabel">Detalii comanda</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    
                    <div class="modal-body">
                        <p v-if="!order"> Se incarca detaliile comenzii...</p>
                        <div v-else>
                            <strong>Valoarea comenzii: </strong>
                                <span> {{ order.total_order }} lei</span>  <br>
                            <strong>User: </strong>
                                <span> {{ order.user_email }} </span> <br>
                            <strong>Data comenzii: </strong>
                                <span> {{ order.created_at }} </span> <br>
                            <strong>Status: </strong>
                                <span> {{ order.status }} </span> <br>
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