const validation = function(selector) {
    const FORM = document.getElementById(selector);
    const messageElement = FORM.querySelector('.validation-message');
    const REQUIREMENT_OF_INPUTS = {};

    var inputs = FORM.querySelectorAll('input');
    var maxLenghth;

    const validator = {
        require: (field, value) => {
           return value ?  '':  `Vui lòng nhập ${field} !!!`
        }, 
        length: (field, value) => {
            return value && value.length < 8 ? `${field} phải có ít nhất 8 kí tự !!!` : '';
        }
    };

    Array.from(inputs).forEach(input => {
        var rule = input.getAttribute('rules').split('|');
        
        if(rule.some(rule => rule.includes('-'))) {
            maxLenghth = rule[1].split('-')[1];
            rule[1] = rule[1].split('-')[0];
        };

        REQUIREMENT_OF_INPUTS[input.name] = rule;
    });


   function addEvent(input) {
    for(let i = 0; i < REQUIREMENT_OF_INPUTS[input.name].length; i++) {
        var mess;
        input.addEventListener('blur',  ()=> {
            mess = validator[REQUIREMENT_OF_INPUTS[input.name][i]](input.getAttribute('alias'), input.value);
            console.log(mess);
            //Lỗi khi password lkhoong nhập gì đã ra thông báo nhập mk nhưng bị thay đổi thành null
            messageElement.innerText = mess;
        });
        if(mess === !'') break;  
    };
   };

    Array.from(inputs).forEach(input => addEvent(input));

    //Hiển thị Password khi click icon
    function togglePass(formSelector) {
    const showPassword = formSelector.querySelector('.show-password');
    const inputPassword = formSelector.querySelector('.password');
    
    showPassword.addEventListener('click', function() {
        if(inputPassword.type === "password") {
            inputPassword.type = "text";
        } else {
            inputPassword.type = "password";
        };
    });
    }

    togglePass(FORM);
};


