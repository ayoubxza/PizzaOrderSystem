document.getElementsByTagName("body")[0].onload = enableButtons; // onload wird ausgeführt, wenn die Seite geladen ist ein funktions pointer

// var variablen sind global und let sind lokal
function addPizza(Element) {
    "use strict";
    let pizzaName = Element.getAttribute("data-name");
    let pizzaPrice = Element.getAttribute("data-price");
    let pizzaID = Element.getAttribute("data-article-id");
    let warenkorb = document.getElementById("warenkorb");


    let existingItem = Array.from(warenkorb.options).find(option => option.value === pizzaName);

    if (existingItem) {
        let count = parseInt(existingItem.getAttribute("data-count") || "1");
        count++;
        existingItem.setAttribute("data-count", count);
        existingItem.text = `${pizzaName} ${pizzaPrice}€ (x${count})`;
    } else {
        let imWarenkorb = document.createElement("option");
        imWarenkorb.text = `${pizzaName} ${pizzaPrice}€ (x1)`;
        imWarenkorb.value = pizzaName;
        imWarenkorb.setAttribute("data-price", pizzaPrice);
        imWarenkorb.setAttribute("data-article-id", pizzaID);
        imWarenkorb.setAttribute("data-count", "1");
        warenkorb.appendChild(imWarenkorb);
    }

    calculatePrice(); 
}

function calculatePrice() {
    let totalCents = 0;
    let warenkorbElement = document.getElementById("warenkorb").firstElementChild;

    while (warenkorbElement != null) {
        let priceInCents = parseFloat(warenkorbElement.getAttribute("data-price")) * 100;
        let count = parseInt(warenkorbElement.getAttribute("data-count")) || 1;
        totalCents += priceInCents * count;
        warenkorbElement = warenkorbElement.nextElementSibling;
    }

    let preis = (totalCents / 100).toFixed(2);
    document.getElementById("total-price").textContent = `${preis} €`;
}

function deleteAllPizza(Element){
    let warenkorb = document.getElementById("warenkorb");
    while(warenkorb.firstChild)
        warenkorb.removeChild(warenkorb.firstChild);
    calculatePrice();
    enableButtons();
}

function deletePizza(Element) {
    let warenkorb = document.getElementById("warenkorb");
    let selectedItems = Array.from(warenkorb.selectedOptions);

    for (let i = 0; i < selectedItems.length; i++) {
        let count = parseInt(selectedItems[i].getAttribute("data-count") || "1");
        if (count > 1) {
            count--;
            selectedItems[i].setAttribute("data-count", count);
            let pizzaName = selectedItems[i].value;
            let pizzaPrice = selectedItems[i].getAttribute("data-price");
            selectedItems[i].text = `${pizzaName} ${pizzaPrice}€ (x${count})`;
        } else {
            warenkorb.removeChild(selectedItems[i]);
        }
    }

    calculatePrice();
    enableButtons(); 
}



function orderPizza() {
    let warenkorb = document.getElementById("warenkorb");

    let neueOptionen = [];

    for (let i = 0; i < warenkorb.options.length; i++) {
        let option = warenkorb.options[i];
        let count = parseInt(option.getAttribute("data-count") || "1");

        for (let j = 0; j < count; j++) {
            let clone = document.createElement("option");
            clone.value = option.value;
            clone.text = option.text;
            clone.selected = true;
            neueOptionen.push(clone);
        }
    }
    warenkorb.innerHTML = "";
    neueOptionen.forEach(opt => warenkorb.appendChild(opt));
    }

function enableButtons(){
    let warenkorb = document.getElementById("warenkorb");
    let adresse = document.getElementById("adresse");
    if(warenkorb.children.length === 0 || adresse.value.trim().length === 0){     // mit children.length wird die Anzahl der Kinder im Element gezählt
        document.getElementById("Order").disabled = true;
        document.getElementById("DeleteOne").disabled = true;
        document.getElementById("DeleteAll").disabled = true;
    }
    else
    {
        document.getElementById("DeleteOne").disabled = false;
        document.getElementById("Order").disabled = false;
    }
    if(warenkorb.children.length >= 1){
        document.getElementById("DeleteOne").disabled = false;
        document.getElementById("DeleteAll").disabled = false;
    }
}