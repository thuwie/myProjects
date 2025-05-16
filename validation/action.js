const action = {
    addEvent: function(elementDOM, event, callback) {
        elementDOM.addEventListener(event, callback);
    },
    start: function() {
        const btnChangeImg = document.querySelector('.btn-change-img');
        const imgFile = document.querySelector('.newImage');
        console.log(imgFile);
        
        const callback = function() {
            imgFile.click();
        };
        this.addEvent(btnChangeImg, 'click', callback);
        
    },
    init: function() {
        this.start();
    }
};

action.init();
