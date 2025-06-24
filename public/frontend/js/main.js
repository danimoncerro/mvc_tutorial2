console.log("Hello, World!")

//function salut(name) {
//    return `Hello, ${name}!`;
//}

const salut = (name) => {
    return `Hello, ${name}!`;
}

console.log(salut("Maria"));

let fruits= ["apple", "banana", "cherry"];

console.log(fruits);


const fruitNames = fruits.map(fruit => fruit.toUpperCase());

console.log(fruitNames);

const fruitNames2 = fruits.filter(fruit => fruit.indexOf("a") !== -1);

console.log(fruitNames2);

const nrFruits = fruits.reduce((total, fruit) => {
    return total + 1;
}, 0);
console.log(nrFruits);