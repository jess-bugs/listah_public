$('document').ready(function() {




    // boy icon for male gender
    lottie.loadAnimation({
        container: $("#boy-icon")[0],
        renderer: 'svg',
        loop: true,
        autoplay: true,
        path: "https://www.jessbaggs.com/res/lotties/boy_icon.json" 
    });



    // girl icon for female gender
    lottie.loadAnimation({
        container: $("#girl-icon")[0],
        renderer: 'svg',
        loop: true,
        autoplay: true,
        path: "https://www.jessbaggs.com/res/lotties/woman_icon.json" 
    });


})