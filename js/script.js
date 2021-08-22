const API_URL = "http://localhost/Site randos/api/";

let locationBtn = document.querySelector("#location-btn");
let directionBtn = document.querySelector("#direction-btn");
let zoomBtn = document.querySelector("#zoom-btn");

let picturesDiv = document.querySelector("#pictures");
let itemsDiv = document.querySelector("#items");
let categoriesDiv = document.querySelector("#categories");


/*
<div className="item">
    <div className="place-title">
        Direction
    </div>
</div>
*/
function clearCategory() {
    itemsDiv.innerHTML = "";
}

function clearPictures() {
    picturesDiv.innerHTML = "";
}

function unselectItems() {
    itemsDiv.querySelectorAll("div").forEach(div => {
        if (div.classList.contains("selected")) {
            div.classList.remove("selected");
        }
    });
}

function unselectCategories() {
    categoriesDiv.querySelectorAll("div").forEach(div => {
        if (div.classList.contains("selected")) {
            div.classList.remove("selected");
        }
    });
}

function generateCategoryItems(list, category) {
    clearPictures();
    clearCategory();
    list.forEach(item => {
        let itemDiv = document.createElement("div");
        let titleDiv = document.createElement("div");
        itemDiv.classList.add("sub-item");
        titleDiv.classList.add("item-title");
        titleDiv.textContent = item.label;

        itemDiv.append(titleDiv);

        itemDiv.addEventListener("click", async event => {
            unselectItems();
            itemDiv.classList.add("selected");

            let pictures = await getPicturesByCategory("pictures", category, item.id);

            generatePictureItems(pictures);

        });
        itemsDiv.append(itemDiv);
    });
}

function generatePictureItems(list){
    clearPictures();
    list.forEach(picture => {
        let colDiv = document.createElement("div");
        colDiv.classList.add("col", "ml-3");
        let containerDiv = document.createElement("div");
        containerDiv.classList.add("picture-container");
        let infoElement = document.createElement("div");
        infoElement.classList.add("picture-info");
        infoElement.innerHTML = "Lieu de la prise de vue: " + picture.location + "<br/>"
        + "Direction de la prise de vue: " + picture.direction + "<br/>"
        + "Niveau de zoom: " + picture.zoom;
        infoElement.style.color = "black";

        let imgElement = document.createElement("img");
        imgElement.src = picture.url;

        containerDiv.append(imgElement,infoElement);
        colDiv.append(containerDiv);
        picturesDiv.append(colDiv);
    })
}

async function getCategory(name) {

    let result;
    await fetch(API_URL + name + "?fetch=true")
        .then(response => response.json())
        .then(response => {
            result = response;
        })
        .catch(error => console.log("Erreur : " + error))
    return result;
}

async function getPicturesByCategory(name, category, id) {
    let result;
    await fetch(API_URL + name
        + "?fetch=true&category=" + category
        + "&id_category=" + id)
        .then(response => response.json())
        .then(response => {
            result = response;
        })
        .catch(error => console.log("Erreur : " + error))
    if (result == "no result found") {
        return null;
    }
    return result;
}


// event listeners handling

locationBtn.addEventListener("mouseenter", async event => {
    unselectCategories();
    locationBtn.parentNode.classList.add("selected");
    generateCategoryItems(await getCategory("locations"), "l");
});

directionBtn.addEventListener("mouseenter", async event => {
    unselectCategories();
    directionBtn.parentNode.classList.add("selected");
    generateCategoryItems(await getCategory("directions"), "d");
});

zoomBtn.addEventListener("mouseenter", async event => {
    unselectCategories();
    zoomBtn.parentNode.classList.add("selected");
    generateCategoryItems(await getCategory("zoom"), "z");
});

async function letsgooo() {

}


letsgooo();
