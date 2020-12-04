import $ from 'jquery'

class TopButton {
    //1. describe and create/initiate an object
    constructor() {
        this.topbuttonVisible = false;

    }
}

//2. events
    event () {
        this.$('.topbutton').on('click', function(){
            $('html, body').animate({scrollTop:0}, speed);
            return false;
    }



//3. methods (functions, actions..) 
    
