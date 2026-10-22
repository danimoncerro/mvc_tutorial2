const RemoveFromCart = {
    name: 'StergeProdus',
    emits: ['arata-produse'],
    template: `

        <button class="btn btn-danger btn-sm" @click="removeFromCart(id_product)">Șterge</button>
    
    `,
    props: {
        id_product:{
            type: String
        },
        deletelink: {
            type: String,
            required: true
        }
    
    },
    setup(props, { emit }) {
    
        const removeFromCart = (productId) => {
                if (!productId) return;
                axios.post(props.deletelink, { product_id: productId })
                    .then(res => {
                        if (res.data.success) {
                          emit('arata-produse');
                        } 
                        else {
                            alert('Eroare la ștergere: ' + (res.data.message || ''));
                        }
                    })
                    .catch(() => alert('Eroare la ștergerea produsului!'));

                
            };

            return {
                removeFromCart
            }
    
    },

}