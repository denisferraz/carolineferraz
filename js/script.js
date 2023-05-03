if (window.location.href.endsWith("home.html")) {

  const imgsdesk = document.getElementById("img-desktop");
const imgdesk = document.querySelectorAll("#img-desktop img");

let idxdesk = 0;

function carrosseldesk(){

    idxdesk++;

    if(idxdesk > imgdesk.length - 1){
        idxdesk = 0;
    }

    imgsdesk.style.transform = `translateX(${-idxdesk * 500}px)`;
}

setInterval(carrosseldesk, 4000);

const imgs2desk = document.getElementById("img2-desktop");
const img2desk = document.querySelectorAll("#img2-desktop img");

let idy2desk = 0;

function carrossel2desk(){

    idy2desk++;

    if(idy2desk > img2desk.length - 1){
        idy2desk = 0;
    }

    imgs2desk.style.transform = `translateX(${-idy2desk * 500}px)`;
}

setInterval(carrossel2desk, 5000);

const imgsmob = document.getElementById("img-mobile");
const imgmob = document.querySelectorAll("#img-mobile img");

let idxmob = 0;

function carrosselmobile(){

    idxmob++;

    if(idxmob > imgmob.length - 1){
        idxmob = 0;
    }

    imgsmob.style.transform = `translateX(${-idxmob * 250}px)`;
}

setInterval(carrosselmobile, 4000);

const imgs2mob = document.getElementById("img2-mobile");
const img2mob = document.querySelectorAll("#img2-mobile img");

let idy2mob = 0;

function carrossel2mobile(){

    idy2mob++;

    if(idy2mob > img2mob.length - 1){
        idy2mob = 0;
    }

    imgs2mob.style.transform = `translateX(${-idy2mob * 250}px)`;
}

setInterval(carrossel2mobile, 5000);

}