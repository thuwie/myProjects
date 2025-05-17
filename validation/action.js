const action = {
    addEvent: function(elementDOM, event, callback) {
        elementDOM.addEventListener(event, callback);
    },
    start: function() {
        const btnChangeImg = document.querySelector('.btn-change-img');
        const imgFile = document.querySelector('.newImage');
        const img = document.querySelector('.images');

        const callback = function() {
            imgFile.click();
        };

        const callbackImg = function() {
        const file = this.files[0];
        if (file && file.type.startsWith('image/')) {
                const imgURL = URL.createObjectURL(file);
                img.setAttribute('src', imgURL);
                // Optional: giải phóng bộ nhớ sau khi ảnh load xong
                img.onload = () => {
                    URL.revokeObjectURL(imgURL);
                };
            };
        };

        this.addEvent(btnChangeImg, 'click', callback);
        this.addEvent(imgFile, 'change', callbackImg);
        
    },
    init: function() {
        this.start();
    }
};

action.init();
