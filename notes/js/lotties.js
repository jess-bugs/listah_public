$('document').ready(function() {




    // loading note animation
    lottie.loadAnimation({
        container: $("#loading-note")[0],
        renderer: 'svg',
        loop: true,
        autoplay: true,
        path: "https://www.jessbaggs.com/res/lotties/bar_loading_anim.json" 
    });




    // empty notes lottie

    lottie.loadAnimation({
        container: $("#empty-notes")[0],
        renderer: 'svg',
        loop: true,
        autoplay: true,
        path: "https://www.jessbaggs.com/res/lotties/empty_box_2.json" 
    });

})