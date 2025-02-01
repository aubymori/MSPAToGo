const audios = [
    new Audio("/assets/sound/6.mp3"),
    new Audio("/assets/sound/7.mp3"),
    new Audio("/assets/sound/8.mp3"),
    new Audio("/assets/sound/9.mp3"),
    new Audio("/assets/sound/10.mp3"),
    new Audio("/assets/sound/11.mp3"),
    new Audio("/assets/sound/12.mp3"),
    new Audio("/assets/sound/13.mp3"),
    new Audio("/assets/sound/14.mp3"),
    new Audio("/assets/sound/15.mp3"),
    new Audio("/assets/sound/16.mp3"),
    new Audio("/assets/sound/17.mp3"),
    new Audio("/assets/sound/18.mp3"),
    new Audio("/assets/sound/19.mp3"),
];

document.getElementById("trickster-banner").addEventListener("click", () => {
    audios[Math.floor(Math.random() * audios.length)].play();
});