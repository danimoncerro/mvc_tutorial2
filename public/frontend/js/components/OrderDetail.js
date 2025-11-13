const OrderDetail = {
    name: 'OrderDetail',
    emits: [],
    template: `

        <div class="mb-3">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#orderDetailModal">
                <i class="bi bi-plus-circle"></i> Detalii comanda
            </button>
        </div>

       

        <!-- Modal pentru afisare detalii comanda -->
        <div class="modal fade" id="orderDetailModal" tabindex="-1" aria-labelledby="orderDetailModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="orderDetailModalLabel">Detalii comanda</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Anulează</button>
                        
                    </div>
                </div>
            </div>
        </div>


    `,
    props: {
        savelink: {
            type: String,
            required: true
        },
        categories: {
            type: Array,
            required: true
        }
        
    },
    setup(props, { emit }) {
       

    
    }
       
    
};