const Order7Detail = {
    name: 'Order7Detail',
    props: {
        order: {
            type: Object,
            default: null
        },
        detaillink: {
            type: String
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

                        <button type="button" clas="btn btn-secondary" @click="fetchOrderItems"> FetchOrderItems </button>
                        <table>
                            <tr>
                                <th>Denumire produs</th>
                                <th>Pret</th>
                                <th>Cantitate</th>
                            </tr>
                            
                            <tr v-for="orderItem in orderItems" :key="orderItem.id">
                                <td> {{orderItem.product_name}} </td>
                                <td> {{orderItem.product_price_db}} </td>
                                <td> {{orderItem.qty}} </td>
                            </tr>
                        </table>

                    </div>
                    
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Inchide</button>
                        
                    </div>
                </div>
            </div>
        </div>


    `,

    setup(props) {
       
        const {ref, onMounted} = Vue;

        orderItems = ref([]);

        const fetchOrderItems = () => { 

            //structura pentru axios - sa nu uiti niciodata
            console.log("Orice")
            axios.get(props.detaillink).then(response => {
                //console.log(response.data)
                orderItems.value = response.data[0]        
            })
                
        }

        return {
            fetchOrderItems,
            orderItems
        }
        
    
    }
       
    
};