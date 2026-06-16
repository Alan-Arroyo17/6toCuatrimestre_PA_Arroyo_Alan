const BASE_URL = 'https://fakestoreapi.com';

document.getElementById("btn-get-products").addEventListener("click", async () => {
    fetch(BASE_URL + "/products")
    .then(response => { 
        if(response.ok){ 
            return response.json(); 
        }else{
            throw new Error("Error en la respuesta: " + response.status); 
        }
    })
    .then(data =>{ 
        const divResultGet = document.getElementById("get-result");
        divResultGet.innerHTML = "";
        data.forEach(post => {
            divResultGet.innerHTML += `<p><strong>Imagen: </strong> ${post.image}</p><p><strong>Título: </strong> ${post.title}</p><p><strong>Precio: </strong> ${post.price}</p><p><strong>Categoría: </strong> ${post.category}</p><hr>`;
        });
    })
    .catch(error =>{ 
        alert("Ocurrió un error al realizar la petición");
        console.error("Error en la petición:",error);
    })
});    