const form = document.querySelector(".typing-area"),
inputField = form.querySelector(".input-field"),
sendBtn = form.querySelector("button"),
chatBox = document.querySelector(".chat-box");

form.onsubmit = (e)=>{
e.preventDefault(); //prevent from form submit
}

sendBtn.onclick = ()=>{
    //AJAX Code
   let xhr = new XMLHttpRequest(); //creating XML objects
   xhr.open("POST", "../php/insert-chat.php",true);
   xhr.onload = ()=>{
             if(xhr.readyState === XMLHttpRequest.DONE){
                if(xhr.status === 200){
                    inputField.value = "";//once msg is inserted into the db then leaves the input field blank
                    scrollToBottom();
                    
                }
             }
   } 
   let formData = new FormData(form); //new formdata object
   xhr.send(formData);
}

chatBox.onmouseenter = ()=>{
    chatBox.classList.add("active");
}
chatBox.onmouseenter = ()=>{
    chatBox.classList.remove("active");
}


setInterval(()=>{
 //AJAX Code
   let xhr = new XMLHttpRequest(); //creating XML objects
   xhr.open("POST", "/Chat-App/php/get-chat.php", true);

   xhr.onload = ()=>{
             if(xhr.readyState === XMLHttpRequest.DONE){
                if(xhr.status === 200){
                    let data = xhr.response;
                    chatBox.innerHTML = data;
                    if(!chatBox.classList.contains("active")){
                        scrollToBottom();
                    }
                    
                  
                   }
             }
            }
            
   let formData = new FormData(form); //new formdata object
   xhr.send(formData);
},500);

function scrollToBottom() {
    chatBox.scrollTop = chatBox.scrollHeight;

}