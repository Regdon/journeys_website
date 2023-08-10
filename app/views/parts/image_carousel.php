<div id="myCarousel" class="carousel slide" data-ride="carousel">
    <!-- Indicators -->
    <ol class="carousel-indicators">
        <?php
            $max_ids = min(5, count($data['image_url']));
            for ($id=0; $id < $max_ids; $id++) { 
                include APPROOT . '/views/parts/image_carousel_indicator.php';
            }
        ?>
    </ol>

    <!-- Wrapper for slides -->
    <div class="carousel-inner">
        <?php
            for ($id=0; $id < $max_ids; $id++) { 
                include APPROOT . '/views/parts/image_carousel_wrapper.php';
            }
        ?>
    </div>

    <!-- Left and right controls -->
    <a class="left carousel-control" href="#myCarousel" data-slide="prev">
        <span class="glyphicon glyphicon-chevron-left"></span>
        <span class="sr-only">Previous</span>
    </a>
    <a class="right carousel-control" href="#myCarousel" data-slide="next">
        <span class="glyphicon glyphicon-chevron-right"></span>
        <span class="sr-only">Next</span>
    </a>
</div>