<?php
$title = 'Products List';
ob_start();
?>

<div id="app" class="container">
    <h1 class="my-4">Coș de cumpărături <span class="badge bg-secondary" v-if="cart.length">{{ totalcartitems }}</span></h1>

    <div v-if="cart.length > 0">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Produs</th>
                    <th>Preț</th>
                    <th>Cantitate</th>
                    <th>Subtotal</th>
                    <th>Elimina din coș</th>
            </tr>
            </thead>
            <tbody>
                <tr v-for="item in cart" :key="item.product_id">
                    <td>{{ item.product_name }}</td>
                    <td>{{ item.product_price }} RON</td>

                    <!-- Cantitate: afisare sau editare -->
                    <td v-if="editingQtyId !== item.product_id" class="qty-display" @click="startEditQty(item)" title="Editează cantitatea">
                        {{ item.quantity }}
                        <i class="bi bi-pencil-square ms-1 text-muted"></i>
                    </td>
                    <td v-else class="editing-qty">
                        <input type="number"
                               min="1"
                               class="form-control form-control-sm"
                               v-model.number="editingQty"
                               @keyup.enter="saveQty(item)"
                               @keyup.esc="cancelEditQty"
                               @blur="saveQty(item)" />
                    </td>

                    <td>{{ (item.product_price * item.quantity).toFixed(2) }} RON</td>
                    <td>
                        <button class="btn btn-danger btn-sm" @click="removeFromCart(item.product_id)">Șterge</button>
                    </td>
                </tr>
            </tbody>
        </table>

        <h2>Selecteaza adresa de livrare si cea de facturare</h2>
        <select>
            <option value="">Selecteaza adresa de livrare</option>
            <option v-for="address in addresses" :key="address.id">
                {{address.address}}, {{address.city}}, {{address.county}}
            </option>
        </select>
        <br>
        <select>
            <option value="">Selecteaza adresa de facturare</option>
            <option v-for="address in billingAddresses" :key="address.id">
                {{address.address}}, {{address.zip_code}}, {{address.city}}, {{address.county}}
            </option>
        </select>



        <button class="btn btn-success mb-3" @click="createOrder" >Plasează comanda</button><br>

     

        <br><br><br><hr><a href="/shipping">Gestioneaza adresa de livrare</a>
        <br><a href="/billing">Gestioneaza adresa de facturare</a>



        <div class="text-end">
            <h4>Total: {{ totalCart }} RON</h4>
        </div>
    </div>
    <!-- Mesaj dacă coșul este gol -->
    <div v-else class="text-center">
        <p class="text-muted">Coșul de cumpărături este gol.</p>
        <a href="<?= BASE_URL ?>" class="btn btn-primary">Continuă cumpărăturile</a>
    </div>


</div>


<!-- aici incepe partea de Vue.json_decode -->

<script>
    const { createApp, ref, computed, onMounted, reactive } = Vue;

    const app = createApp({
        setup() {
            const cart = ref([]);
            const editingQtyId = ref(null);
            const editingQty = ref(1);
            const addresses = ref([]);
            const billingAddresses = ref([]);
            const currentUserId = <?= isset($_SESSION['user']['id']) ? $_SESSION['user']['id'] : 'null' ?>;


            const getAddresses = () => {

                const params = {
                    user_id: currentUserId,
                };

                axios.get('<?= BASE_URL ?>api/shipping?user_id=' + currentUserId)
                    .then(response => {
                        addresses.value = response.data
                        console.log('Shipping address:', addresses.value);
                    })
            };

            const getBillingAddresses = () => {

                const params = {
                    user_id: currentUserId,
                };

                axios.get('<?= BASE_URL ?>api/billing?user_id=' + currentUserId)
                    .then(response => {
                        billingAddresses.value = response.data
                        console.log('Shipping address:', billingAddresses.value);
                    })
            }

            const getCart = () => {
                axios.get('<?= BASE_URL ?>api/cart').then(response => {
                    const cartArray = [];
                    for (const [, item] of Object.entries(response.data)) {
                        cartArray.push({
                            id: item.product_id,
                            product_id: item.product_id,
                            product_name: item.product_name,
                            product_price: item.product_price,
                            quantity: item.quantity
                        });
                    }
                    cart.value = cartArray;
                });
            };

            const startEditQty = (item) => {
                editingQtyId.value = item.product_id;
                editingQty.value = item.quantity;
            };

            const cancelEditQty = () => {
                editingQtyId.value = null;
                editingQty.value = 1;
            };

            const saveQty = (item) => {
                const qty = parseInt(editingQty.value, 10);
                if (!Number.isInteger(qty) || qty < 1) {
                    alert('Cantitatea trebuie să fie un număr întreg ≥ 1.');
                    return;
                }
                axios.post('<?= BASE_URL ?>api/cart/update-qty', {
                    product_id: item.product_id,
                    quantity: qty
                })
                .then(res => {
                    if (res.data?.success) {
                        // actualizează local
                        const idx = cart.value.findIndex(p => p.product_id === item.product_id);
                        if (idx !== -1) cart.value[idx].quantity = qty;
                        cancelEditQty();
                    } else {
                        alert('Eroare la salvare: ' + (res.data?.message || ''));
                    }
                })
                .catch(() => alert('Eroare la actualizarea cantității!'));
            };

            const removeFromCart = (productId) => {
                if (!productId) return;
                axios.post('<?= BASE_URL ?>api/cart/remove', { product_id: productId })
                    .then(res => {
                        if (res.data.success) getCart();
                        else alert('Eroare la ștergere: ' + (res.data.message || ''));
                    })
                    .catch(() => alert('Eroare la ștergerea produsului!'));
            };

            const createOrder = () => {
                if (cart.value.length === 0) {
                    alert('Coșul este gol!');
                    return;
                }
                axios.post('<?= BASE_URL ?>api/order/create')
                    .then(res => {
                        if (res.data.success) {
                            alert('Comanda a fost plasată cu succes! Număr comandă: ' + res.data.order_id);
                            cart.value = [];

                        } else {
                            alert('Eroare la plasarea comenzii: ' + (res.data.message || ''));
                        }
                    })
                    .catch(() => alert('Eroare la plasarea comenzii!'));
            };

            const totalcartitems = computed( () =>
            {
                let total = 0;
                cart.value.forEach(item => {
                    total += item.quantity;
                });
                return total;
            });

            const totalCart = computed(() => {
                let total = 0;
                cart.value.forEach(item => {
                    total += item.product_price * item.quantity;
                });
                return total.toFixed(2);
            });

            onMounted(() => {
                getCart();
                getAddresses();
                getBillingAddresses();
            });

            return {
                cart,
                totalCart,
                removeFromCart,
                editingQtyId,
                editingQty,
                startEditQty,
                saveQty,
                cancelEditQty,
                totalcartitems,
                createOrder,
                addresses,
                billingAddresses,
                
            };
        }
    });

    app.mount('#app');

</script>



<?php
$content = ob_get_clean();
require_once APP_ROOT . '/app/views/layout.php';



