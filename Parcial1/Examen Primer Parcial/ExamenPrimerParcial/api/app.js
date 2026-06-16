const BASE_URL = "https://jsonplaceholder.typicode.com";

document.getElementById("btn-get-post").addEventListener("click", async () => {
    fetch(BASE_URL + "/posts?_limit=10")
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
            divResultGet.innerHTML += `<p><strong>ID del post: </strong> ${post.id}</p><p><strong>Título del post: </strong> ${post.title}</p><p><strong>Contenido del post: </strong> ${post.body}</p><hr>`;
        });
    })
    .catch(error =>{ 
        alert("Ocurrió un error al realizar la petición");
        console.error("Error en la petición:",error);
    })
});