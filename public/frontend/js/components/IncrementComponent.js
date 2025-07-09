const IncrementComponent = {
    template: `
        <div>
            <button @click="increment" class="btn btn-primary">Increment</button>  
            <p>Count: {{ count }}</p>
        </div>
    `,
    setup() {
        const count = Vue.ref(0);
        
        function increment() {
        
            count.value++;
        }

        return {
            count,
            increment
        };
    }
};