<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Links -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="icon" href="../img/logoabmweb.png">
    <link rel="stylesheet" href="../css/main.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200..1000;1,200..1000&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=ZCOOL+QingKe+HuangYou&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <title>Angkatan Belia MFLS Negeri Kedah</title>
</head>

<body>


    <!-- Homepage Header -->
    <?php include 'homepage-header.php'; ?>
    <!-- Overlay for sidebar -->
    <div id="overlay" class="overlay"></div>
    <!-- Homepage Sidebar -->
    <?php include 'homepage-sidebar.php'; ?>

    <?php
    include '../sql/nonmember-functions.php';
    $events = getActiveEvents($conn);
    ?>




    <!-- Content -->
    <section class="mainpage-sectionone">
        <div class="container sectionone-wrapper">
            <div class="row">
                <div class="col-md">
                    <div class="maintitle">
                        <h1>New Leaders are born</h1>
                        <p>ABM Kedah builds leadership potential in youth nationwide. We don't build buildings, <b>we
                                build people of tomorrow.</b></p>
                    </div>
                </div>
                <div class="col-md">
                    <img class="img-fluid mainimagesectionone" src="../img/mainimagesectionone.jpg">
                </div>

            </div>
        </div>
    </section>

    <section class="mainpage-sectiontwo">
        <div class="container sectiontwo-wrapper">
            <div class="row">
                
                <div class="col-lg program-wrapper">
                    <h2 class="text-start pb-2 py-2 fw-bold">News Bulletin</h2>    
                        <div class="col">
                            <div class="input-group">
                                <div class="form-floating flex-grow-1">
                                    <input type="text" class="form-control" placeholder="Search..." id="searchbar">
                                    <label for="searchbar">Search...</label>
                                </div>                                
                                <!-- <span class="input-group-text searchbarbtn"><i class="fa-solid fa-magnifying-glass fa-xs"></i></span> -->
                            </div>
                        </div>

                        <div class="news-container">                    
                            <div class="bekas">
                                <div class="news">
                                    <div class="row">
                                        <div class="col-auto"  >
                                            <img class="banner-pic" src="../img/sectiontwo1.JPG">
                                        </div>
                                        <div class="col-md" >
                                            <h2 class="title">Government Announces New Stimulus Package</h2>
                                            <p class="date">July 12, 2024</p>
                                            <p class="content">The government has announced a new stimulus package to support small businesses affected by the recent economic downturn.</p>
                                            <div class="form-group save-button">
                                                <button type="submit">Read More</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="contain">
                                <a href="#" class="card-link">
                                <div class="news">
                                    <div class="row">
                                        <div class="col-auto"  >
                                            <img class="banner-pic" src="../img/sectiontwo1.JPG">
                                        </div>
                                        <div class="col-md" >
                                            <h2 class="title">Government Announces New Stimulus Package</h2>
                                            <p class="date">July 12, 2024</p>
                                            <p class="content">The government has announced a new stimulus package to support small businesses affected by the recent economic downturn.</p>
                                            <div class="form-group save-button">
                                                <button type="submit">Read More</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                </a>
                            </div>
                            <div class="bekas">
                                <div class="news">
                                    <div class="row">
                                        <div class="col-auto"  >
                                            <img class="banner-pic" src="../img/sectiontwo1.JPG">
                                        </div>
                                        <div class="col-md" >
                                            <h2 class="title">Government Announces New Stimulus Package</h2>
                                            <p class="date">July 12, 2024</p>
                                            <p class="content">The government has announced a new stimulus package to support small businesses affected by the recent economic downturn.</p>
                                            <div class="form-group save-button">
                                                <button type="submit">Read More</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="bekas">
                                <a href="#" class="card-link">
                                <div class="news">
                                    <div class="row">
                                        <div class="col-auto"  >
                                            <img class="banner-pic" src="../img/sectiontwo1.JPG">
                                        </div>
                                        <div class="col-md" >
                                            <h2 class="title">Government Announces New Stimulus Package</h2>
                                            <p class="date">July 12, 2024</p>
                                            <p class="content">The government has announced a new stimulus package to support small businesses affected by the recent economic downturn.</p>
                                            <div class="form-group save-button">
                                                <button type="submit">Read More</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                </a>
                            </div>
                            <div class="bekas">
                                <div class="news">
                                    <div class="row">
                                        <div class="col-auto"  >
                                            <img class="banner-pic" src="../img/sectiontwo1.JPG">
                                        </div>
                                        <div class="col-md" >
                                            <h2 class="title">Government Announces New Stimulus Package</h2>
                                            <p class="date">July 12, 2024</p>
                                            <p class="content">The government has announced a new stimulus package to support small businesses affected by the recent economic downturn.</p>
                                            <div class="form-group save-button">
                                                <button class="readmoretbtn" type="submit">Read More</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                </div>

                <!-- this is the part where the event will show up -->
                <div class="col-3 program-wrapper">
                    <h2 class="text-center pb-2 py-2 fw-bold">Active Activity</h2>
                        <div class="col">
                            <div class="input-group">
                                <div class="form-floating flex-grow-1">
                                    <input type="text" class="form-control" placeholder="Search..." id="searchbar">
                                    <label for="searchbar">Search...</label>
                                </div>
                                <!-- <span class="input-group-text searchbarbtn"><i class="fa-solid fa-magnifying-glass "></i></span> -->
                            </div>
                        </div>

                    <div class="activity-container">
                        <?php foreach ($events as $event) { ?>
                            <a href="../non-member/nonmember-registeractivity.php?event_id=<?php echo $event['event_id']; ?>" class="card-link">
                                <div class="activity">
                                    <img class="banner-act" src="<?php echo $event['banner']; ?>">  
                                    <h3 class="text-center"><?php echo $event['event_name']; ?></h3>                                               
                                </div>
                            </a>
                        <?php } ?>
                        
                    </div>
                </div>
                
            </div>
        </div>
    </section>



    <!-- Homepage Footer -->
    <?php include 'homepage-footer.php'; ?>



    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../js/sidebar.js"></script>
    <script src="../js/crud/registermember.js"></script>
    <script src="../js/crud/loginmember.js"></script>



</body>

</html>