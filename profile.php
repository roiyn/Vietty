<?php
    require_once 'config_session.php';
    require_once 'dbh.php';

    // determine which user's profile to show: ?user_id= or fall back to the logged-in user
    $viewUserId = null;
    // check if a user_id is provided in the GET request
    if (isset($_GET['user_id'])) {
        $viewUserId = (int)$_GET['user_id'];
    // otherwise use the logged-in user's id    
    } elseif (isset($_SESSION['user_id'])) {
        $viewUserId = (int)$_SESSION['user_id'];
    }

    if ($viewUserId === null) {
        $posts = [];
        $dbError = 'No user specified.';
        $user = null;
    } else {
        try {
            // fetch basic user info
            $stmtUser = $pdo->prepare('SELECT id, username, year FROM users WHERE id = ?');
            $stmtUser->execute([$viewUserId]);
            $user = $stmtUser->fetch(PDO::FETCH_ASSOC);
            // if the user is not found, set an error message
            if (!$user) {
                $posts = [];
                $dbError = 'User not found.';
            // otherwise fetch the user's posts    
            } else {
                $stmt = $pdo->prepare("SELECT posts.*, users.username FROM posts JOIN users ON posts.userid = users.id WHERE posts.userid = ?");
                $stmt->execute([$viewUserId]);
                $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $posts = array_reverse($posts); // Reverse the order of posts to show the most recent first
            }
        } catch (PDOException $e) {
            $posts = [];
            $dbError = $e->getMessage();
        }
    }
?>

<!DOCTYPE html>
<html> 
    <head>
        <title>Vietty | profile</title>
        <link rel="stylesheet" href="styles.css">
        <script src="script.js" defer></script>
        <meta charset=”utf-8”>
        <link rel="icon" href="images/Vietty_icon.png" type="image/x-icon">
        
        <!--FONTS---------------------------------------------------------->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Rubik+Dirt&display=swap" rel="stylesheet">

        <!---header---------------------------------------------------------->
        <div class="header">        
            <div class="header-buttons">
                <?php if (isset($_SESSION["user_id"])): ?>
                    <!---logged in---------------------------------------------------------->

                    <form action="logout.php" method="POST"> 
                        <button type="submit">Log out</button>
                    </form>

                <?php else: ?>
                    <!---logged out---------------------------------------------------------->

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
                
                <a href ="index.php" class = "sidenav-item" link ="index.php">
                    <img src="images/home_icon.png" alt="home icon" width="20px">
                    <p id="home-tab">Home</p>
                </a>

                <a href ="profile.php" class = "sidenav-item-current">
                    <img src="images/profile_icon.png" alt="profile icon" width="20px">
                    <p id="profile-tab">Profile</p>
                </a>
            </div>
        </div>
    </head>

    <body>
       
    <!---feed-------------------------------------->
        <div class = "profile-feed">

            <!-- if a viewed user was found, render their  profile and posts -->
            <?php if ($user): ?>

            <!---show profile of other users (yourself does not need to be logged in to view)---------------------------------------------------------->
                <div class="user-profile-card">
                    <div class = "user-info">  
                        <img src="images/default_profile.png" width ="40px" height="40px">
                        <h1><?php echo isset($user['username']) ? htmlspecialchars($user['username']) : 'Unknown user'; ?></h1>
                    </div>

            <!---show profile of own user if logged in---------------------------------------------------------->
                    <div class = "user-profile-info">
                        <?php if (isset($_SESSION['user_id']) && isset($user['id']) && $_SESSION['user_id'] == $user['id']): ?>                
                            <p>Level: <?php echo htmlspecialchars($user['year'] ?? ''); ?> </p>
                            <p>user id: <?php echo htmlspecialchars($user['id']); ?></p>
                        <?php endif; ?>
                    </div>
                </div>    

                <!---user's name title---------------------------------------------------------->
                <div class="profile-divider">
                    <h1><?php echo isset($user['username']) ? htmlspecialchars($user['username']) : 'User'; ?>'s Posts</h1>
                </div>

                <!---if there is database error---------------------------------------------------------->
                <?php if (!empty($dbError)): ?>
                <div class="db-error">Database error: <?php echo htmlspecialchars($dbError); ?></div>
                <?php endif; ?>

                <!---if there are no posts---------------------------------------------------------->
                <?php if (empty($posts)): ?>
                    <div class="no-posts">No posts yet.</div>
                <?php endif; ?>

                <!---iterate over the user's posts and render each post card ----------------------->
                <?php foreach ($posts as $post): ?>
                <!-- Post card -->
                <div class="text-post-card">
                    <div class = "user-info">  
                        <img src="images/default_profile.png" width ="40px" height="40px">
                        <h3><?php echo htmlspecialchars($post['username']); ?></h3>
                        <div class = "text-post-card-date">
                        	<h6><?php echo htmlspecialchars($post['date']); ?></h6>
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

            <?php else: ?>
            <!---logged out---------------------------------------------------------->

                <div class="logged-out-profile-instruction">
                    <a href="signup.html"><button id="signin-page-button">Sign Up</button></a>
                    <p> or </p>
                    <a href="login.html"><button id="login-page-button">Log In</button></a>
                    <p> to view your profile!</p>
                </div>

            <?php endif; ?>
        </div>
    </body>

    <footer>
    </footer>
</html>    