<?php
    require_once 'config_session.php';
    require_once 'dbh.php';
    // fetch all posts from the database, along with the corresponding usernames
    try {
        $stmt = $pdo->query("SELECT posts.*, users.username FROM posts JOIN users ON posts.userid = users.id");
        $posts = $stmt->fetchAll(PDO::FETCH_ASSOC); // fetch all posts as an associative array
        $posts = array_reverse($posts); // Reverse the order of posts to show the most recent first

    } catch (PDOException $e) {
        $posts = [];
        $dbError = $e->getMessage();
    }
?>


<!DOCTYPE html>
<html> 
    <head>
        <!--LINKS + names-------------------------------------------------------------->
        <title>Vietty | home</title>
        <link rel="stylesheet" href="styles.css">
        <script src="script.js" defer></script>
        <meta charset=”utf-8”>
        <link rel="icon" href="images/Vietty_icon.png" type="image/x-icon">
        
        <!--FONTS-------------------------------------------------------------------------->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Rubik+Dirt&display=swap" rel="stylesheet">

        <!---header-------------------------------------------------------------->
        <div class="header">  
            <div class="header-name">
                 <h1>Vietty</h1>
            </div>
            
            <div class="header-buttons">
                <!--buttons change depending on login status-->
                <?php if (isset($_SESSION["user_id"])): ?>

                    <!---logged in-------------------------------------------------------------->

                    <form action="logout.php" method="POST"> 
                        <button type="submit">Log out</button>
                    </form>

                <?php else: ?>

                    <!---logged out------------------------------------------------------------------------------->

                    <a href="signup.html"><button id="signin-page-button">Sign Up</button></a>
                    <a href="login.html"><button id="login-page-button">Log In</button></a>

                <?php endif; ?>
            </div>
        </div>
    
        <!-----------sidenav--------------------------------------------------------------------------------------->
        <div class = "side-nav">
            <div class="profile-card">
                <p></p>
            </div>
            <div class = "sidenav-items">
                
                <a href ="index.php" class = "sidenav-item-current" link ="index.php">
                    <img src="images/home_icon.png" alt="home icon" width="20px">
                    <p id="home-tab">Home</p>
                </a>

                <a href ="profile.php" class = "sidenav-item">
                    <img src="images/profile_icon.png" alt="profile icon" width="20px">
                    <p id="profile-tab">Profile</p>
                </a>
            </div>
        </div>
    </head>

    <!---feed-------------------------------------->

    <body>
        <div class = "feed">

            <!--inputs show or buttons show depending on login status-------------->

            <?php if (isset($_SESSION["user_id"])): ?>

            <!---logged in, input fields-------------------------------------->

                <form class="create-post-form" action="createpost-form.php" method="post">
                    <div class="create-post-user-info">
                        <img src="images/default_profile.png" width ="40px" height="40px">
                        <!-- link the logged in user's name to their own profile -->
                        <h3>
                            <a href="profile.php?user_id=<?php echo htmlspecialchars($_SESSION['user_id']); ?>">
                                <?php $username = $_SESSION['username']; echo htmlspecialchars($username); ?>
                            </a>
                        </h3>
                    </div>
                    <input type="text" id="createPostHeaderInput" name="title" placeholder="Title" >
                    <textarea rows="6" cols="50" type="text" id="createPostParagraphInput" name="body" placeholder="Body text"></textarea>
                    <button id="createPostButton" class="create-post-button">+ Create Post</button>
                </form>

            <?php else: ?>

                <!---logged out, sign up or log in options-------------------------------------->

                <div class="logged-out-post-instruction">
                    <a href="signup.html"><button id="signin-page-button">Sign Up</button></a>
                    <p> or </p>
                    <a href="login.html"><button id="login-page-button">Log In</button></a>
                    <p> to create a post!</p>
                </div>

            <?php endif; ?>

            <!---display posts-------------------------------------------------------------->

            <?php if (!empty($dbError)): ?>
                <div class="db-error">Database error: <?php echo htmlspecialchars($dbError); ?></div>
            <?php endif; ?>

            <!--if there are no posts----------------------------------------------------------->

            <?php if (empty($posts)): ?>
                <div class="no-posts">No posts yet.</div>
            <?php endif; ?>

            <!---post cards-------------------------------------------------------------------------->

            <?php foreach ($posts as $post): ?>
            <div class="text-post-card">
                <div class = "user-info">  
                    <img src="images/default_profile.png" width ="40px" height="40px">
                    <h3>
                        <?php
                        // determine whether the post author is the current logged-in user
                        $isOwn = isset($_SESSION['user_id']) && $_SESSION['user_id'] == $post['userid'];
                        // if it's users own post, open the profile in the same tab. otherwise open in a new tab
                        if ($isOwn): ?>
                            <a href="profile.php?user_id=<?php echo htmlspecialchars($post['userid']); ?>"><?php echo htmlspecialchars($post['username']); ?></a>
                        <?php else: ?>
                            <a href="profile.php?user_id=<?php echo htmlspecialchars($post['userid']); ?>" target="_blank" rel="noopener noreferrer"><?php echo htmlspecialchars($post['username']); ?></a>
                        <?php endif; ?>
                    </h3>
                    <div class = "text-post-card-date">
                        <h6>posted on <?php echo htmlspecialchars($post['date']); ?></h6>
                    </div>
                </div>
                <div class = "text-post-card-heading">
                    <h2><?php echo htmlspecialchars($post['title']); ?></h2>
                </div>
                <div class = "text-post-card-content">
                    <p><?php echo nl2br(htmlspecialchars($post['body'])); ?></p>
                </div>
            </div>
            <?php endforeach; ?>

        </div>
    </body>

    <footer>
    </footer>
</html>    