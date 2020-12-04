import "../css/style.css"

// Our modules / classes
import HeroSlider from "./modules/HeroSlider"
import GoogleMap from "./modules/GoogleMap"
import Search from "./modules/Search"



// Instantiate a new object using our modules/classes
const heroSlider = new HeroSlider()
const googleMap = new GoogleMap()
const search = new Search()



// Allow new JS and CSS to load in browser without a traditional page refresh
if (module.hot) {
  module.hot.accept()
}


