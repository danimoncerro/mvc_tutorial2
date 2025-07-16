//function salut(name) {
//    return `Hello, ${name}!`;
//}

const salut = (name) => {
    return `Hello, ${name}!`;
}



let fruits= ["apple", "banana", "cherry"];




const fruitNames = fruits.map(fruit => fruit.toUpperCase());



const fruitNames2 = fruits.filter(fruit => fruit.indexOf("a") !== -1);



const nrFruits = fruits.reduce((total, fruit) => {
    return total + 1;
}, 0);


function afiseazaTabelDate(date) {
    // Creează header tabel din cheile primului obiect
    if (!Array.isArray(date) || date.length === 0) {
        document.getElementById('tabel-ajax').innerHTML = 'Nu există date!';
        return;
    }
    let header = Object.keys(date[0]);
    let html = '<table border="1"><thead><tr>';
    header.forEach(col => html += `<th>${col}</th>`);
    html += '</tr></thead><tbody>';
    date.forEach(row => {
        html += '<tr>';
        header.forEach(col => html += `<td>${row[col]}</td>`);
        html += '</tr>';
    });
    html += '</tbody></table>';
    document.getElementById('tabel-ajax').innerHTML = html;
}