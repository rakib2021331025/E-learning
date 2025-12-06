let mediaRecorder, audioChunks = [];
const stuEmail = $('input[name="sender"]').val();
const adminEmail = $('input[name="receiver"]').val();

function loadChat(){
    $.post("../chat/fetch_messages.php",{student_email:stuEmail}, function(data){
        $("#chatBox").html(data);
        $(".chat-container").scrollTop($(".chat-container")[0].scrollHeight);
    });
}
setInterval(loadChat,1500); loadChat();

// Send message (text or file)
$("#chatForm").submit(function(e){
    e.preventDefault();
    $.ajax({
        url: "send_message.php",
        type: "POST",
        data: new FormData(this),
        contentType: false,
        processData: false,
        success:function(){ $("#chatForm")[0].reset(); loadChat(); }
    });
});

// Clear chat
$("#clearChat").click(function(){
    if(confirm("Are you sure to clear all chats?")){
        $.post("../chat/clear_chat.php",{u1:stuEmail,u2:adminEmail}, function(){ loadChat(); });
    }
});

// Audio record
$('#recStart').click(async function(){
    try {
        const stream = await navigator.mediaDevices.getUserMedia({audio:true});
        mediaRecorder = new MediaRecorder(stream,{mimeType:'audio/webm'});
        audioChunks=[];
        mediaRecorder.ondataavailable = e=>{ if(e.data.size>0) audioChunks.push(e.data); };
        mediaRecorder.start();
        $('#recStart').hide(); $('#recStop').show();
    } catch(err){
        alert("Microphone access denied or not available: "+err);
    }
});

$('#recStop').click(function(){
    if(!mediaRecorder) return;
    mediaRecorder.stop();
    mediaRecorder.onstop = ()=>{
        const blob = new Blob(audioChunks,{type:'audio/webm'});
        const file = new File([blob],'audio_'+Date.now()+'.webm',{type:'audio/webm'});
        const fd = new FormData();
        fd.append('sender',stuEmail);
        fd.append('receiver',adminEmail);
        fd.append('file',file);

        fetch('send_message.php',{method:'POST',body:fd}).then(()=>{
            $('#recStart').show(); $('#recStop').hide(); loadChat();
        });
    };
});
