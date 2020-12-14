<form class="search-form row" method="get" action="<?php echo esc_url(site_url('/')); ?>"> 

        <div class="col-lg-12">
            <label class="sub-heading-three" for="s">Perform a New Search:</label>
        </div>
        <div class="search-form-row col-lg-12">
            
                <input class="s col-lg-6" id="s" type="search" name="s" placeholder="What are you looking for?" style="margin-right: 10px"></input> 
            
                <input type="number" class="form-control col-lg-4" name="no_of_bedrooms" value="no_of_bedrooms" placeholder="No of Bedrooms">        

                <input class="search-submit col-lg-2" type="submit" value="Search"></input>
            
        </div>     
 
        </form>