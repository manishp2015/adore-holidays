import $ from "jquery";

class Search {
  // 1. describe and create/initiate an object
  constructor() {
    this.addSearchHTML();
    this.resultsDiv = $("#search-overlay-results");
    this.openButton = $(".js-search-trigger");
    this.closeButton = $(".search-overlay-close");
    this.searchOverlay = $(".search-overlay");
    this.searchField = $("#search-term");
    this.events();
    this.isOverlayOpen = false;
    this.isSpinnerVisible = false;
    this.previousValue;
    this.typingTimer;
  }

  // 2. events
    events() {
      this.openButton.on("click", this.openOverlay.bind(this));
      this.closeButton.on("click", this.closeOverlay.bind(this));
      $(document).on("keydown", this.keyPressDispatcher.bind(this));  
      this.searchField.on("keyup", this.typingLogic.bind(this));
      }
        

  // 3. methods (functions, actions...)
  typingLogic() {
    if (this.searchField.val() != this.previousValue) {
      clearTimeout(this.typingTimer);

      if (this.searchField.val()) {
        if (!this.isSpinnerVisible) {
          this.resultsDiv.html('<div class="spinner-loader"></div>'); 
          this.isSpinnerVisible = true;
        } 
          this.typingTimer = setTimeout(this.getResults.bind(this), 750);
      } else {
        this.resultsDiv.html('');
        this.isSpinnerVisible = false; 
      }
    }
    this.previousValue = this.searchField.val();
  }

  getResults() {
    $.getJSON(holidayData.root_url + '/wp-json/destination/v1/search?term=' + this.searchField.val(), (results) => {
      this.resultsDiv.html(`
        <div class="row">
          <div class="col-lg-4">
            <h4 class="min-list">General Information</h4>
            <hr>
            ${results.generalInfo.length ? '<ul class="custom-link">' : '<p>No general information matches that search.</p>'}
            ${results.generalInfo.map(item => `<li class="min-list"><a href="${item.permalink}">${item.title}</a></li>`).join('')}
            ${results.generalInfo.length ? '</ul>' : ''}
          </div>
          <div class="col-lg-4">
            <h4 class="min-list">Destinations</h4>
            <hr>
            ${results.destination.length ? '<ul class="popular-dest">' : '<p>No destinations matches that search.</p>'}
            ${results.destination.map(item => `
            <div class="row">
              <div class="col-lg-6 col-md-3 col-sm">
              <a href="${item.permalink}"><img class="img-space" src="${item.image}" style="width: 8rem; height: 6.5rem" alt=""></a>
              </div>
              <div class="col-lg-6 col-md-9 col-sm">
                <p><a class="sub-heading-one" href="${item.permalink}">${item.title}</a></p> 
                <hr>                 
              </div>               
             </div>
            <div class="min-list"></div>
            `).join('')}
            ${results.destination.length ? '</ul>' : ''}
            
          </div>
          <div class="col-lg-4">
            <h4 class="min-list">Villas</h4>
            <hr>
            ${results.villa.length ? '' : '<p>No villas matches that search.</p>'}
            ${results.villa.map(item => `
            <div class="row">
            <div class="col-lg-6 col-md-3">
              <a href="${item.permalink}">
                <img class="img-space" src="${item.image}" style="width: 8rem; height: 6.5rem" alt="">
              </a>
            </div>
            <div class="col-lg-6 col-md-9">
              <p><a class="sub-heading-one" href="${item.permalink}">${item.title}</a></p>
              <hr>
            </div>
            </div>
            <div class="min-list"></div>
            `).join('')}
            ${results.villa.length ? '</ul>' : ''}
            
          </div>

        <div>
      
      `)

      this.isSpinnerVisible = false;

    });
 
  }

  keyPressDispatcher(e) {
    
    if (e.keyCode == 83 && !this.isOverlayOpen && !$("input, textarea").is(':focus')) {
      this.openOverlay();
    }

    if (e.keyCode == 27 && this.isOverlayOpen) {
      this.closeOverlay();
    }

  }

  openOverlay() {
    this.searchOverlay.addClass("search-overlay-active");
    $("body").addClass("body-no-scroll");
    this.searchField.val('');
    setTimeout(() => this.searchField.focus(), 301);
    console.log("our open method just ran");
    this.isOverlayOpen = true;
    return false;
  }

  closeOverlay() {
    this.searchOverlay.removeClass("search-overlay-active");
    $("body").removeClass("body-no-scroll");
    console.log("our close method just ran");
    this.isOverlayOpen = false;
  }

  addSearchHTML() {
    $("body").append(`
    <div class="search-overlay">
     <div class="search-overlay-top">
       <div class="container">
         <i class="fa fa-search search-overlay-icon" aria-hidden="true"></i>
        <input type="text" class="search-term" placeholder="What are you looking for?" id="search-term">
        <i class="fa fa-window-close search-overlay-close" aria-hidden="true"></i>   
       </div>
     </div>  
   
     <div class="container">
      <div id="search-overlay-results">
         
      </div>
     </div>
   
    </div>
    `)
  }

}

export default Search
