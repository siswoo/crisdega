document.getElementById("referencia").addEventListener("keyup",load)

function load() {
    let inputRef= document.getElementById("referencia").value
    let lista= document.getElementById("Lista")

    let url = "../script/load.php"
    let formData = new FormData()
    formData.append("referencia", inputRef)

    fetch(url, {
        method:"POST",
        body: formData,
        mode: "cors"
    }).then(response => response.json())
    .then(data => {
        lista.style.display ='block'
        lista.innerHTML = data
    })
    .catch(err => console.log(err))
}