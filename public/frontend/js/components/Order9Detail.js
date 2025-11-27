const Order9Detail = {
    name: 'Order9Detail',
    props: {
        order9: {
            type: Object,
            default: null
        },
        orderDetails9: {
            type: [Object, Array],
            default: null
        },

        orderItems9: {
            type: Array,
            default: () => []
        },

    },

    template: `

    

       

        <!-- Modal pentru afisare detalii comanda -->
        <div class="modal fade" id="order9DetailModal" tabindex="-1" aria-labelledby="orderDetailModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="orderDetailModalLabel">Detalii 9 comanda</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    
                    <div class="modal-body">
                        <p v-if="!order9"> Se incarca detaliile comenzii 9...</p>
                        <div v-else>
                           
                            <strong>Valoarea comenzii9: </strong>
                                <span> {{ order9.total_order }} lei</span>  <br>
                            <strong>User9: </strong>
                                <span> {{ order9.user_email }} </span> <br>
                            <strong>Data comenzii9: </strong>
                                <span> {{ order9.created_at }} </span> <br>
                            <strong>Status: </strong>
                                <span> {{ order9.status }} </span> <br>


                            <table>
                            <tr>
                                <th>Denumire produs</th>
                                <th>Pret</th>
                                <th>Cantitate</th>
                            </tr>

                            <tr v-for="item in orderItems9" :key="item.id">
                                <td> {{item.product_name}} </td>
                                <td> {{item.product_price_db}} </td>
                                <td> {{item.qty}} </td>
                            </tr>
                            
                            
                            </table>

                                
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

        console.log('Props primite în Order9Detail:', props);
        console.log('orderItems9:', props.orderItems9);
        return {
        };
        
    }
}