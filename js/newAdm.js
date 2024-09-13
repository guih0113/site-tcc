const passwordInput = document.getElementById("password");

passwordInput.addEventListener("input", function(){
    const password = this.value;
    const strenghtText = document.querySelector(".password-strength");
    const strenghts = {
        1: "muito fraca",
        2: "fraca",
        3: "moderada",
        4: "forte",
        5: "muito forte",
    };

    let score = 0;
    if(password.length >= 8) score++;
    if(password.match(/[a-z]/)) score++;
    if(password.match(/[A-Z]/)) score++;
    if(password.match(/[0-9]/)) score++;
    if(password.match(/[^a-zA-Z0-9]/)) score++;

    switch(score){
        case 1:
            strenghtText.style.color = '#e70b0b';
            break;

        case 2:
            strenghtText.style.color = '#ffb74d';
            break;

        case 3:
            strenghtText.style.color = '#fff176';
            break;

        case 4:
            strenghtText.style.color = '#b1c784';
            break;

        case 5:
            strenghtText.style.color = '#81c784';
            break;
    }

    //Mostrando o texto
    if(password.length > 0){
        strenghtText.style.display = 'block';
        strenghtText.innerHTML = `Senha ${strenghts[score]}`;
    } 
    
    else{
        strenghtText.innerHTML = "";
    }
})

document.getElementById('cadastrar').addEventListener('submit', function(event) {
    let formData = new FormData(this);
    let canSubmit = true;

    const alertText = document.querySelector(".msg-alert");
    const passwordValue = document.getElementById("password").value;
    let score = 0;
    if(passwordValue.length >= 8) score++;
    if(passwordValue.match(/[a-z]/)) score++;
    if(passwordValue.match(/[A-Z]/)) score++;
    if(passwordValue.match(/[0-9]/)) score++;
    if(passwordValue.match(/[^a-zA-Z0-9]/)) score++;

    if(score < 3){
        canSubmit = false;
        alertText.style.display = 'block';
    } else {
        alertText.style.display = 'none';
    }

    if (canSubmit) {
        fetch('ajax.php', {
            method: 'POST',
            body: formData
        })

        .then(response => response.text())

        .then(data => {
            console.log(data);
        })

        .catch(error => {
            console.error('Error:', error);
        });
    }
});