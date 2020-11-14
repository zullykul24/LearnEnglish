/// words speaker

$(document).ready(function(){

    if ('speechSynthesis' in window) {
    $(".word-reading").click(function(){
        var mmm = $(this).parent().parent().children(".word-text").text();
        console.log(mmm);
        voices = window.speechSynthesis.getVoices();
        var msg = new SpeechSynthesisUtterance(mmm);
        msg.lang = 'en-US';
       
        msg.voice = voices[4];
        msg.lang = ""
        msg.rate = 1;
       
        msg.pitch = 1;
        window.speechSynthesis.cancel();
        speechSynthesis.speak(msg);
    });
}
})
