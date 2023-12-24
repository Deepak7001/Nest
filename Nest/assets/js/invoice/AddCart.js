

let cart = [];
let addeds=["AddSeed","Stouffer","StarKist","SSTFood","SSTFood2","SSTFood3","SSTFood4","SSTFood5","SSTFood6","SSTFood2"];

function addToCart(productName, productImage, productPrice, quantityId,added) {
    
    const quantity = document.getElementById(quantityId).value;

    cart.push({
        name: productName,
        image: productImage,
        price: productPrice,
        quantity: quantity
    });
   

  
    for (let element of addeds) {
        if(element==added){
       changeAdd(element);
        }
    }

 
    updateCartDisplay();

    sendCartToServer(cart);
}

function removeFromCart(index,name) {
    // Remove the product at the given index
    cart.splice(index, 1);

    //delete product to given cart items
    deleteItem(name);
    
    // Update the cart display
    updateCartDisplay();
}

function updateCartDisplay() {
    const cartContents = document.getElementById('cartContents');
    cartContents.innerHTML = '';
    let totalPrice = 0;
    let totalitems=0;

    // Iterate over the cart array with the index
    cart.forEach((product, index) => {
        let li = document.createElement('li');

        let img = document.createElement('img');
        img.src = product.image;
        img.alt = product.name;
        img.width = 50;
        li.appendChild(img);

        li.innerHTML += `<span style="color:#3BB77E">${product.name}</span>  ${product.quantity}  X   $${product.price} `;

       
        let deleteButton = document.createElement('button');
        deleteButton.innerHTML = '🗑️';
        deleteButton.onclick = function() { removeFromCart(index,product.name); };
        deleteButton.style.border = 'none';
        deleteButton.style.background = 'transparent';
        deleteButton.style.cursor = 'pointer';
        deleteButton.style.marginLeft='10vw';
        li.appendChild(deleteButton);
        
        cartContents.appendChild(li);


         // Calculate total price
         totalPrice += parseFloat(product.price) * parseInt(product.quantity);
    });
    document.getElementById('pro-count').innerHTML=cart.length;

    document.getElementById('totalPrice').textContent = `$${totalPrice.toFixed(2)}`;


}




// this function for fetching details to databases
function sendCartToServer(cart) {
    // Assuming 'cart' is an array containing your data

    fetch('http://localhost/nest/index.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ cart: cart }) 
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
       
    })
    .catch((error) => {
        console.error('Error:', error);
    });
}



// this function change button add to Added
function changeAdd(element){
    let elementAdded=document.getElementById(`${element}`);
    elementAdded.innerHTML="Added";
    
   
}

// delete item from cart 

function deleteItem(name) {
    fetch('http://localhost/nest/delete.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ itemName: name })
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        // Handle the response from the server if needed
    })
    .catch((error) => {
        console.error('Error:', error);
    });
}
