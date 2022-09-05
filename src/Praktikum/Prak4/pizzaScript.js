function addToWarenkorb(pizzaName, article_id, elementPointer){
    "use strict";
    //Element an in die Select liste hängen
    let warenkorb = document.getElementById("warenkorb");

    let neuesElement = document.createElement("option");
    let neuerText = document.createTextNode(pizzaName);
    neuesElement.value = article_id;
    neuesElement.appendChild(neuerText);
    warenkorb.appendChild(neuesElement);

    //Preis berechnen
    let preis = parseFloat(elementPointer.getAttribute("data-preis"));
    let totalPreisNode = document.getElementById("totalPrice");
    let totalPreis = totalPreisNode.innerText;
    if(totalPreisNode.innerText === ""){
        totalPreisNode.innerText = preis.toFixed(2);
    } else{
        totalPreis = parseFloat(totalPreis);
        totalPreis += preis;
        totalPreisNode.innerText = totalPreis.toFixed(2);
    }
}

function auswahlLöschen(){
    "use strict";
    let warenkorb = document.getElementById("warenkorb");

    for(let i=0; i<warenkorb.length; i++){
        if(warenkorb[i].selected == true){
            let pizzaName = document.getElementById(warenkorb[i].innerText);
            //console.log(pizzaName);
            //let pizzaNode = document.getElementById(pizzaName);
            let preis = parseFloat(pizzaName.getAttribute("data-preis"));
            let totalPreisNode = document.getElementById("totalPrice");
            let totalPreis = parseFloat(totalPreisNode.innerText);
            totalPreis -= preis;
            if(totalPreis == 0.00){
                totalPreisNode.innerText = "";
            }else{
                totalPreisNode.innerText = totalPreis.toFixed(2);
            }
            warenkorb.remove(i);
            i--;
        }
    }
}

function alleLöschen(){
    "use strict";
    let warenkorb = document.getElementById("warenkorb");
    for(let i=0; i<warenkorb.length; ++i){
        warenkorb.remove(i);
        --i;
    }

    document.getElementById("totalPrice").innerText = "";
}

function formularOK(){
    "use strict";

    let warenkorb = document.getElementById("warenkorb");
    if(warenkorb.length > 0){
        for(let i=0; i<warenkorb.length; ++i){
            warenkorb[i].selected = true;
        }
    } else { 
        alert("Bitte fügen Sie einen Artikel zu Ihrem Warenkorb hinzu");
        return false;
    }
    let addressNode = document.getElementById("adresse");
    if(addressNode.value == "" || addressNode == null){
        alert("Bitte geben Sie Ihre Adresse ein");
        return false;
    }
    return true;
}

function buttonActivation(){

    if(document.getElementById("warenkorb").length<1 || document.getElementById("adresse").value == ""){
        alert("Bitte warenkorb füllen oder adresse angeben");
        document.getElementById("bestellen").disabled = true;
    }else{
        alert("Button ist aktiv");
        document.getElementById("bestellen").disabled = false;
    }
}

function submitOnClick(id){
    document.getElementById(id).submit();
}