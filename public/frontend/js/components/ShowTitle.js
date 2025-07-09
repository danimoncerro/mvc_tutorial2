const ShowTitle = {
    template: `
        <div>
            <button @click="showtitle=!showtitle">Ascunde / arata titlul</button>    
            <p v-if="showtitle"> Titlu </p>
        </div>
    `,
    setup() {
        const showtitle = Vue.ref(true);
        return {
            showtitle
        };
    }
};