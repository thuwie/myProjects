const validation = function(selector) {
    
    const FORM = document.getElementById(selector);
    const messageElement = FORM.querySelector('.validation-message');
    const REQUIREMENT_OF_INPUTS = {};
    const BTN_SUBMIT = FORM.querySelector('.btn-submit');

    var inputs = FORM.querySelectorAll("input:not(#remember)");
    var maxLenghth;
    var mess;

    const validator = {
        require: (field, value) => {
           return value ?  undefined:  `Vui lòng nhập ${field} !!!`
        }, 
        length: (field, value) => {
            var rs =  value.length < 8 ? `${field} phải có ít nhất 8 kí tự !!!` : undefined;
            return rs ?  rs[0].toUpperCase() + rs.slice(1) : rs;
        }
    };

    Array.from(inputs).forEach(input => {
        var rule = input.getAttribute('rules');
        console.log( typeof rule);
        
        if(rule.includes('|')) {
            rule = rule.split("|");
        } else {
            rule = [rule];
        };
        
        if(rule.some(rule => rule.includes('-'))) {
            maxLenghth = rule[1].split('-')[1];
            rule[1] = rule[1].split('-')[0];
        };

        REQUIREMENT_OF_INPUTS[input.name] = rule;
    });


   function addEvent(input) {
        input.addEventListener('blur',  function() {
             for(let i = 0; i < REQUIREMENT_OF_INPUTS[input.name].length; i++) {
                    mess = validator[REQUIREMENT_OF_INPUTS[input.name][i]](input.getAttribute('alias'), input.value);
                    if(mess) {
                        messageElement.innerText = mess;
                        break;
                    } else {
                        messageElement.innerText ="" ;
                    };  
             };
        });

        input.addEventListener('input',  function() {
            if(mess) {
                 messageElement.innerText = "";
            };
        });
   };

   //Đăng ký sự kiện cho button Đăng Nhập
   BTN_SUBMIT.addEventListener('click', (e) => {
        inputs.forEach(input => {
             for(let i = 0; i < REQUIREMENT_OF_INPUTS[input.name].length; i++) {
                    if(!input.value) {
                         messageElement.innerText = "Vui lòng nhập đầy đủ thông tin !!!";
                        e.preventDefault();
                        break;
                    } else if(mess){
                        e.preventDefault();
                        break;
                    } else {
                        messageElement.innerText ="" ;
                    };  
            };
        });
   });

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


