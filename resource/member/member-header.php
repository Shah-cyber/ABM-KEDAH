<header class="d-flex justify-content-between align-items-center p-3">

<div class="adminsidebarmenu-wrapper" hidden>
    <img height="30" width="30" src="../img/adminsidebarmenu.png">
</div>
<div class="notification-wrapper">
    <img height="30" width="30" src="../img/notification.png">
</div>

<div class="adminprofile-wrapper">
    <div class="circle" id="profileCircle">
        <img src="../img/adminprofile.png">
    </div>
    <div class="nametitle-wrapper">
            <span><?php echo htmlspecialchars($userInfo['username']); ?></span>
            <span>Member</span>
    </div>
</div>

<div class="profile-popup" id="profilePopup">
    <ul>
        <li><a href="#profile" class="profilepopup-options"><img src="../img/user.png"><span class="px-2">My Profile</span></a></li>
        <li class="text-center signoutlist"><a class="signoutbtn text-decoration-none" href="signout.php">Sign Out</a></li>
    </ul>
</div>
</header>