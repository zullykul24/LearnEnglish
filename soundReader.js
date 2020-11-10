/// words speaker

$(document).ready(function(){

    if ('speechSynthesis' in window) {
    $(".word-reading").click(function(){
        var text = $(this).prev().prev().prev().text();
        console.log(text);
        voices = window.speechSynthesis.getVoices()
        var msg = new SpeechSynthesisUtterance(text);
       
        msg.voice = voices[4];
        msg.rate = 1;
       
        msg.pitch = 1;
     
        speechSynthesis.speak(msg);
    });
}
})
