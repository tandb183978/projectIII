@extends('layouts.homepage')

@section('home-content')

                                <!-- Home content carousel -->

        <div id="demo" class="carousel slide" data-ride="carousel" data-interval="3000">
            <!-- Indicators -->
            <ul class="carousel-indicators">
                <li data-target="#demo" data-slide-to="0" class="active"></li>
                <li data-target="#demo" data-slide-to="1"></li>
                <li data-target="#demo" data-slide-to="2"></li>
                <li data-target="#demo" data-slide-to="3"></li>
            </ul>

            <!-- The slideshow -->
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <div class="d-flex align-items-center justify-content-center min-vh-100">
                        <img src="https://www.incimages.com/uploaded_files/image/1920x1080/getty_825175626_407432.jpg" alt="...">
                    </div>
                </div>

                <div class="carousel-item">
                    <div class="d-flex align-items-center justify-content-center min-vh-100">
                        <img src="https://www.akersolutions.com/globalassets/images_1920x1080/two_men_business_generic_1920x1080.png" alt="...">
                    </div>
                </div>

                <div class="carousel-item">
                    <div class="d-flex align-items-center justify-content-center min-vh-100">
                        <img src="https://cdn.teamdeck.io/uploads/website/2019/03/06144026/teamdeck-baner-blog-1920x1080-2-e1555411710692.png" alt="...">
                    </div>
                </div>

                <div class="carousel-item">
                    <div class="d-flex align-items-center justify-content-center min-vh-100">
                        <img src="https://cdn.decision-wise.com/wp-content/uploads/2017/10/DecisionWise_Reveal-Pulse-Survey_1920x1080.jpg" alt="...">
                    </div>
                </div>

            </div>

            <!-- Left and right controls -->
            <a class="carousel-control-prev" href="#demo" data-slide="prev">
                <span class="carousel-control-prev-icon"></span>
            </a>
            <a class="carousel-control-next" href="#demo" data-slide="next">
                <span class="carousel-control-next-icon"></span>
            </a>
        </div>
@endsection


